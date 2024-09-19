<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers;
use MeSomb\Operation\PaymentOperation;
use Illuminate\Http\Request;

class CheckPaymentStatusController extends Controller
{
    use Helpers;

    public function __invoke(Request $request)
    {
        $client = new PaymentOperation(env('MESOMB_APP_KEY'), env('MESOMB_ACCESS_KEY'), env('MESOMB_SECRET_KEY'));

        $transactions = $client->getTransactions([$request->input('transactionId')], 'EXTERNAL');

        return match (collect($transactions)->first()->status) {
            'FAILED' => $this->errorResponse('La transaction a échoué. Veuillez réessayer', ['status' => 'FAILED']),
            'SUCCESS' => $this->successResponse('La transaction a été effectuée avec succès'),
            default => $this->errorResponse('Paiement en cours', ['status' => 'PENDING'])
        };
    }
}
