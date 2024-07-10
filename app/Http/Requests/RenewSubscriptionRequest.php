<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Http\Traits\Helpers;
use App\Models\PaymentMethod;
use App\Models\RenewSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Http\FormRequest;
use Hachther\MeSomb\Operation\Payment\Collect;

class RenewSubscriptionRequest extends FormRequest
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
        return $this->conditionalValidation();
    }

    public function store(): JsonResponse
    {
        DB::beginTransaction();

        $user = User::userByUUID($this->input('identifier'))->first();
        $admin = User::superAdmin()->first();
        $paymentMethod = PaymentMethod::searchByUUID($this->input('method'))->first();
        $formula = Category::categoryByUUID($this->input('formula'))->first();
        $option = strlen($this->input('option')) > 0 ? Category::categoryByUUID($this->input('option'))->first() : '';
        $amount = (int) $this->input('amount');

        try {
            // Check methodId to know from which account to deduct fonds
            if ($paymentMethod->id === 1) {
                // check if $balance < $amount
                if ($user->account->balance < $amount) {
                    return $this->errorResponse(Lang::get('messages.error.insufficient_balance', [], 'en'));
                } else {
                    // Deduct funds form user account
                    $user->account()->decrement('balance', $amount);
                }
            } else {
                // Add MTN && Orange shortCode to respective payment Methods in DB
                if (env('APP_DEBUG')) {
                    $request = new Collect(env('MESOMB_TEST_SUCCESS_NUMBER'), 1000, 'MTN', 'CM');
                } else {
                    $request = new Collect($this->input('phone'), 1000, $paymentMethod->short_code, 'CM');
                }

                $payment = $request->pay();

                if (!$payment->success) {
                    // Fire some event, Pay someone, Alert user
                    return $this->errorResponse('operation failed. Please try again');
                }
            }

            // Create transaction record
            DB::transaction(function () use ($paymentMethod, $user, $admin, $formula, $option) {
                return tap(Transaction::create([
                    'uuid' => Str::uuid(),
                    'type' => 2,
                    'amount' => trim($this->input('amount')),
                    'commission' => 0.00,
                    'method_id' => $paymentMethod->id,
                    'sender_id' => $user->id, // sender user id
                    'sender_account' => $user->account()->value('id'),
                    'sender_account_balance' => $paymentMethod->id === 1 ? $user->account->balance : 0.00,
                    'receiver_id' => 1, // receiver user id
                    'receiver_account' => 1,
                    'receiver_account_balance' =>  $admin->account->balance
                ]), function (Transaction $transaction) use ($user, $formula, $option, $paymentMethod) {
                    $transaction->renewSubscription()->create([
                        'uuid' => Str::uuid(),
                        'user_id' => $user->id,
                        'decoder' => trim($this->input('decoder')),
                        'name' => trim($this->input('name')),
                        'formula_id' => $formula->id,
                        'option_id' => strlen($this->input('option')) > 0 ? $option->id : null,
                        'duration' => trim($this->input('duration')),
                        'phone' => trim($this->input('phone')),
                        'method_id' => $paymentMethod->id,
                        'amount' => trim($this->input('amount'))
                    ]);
                });
            });

            // inform admin through event
            DB::commit();

            return $this->successResponse(Lang::get('messages.success.subscription_saved', [], 'en'), []);
        } catch (Exception $e) {
            DB::rollBack();

            Log::critical($e->getMessage(), [
                'trace' => $e
            ]);

            return $this->errorResponse('operation failed');
        }
    }

    private function conditionalValidation(): array
    {
        $output = [
            'identifier' => 'required|exists:users,uuid',
            'decoder' => 'required|string|size:14',
            'name' => 'required|string',
            'formula' => 'required|exists:categories,uuid',
            'duration' => 'required|string|numeric',
            'phone' => 'required|string',
            'method' => 'required|exists:payment_methods,uuid',
            'amount' => 'required|numeric|integer'
        ];

        if (strlen($this->input('option')) > 0) {
            $output['option'] = 'required|exists:categories,uuid';
        }
        return $output;
    }
}
