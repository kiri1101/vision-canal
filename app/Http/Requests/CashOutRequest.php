<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use App\Rules\Account;
use App\Rules\UserExists;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Http\Traits\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Http\FormRequest;

class CashOutRequest extends FormRequest
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
            'user' => ['required', new UserExists],
            'account' => ['required', new Account],
            'amount' => 'required|numeric|min:1'
        ];
    }

    public function processing(): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $user = User::userWithPhone($this->input('user')['tel'])->first();

            $superAdmin = User::superAdmin()->first();

            if ($this->input('account')['id'] === 1) {
                if ($user->account()->value('balance') < $this->input('amount')) {
                    back()->withErrors(['error' => 'Insufficient user balance']);
                } else {
                    $user->account()->decrement('balance', $this->input('amount'));
                    // create new transaction
                    Transaction::create([
                        'uuid' => Str::uuid(),
                        'type' => 2,
                        'amount' => $this->input('amount'),
                        'method_id' => 1,
                        'sender_id' => $superAdmin->id,
                        'sender_account' => $this->input('account')['id'], // '1' => main, '2' => 'commission', '3' => 'cash'
                        'sender_account_balance' => $superAdmin->account()->value('balance'),
                        'receiver_id' => $user->id,
                        'receiver_account' => $this->input('account')['id'], // '1' => main, '2' => 'commission', '3' => 'cash'
                        'receiver_account_balance' => $user->account()->value('balance'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } elseif ($this->input('account')['id'] === 2) {
                if ($user->account()->value('commission') < $this->input('amount')) {
                    back()->withErrors(['error' => 'Insufficient user balance']);
                } else {
                    $user->account()->decrement('commission', $this->input('amount'));
                    // create new transaction
                    Transaction::create([
                        'uuid' => Str::uuid(),
                        'type' => 2,
                        'amount' => $this->input('amount'),
                        'method_id' => 1,
                        'sender_id' => $superAdmin->id,
                        'sender_account' => $this->input('account')['id'], // '1' => main, '2' => 'commission', '3' => 'cash'
                        'sender_account_balance' => $superAdmin->account()->value('commission'),
                        'receiver_id' => $user->id,
                        'receiver_account' => $this->input('account')['id'], // '1' => main, '2' => 'commission', '3' => 'cash'
                        'receiver_account_balance' => $user->account()->value('commission'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::commit();

            return back();
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Operation failed. Please try again']);
        }
    }
}
