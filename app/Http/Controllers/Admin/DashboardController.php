<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\Helpers;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashInRequest;
use App\Http\Requests\CashOutRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\TransactionResource;

class DashboardController extends Controller
{
    use Helpers;

    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'userList' => User::isNotAdmin()->get()->map(fn ($user) => [
                'id' => $user->uuid,
                'name' => $user->name,
                'tel' => $user->phone
            ]),
            'accounts' => collect(config('constants.account'))->pipe(function (Collection $collection) {
                $collection->pop();
                return $collection->all();
            }),
            'subs' => [
                'number' => Subscription::count(),
                'list' => [
                    (object) [
                        'name' => 'Subscriptions',
                        'data' => $this->subscriptionGraphData()
                    ]
                ]
            ],
            'budget' => [
                'total' => $this->formatAmount($this->profit())
            ],
            'transactions' => [
                'number' => Transaction::count(),
                'list' => TransactionResource::collection(Transaction::all())
            ]
        ]);
    }

    public function cashIn(CashInRequest $request): RedirectResponse
    {
        return $request->store();
    }

    public function cashOut(CashOutRequest $request): RedirectResponse
    {
        return $request->processing();
    }

    private function subscriptionGraphData(): array
    {
        $subsData = config('constants.xAxisMonth');
        $subsOutput = [];
        $subs = Subscription::query()
            ->whereYear('created_at', now()->year)
            ->get()
            ->groupBy(function ($q) {
                return Carbon::parse($q->created_at)->format('m');
            })->toArray();

        foreach ($subsData as $value) {
            if (array_key_exists($value, $subs)) {
                array_push($subsOutput, count($subs[$value]));
            } else {
                array_push($subsOutput, 0);
            }
        }
        return $subsOutput;
    }

    private function profit()
    {
        $deposits = Transaction::allDeposits()->get();
        $withdrawals = Transaction::allWithdrawals()->get();

        $totalDepositBalance = $deposits->sum('amount');
        $totalDepositCommission = $deposits->sum('commission');
        $totalDeposits = $totalDepositBalance + $totalDepositCommission;

        $totalWithdrawalBalance = $withdrawals->sum('amount');
        $totalWithdrawalCommission = $withdrawals->sum('commission');
        $totalWithdrawals = $totalWithdrawalBalance + $totalWithdrawalCommission;

        $profit = $totalDeposits - $totalWithdrawals;

        return (int) $profit;
    }
}
