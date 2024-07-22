<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Http\Traits\Helpers;
use App\Rules\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use App\Models\PaymentMethod as Payment;
use App\Http\Resources\TransactionResource;
use Illuminate\Foundation\Http\FormRequest;
use Hachther\MeSomb\Operation\Payment\Deposit;

class TopUpRequest extends FormRequest
{
    use Helpers;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'method' => ['required', new PaymentMethod],
            'amount' => 'required|numeric|min:1'
        ];
    }

    public function cashIn(User $user)
    {
        DB::beginTransaction();

        try {
            $admin = User::superAdmin()->first();
            $paymentMethod = Payment::searchByUUID($this->input('method'))->first();
            $amount = (int) $this->input('amount');

            $request = env('APP_DEBUG') ? new Deposit(env('MESOMB_TEST_SUCCESS_NUMBER'), 1000, 'MTN', 'CM') : $request = new Deposit($this->input('phone'), 1000, $paymentMethod->short_code, 'CM');

            $payment = $request->pay();

            if (!$payment->success) {
                return $this->errorResponse('operation failed. Please try again');
            } else {
                $user->account()->increment('balance', $amount);

                // Create transaction record
                DB::transaction(function () use ($paymentMethod, $user, $admin) {
                    return Transaction::create([
                        'uuid' => Str::uuid(),
                        'type' => 1,
                        'amount' => trim($this->input('amount')),
                        'commission' => 0.00,
                        'method_id' => $paymentMethod->value('id'),
                        'sender_id' => 1, // sender user id
                        'sender_account' => 1,
                        'sender_account_balance' => $admin->account->balance,
                        'receiver_id' => $user->id, // receiver user id
                        'receiver_account' => $user->account()->value('id'),
                        'receiver_account_balance' =>  $paymentMethod->value('id') === 1 ? $user->account->balance : 0.00
                    ]);
                });

                $transactions = Transaction::transactionsByUser($user->id)->latest()->get();

                DB::commit();

                return $this->successResponse(Lang::get('messages.success.subscription_saved', [], 'en'), [
                    'transactions' => TransactionResource::collection($transactions),
                    'total' => $user->account()->value('balance')
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();

            Log::critical($e->getMessage(), [
                'trace' => $e
            ]);

            return $this->errorResponse('operation failed');
        }
    }
}
