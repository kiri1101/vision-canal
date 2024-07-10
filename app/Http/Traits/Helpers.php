<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait Helpers
{
    public function removeSpaceBetweenStringChar(String $string): string
    {
        return str_replace(' ', '', $string);
    }


    /**
     * Response for Http response 200
     *
     * @param string $message,
     * @param array|object $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse(string $message, array|object $data = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => 200,
            'data' => $data
        ]);
    }

    /**
     * Response for Http response 500
     *
     * @param string $message,
     * @param array|object $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse(string $message, array|object $data = [], int $code = 500): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], 500);
    }

    /**
     * Format an amount to make it readable
     *
     * @param string $amount
     *
     * @return string
     */
    public function formatAmount(string $amount): string
    {
        // Reverse the amount
        $reversedString = strrev($amount);

        // Add a , after every 3 digits from the first to the last
        $formattedString = preg_replace('/(\d{3})(?=\d)(?!$)/', '$1,', $reversedString);

        // Reverse the formatted amount, round it up and return the result
        return 'FCFA' . strrev($formattedString);
    }

    public function searchAccount(String $query, String $type = 'email')
    {
        $token = '';

        try {
            $authResponse = $this->fujiAuth();

            if (collect($authResponse->data)->isEmpty()) {
                return $this->errorResponse('operation failed! please try again');
            } else {
                $token = $authResponse->data->token;

                Log::info('account search parameters: ', [
                    'numabo' => $type === 'abonner' ? $query : '',
                    'numdecabo' => $type === 'decoder' ? $query : '',
                    'emailabo' => $type === 'email' ? $query : '',
                    'telabo' => $type === 'phone' ? $query : '',
                ]);

                $response = Http::withToken($token)->connectTimeout(env('API_TIMEOUT'))->post(env('PARTNER_URL') . env('PARTNER_SEARCH_SUBSCRIBER_URI'), [
                    'numabo' => $type === 'abonner' ? $query : '',
                    'numdecabo' => $type === 'decoder' ? $query : '',
                    'emailabo' => $type === 'email' ? $query : '',
                    'telabo' => $type === 'phone' ? '00237' . $query : ''
                ]);

                Log::info('account search response: ', [
                    'data' => $response->object()
                ]);

                return $response->object();
            }
        } catch (Exception $e) {
            Log::emergency('Failed to search user account at FujiSAT', [
                'trace' => $e
            ]);

            return [];
        }
    }

    private function fujiAuth()
    {
        try {
            Log::info('partner authentication parameters: ', [
                'username' => env('PARTNER_PSEUDO'),
                'password' => env('PARTNER_PWD')
            ]);

            $response = Http::connectTimeout(env('API_TIMEOUT'))->post(env('PARTNER_URL') . env('PARTNER_AUTH_URI'), [
                'username' => env('PARTNER_PSEUDO'),
                'password' => env('PARTNER_PWD')
            ]);

            Log::info('account search response: ', [
                'data' => $response->object()
            ]);

            return $response->object();
        } catch (Exception $e) {
            Log::emergency('Failed to authenticate partner at FujiSAT', [
                'trace' => $e
            ]);

            return [];
        }
    }
}
