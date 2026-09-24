<?php

declare(strict_types=1);

session_start();

/*
|--------------------------------------------------------------------------
| DigiVerify Online Aadhaar KYC
|--------------------------------------------------------------------------
| IMPORTANT:
| This file starts an authorized UIDAI KYC transaction.
| It does NOT pretend to verify Aadhaar locally.
|
| Replace the configuration values only after your entity
| has been onboarded and UIDAI/KUA/ASA credentials are issued.
|--------------------------------------------------------------------------
*/

const KYC_ENABLED = false;

/*
 * These values must come from your authorized KUA/ASA setup.
 * DO NOT put random/public credentials here.
 */
const KUA_CODE = '';
const ASA_CODE = '';
const KUA_LICENSE_KEY = '';
const KUA_PRIVATE_KEY = '';

function redirectResult(
    string $status,
    string $message
): never {

    header(
        'Location: verification_result.php?' .
        http_build_query([
            'status' => $status,
            'score' => $status === 'APPROVED' ? 100 : 0,
            'name' => 'NOT AVAILABLE',
            'aadhaar' => 'XXXX XXXX XXXX',
            'reason' => $message,
            'warning' =>
                'Online Aadhaar authentication requires an authorized UIDAI KUA/ASA integration.'
        ])
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Request Method
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: upload.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Aadhaar / VID
|--------------------------------------------------------------------------
*/

$aadhaar = trim(
    (string)($_POST['aadhaar'] ?? '')
);

$vid = trim(
    (string)($_POST['vid'] ?? '')
);

$otp = trim(
    (string)($_POST['otp'] ?? '')
);


/*
|--------------------------------------------------------------------------
| Basic validation
|--------------------------------------------------------------------------
*/

if ($aadhaar === '' && $vid === '') {

    redirectResult(
        'REJECTED',
        'Aadhaar number or VID is required.'
    );
}


/*
|--------------------------------------------------------------------------
| Never store Aadhaar unnecessarily
|--------------------------------------------------------------------------
*/

if ($aadhaar !== '') {

    $digits = preg_replace(
        '/\D/',
        '',
        $aadhaar
    );

    if (
        $digits === null ||
        strlen($digits) !== 12
    ) {

        redirectResult(
            'REJECTED',
            'Invalid Aadhaar number format.'
        );
    }

    /*
     * Do not save this value in database/log files.
     */
}


/*
|--------------------------------------------------------------------------
| OTP validation
|--------------------------------------------------------------------------
*/

if ($otp !== '') {

    if (
        !preg_match(
            '/^[0-9]{6}$/',
            $otp
        )
    ) {

        redirectResult(
            'REJECTED',
            'Invalid OTP format.'
        );
    }
}


/*
|--------------------------------------------------------------------------
| UIDAI integration status
|--------------------------------------------------------------------------
*/

if (!KYC_ENABLED) {

    redirectResult(
        'REJECTED',
        'Online UIDAI KYC is not enabled because this application does not yet have an authorized KUA/ASA integration.'
    );
}


/*
|--------------------------------------------------------------------------
| IMPORTANT
|--------------------------------------------------------------------------
| Actual UIDAI request must be generated according to the
| currently approved UIDAI Authentication/e-KYC API version.
|
| It requires:
|
| 1. KUA credentials
| 2. ASA connectivity
| 3. UIDAI-issued certificates/keys
| 4. Auth XML
| 5. Digital signature
| 6. Encrypted PID block
| 7. OTP/biometric authentication
| 8. Correct UIDAI API endpoint
|
| These values cannot be invented.
|--------------------------------------------------------------------------
*/


redirectResult(
    'REJECTED',
    'UIDAI KUA/ASA credentials and production API configuration are required before an online KYC transaction can be submitted.'
);
