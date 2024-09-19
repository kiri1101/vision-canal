<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Http\Traits\Helpers;
use App\Models\PaymentMethod;
use MeSomb\Operation\PaymentOperation;
use MeSomb\Util\RandomGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'identifier' => 'required|exists:users,uuid',
            'decoder' => 'required|string|size:14',
            'name' => 'required|string',
            'formula' => 'required|exists:categories,uuid',
            'duration' => 'required|string|numeric',
            'phone' => 'required|string',
            'method' => 'required|exists:payment_methods,uuid',
            'amount' => 'required|numeric|integer',
            'option' => strlen($this->input('option')) > 0 ? 'required|exists:categories,uuid' : ''
        ];
    }

    public function store(): JsonResponse
    {
        $user = User::userByUUID($this->input('identifier'))->first();
        $admin = User::superAdmin()->first();
        $paymentMethod = PaymentMethod::searchByUUID($this->input('method'))->first();
        $formula = Category::categoryByUUID($this->input('formula'))->first();
        $option = strlen($this->input('option')) > 0 ? Category::categoryByUUID($this->input('option'))->value('id') : null;
        $amount = env('APP_DEBUG') ? env('MESOMB_TEST_AMOUNT') : (int) $this->input('amount');
        $phone = env('APP_DEBUG') ? env('MESOMB_DEV_TEST_NUMBER') : $this->removeSpaceBetweenStringChar($this->input('phone'));

        try {
            // Check methodId to know from which account to deduct fonds
            if ($paymentMethod->id === 1) {
                // check if $balance < $amount
                if ($user->account->balance < $amount) {
                    $redirect = $this->errorResponse(Lang::get('messages.error.insufficient_balance', [], 'en'));
                } else {
                    // Deduct funds form user account
                    $user->account()->decrement('balance', $amount);
                    $redirect = $this->successResponse(Lang::get('messages.success.subscription_saved', [], 'en'));
                }
            } else {
                // Add MTN && Orange shortCode to respective payment Methods in DB
                $client = new PaymentOperation(env('MESOMB_APP_KEY'), env('MESOMB_ACCESS_KEY'), env('MESOMB_SECRET_KEY'));
                // Generate uuid string
                $transactionId = Str::uuid();
                // initiate collect transaction
                $client->makeCollect([
                    'amount' => $amount,
                    'service' => $paymentMethod->short_code,
                    'payer' => $phone,
                    'fees' => false,
                    'nonce' => RandomGenerator::nonce(),
                    'trxID' => $transactionId
                ]);

                $this->recordTransaction($paymentMethod, $user, $admin, $formula, $option, $transactionId);

                $redirect = $this->successResponse(Lang::get('messages.success.subscription_saved', [], 'en'), ['transactionID' => $transactionId]);
            }
            return $redirect;
        } catch (Exception $e) {
            Log::critical($e->getMessage(), [
                'code' => $e->getCode(),
                'message' => 'Internal Error',
                'trace' => $e
            ]);

            return $this->errorResponse(Lang::get('messages.error.mesomb.server_error', [], 'en'), ['transactionID' => $transactionId]);
        }
    }

    private function recordTransaction(object $paymentMethod, object $user, object $admin, object $formula, int|null $option, $transactionID): array
    {
        // Create transaction record
        return DB::transaction(function () use ($paymentMethod, $user, $admin, $formula, $option, $transactionID) {
            return tap(Transaction::create([
                'uuid' => $transactionID,
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
            ]), function (Transaction $transaction) use ($user, $formula, $option, $paymentMethod, $transactionID) {
                $transaction->renewSubscription()->create([
                    'uuid' => $transactionID,
                    'user_id' => $user->id,
                    'decoder' => trim($this->input('decoder')),
                    'name' => trim($this->input('name')),
                    'formula_id' => $formula->id,
                    'option_id' => $option,
                    'duration' => trim($this->input('duration')),
                    'phone' => trim($this->input('phone')),
                    'method_id' => $paymentMethod->id,
                    'amount' => trim($this->input('amount'))
                ]);
            });
        });
    }
}
