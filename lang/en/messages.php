<?php

return [

    'success' => [
        'authorization_token' => 'Authorization token generated successfully',
        'default_response' => 'Operation successful',
        'user_created' => 'User created successfully',
        'subscription_saved' => 'Request for subscription saved successfully',
        'subscription_renewal_saved' => 'Request to renew subscription saved successfully',
        'support_saved' => 'Your demand for technical support is saved',
        'file_uploaded' => 'File upload successful!',
    ],
    'error' => [
        'server_error' => [
            'invalid_authorization' => 'invalid authorization token',
            'inactive_authorization' => 'Unauthorized access. Contact an administrator for more information',
            'failed_register' => 'Registration operation failed. Please contact an administrator',
            'support_failed' => 'Technical support failed. Please try again',
            'user_account_exists' => 'An account with this phone number exists',
        ],
        'insufficient_balance' => 'Insufficient balance',
        'service_unavailable' => 'Functionality unavailable for now'
    ],
    'service' => [
        'subscribe' => 'Subscription',
        'renew' => 'Renew Subscription',
        'support' => 'Support',
        'accessory' => 'Accessory'
    ],
    'miscellaneous' => [
        'otp_message' => 'The OTP code needed for the password reset operation can be found below'
    ]

];
