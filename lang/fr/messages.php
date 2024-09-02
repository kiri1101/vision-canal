<?php

return [
    'success' => [
        'authorization_token' => "Le jeton d'autorisation fut généré avec succès",
        'default_response' => "L'operation fut un succès",
        'user_created' => 'Utilisateur créé avec succès',
        'subscription_saved' => "Demande d'abonnement enregistrée avec succès",
        'subscription_renewal_saved' => "La demande de renouvellement de l'abonnement a été enregistrée avec succès",
        'support_saved' => "Votre demande d'assistance technique est sauvegardée",
        'file_uploaded' => 'Chargement du fichier réussi!'
    ],
    'error' => [
        'server_error' => [
            'invalid_authorization' => "Jeton d'autorisation incorrecte",
            'inactive_authorization' => "Accès non autorisé.Contactez un administrateur pour plus d'informations",
            'failed_register' => "L'opération de création d'un compte a échoué.Veuillez contacter un administrateur",
            'support_failed' => "Échec de l'enregistrement de la demande d'assistance technique. Veuillez réessayer",
            'user_account_exists' => 'Il existe un compte avec ce numéro de téléphone',
            'insufficient_balance' => 'Solde insuffisant',
            'service_unavailable' => 'Fonctionaliter indisponible pour le moment'
        ],
        'insufficient_balance' => 'Solde insuffisant',
        'service_unavailable' => 'Functionality unavailable for now',
        'mesomb' => [
            'server_error' => 'Something went wrong. Please kindly try again!',
        ]
    ],
    'service' => [
        'subscribe' => 'Abonnement',
        'renew' => 'Réabonnement',
        'support' => 'Support',
        'accessory' => 'Accéssoires'
    ],
    'miscellaneous' => [
        'otp_message' => "Le code OTP nécessaire pour l'opération de réinitialisation du mot de passe est indiqué ci-dessous"
    ]
];
