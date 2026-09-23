<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
|--------------------------------------------------------------------------
| DigiVerify - Verification Result
|--------------------------------------------------------------------------
| Receives OCR results from upload.php
|--------------------------------------------------------------------------
*/


// =====================================================
// GET DATA FROM UPLOAD.PHP
// =====================================================

$detected_name = isset($_GET['name'])
    ? trim($_GET['name'])
    : 'NOT DETECTED';

$aadhaar_no = isset($_GET['aadhaar'])
    ? trim($_GET['aadhaar'])
    : 'XXXX XXXX XXXX';

$is_ai = isset($_GET['is_ai'])
    ? strtolower(trim($_GET['is_ai']))
    : 'false';


// =====================================================
// BASIC VALIDATION
// =====================================================

if ($detected_name === '') {
    $detected_name = 'NOT DETECTED';
}

if ($aadhaar_no === '') {
    $aadhaar_no = 'XXXX XXXX XXXX';
}


// =====================================================
// DETERMINE STATUS
// =====================================================

if ($is_ai === 'true') {

    $status = 'REVIEW REQUIRED';

    $status_class = 'warning';

    $status_icon = '⚠';

    $status_message =
        'The uploaded document contains text patterns ' .
        'that require additional review.';

} else {

    $status = 'OCR SCREENING PASSED';

    $status_class = 'success';

    $status_icon = '✓';

    $status_message =
        'The document passed the basic OCR-based ' .
        'screening checks.';
}


// =====================================================
// MASK AADHAAR NUMBER
// =====================================================

$display_aadhaar = $aadhaar_no;

if (
    preg_match(
        '/^(\d{4})\s+(\d{4})\s+(\d{4})$/',
        $aadhaar_no,
        $matches
    )
) {

    $display_aadhaar =
        'XXXX XXXX ' . $matches[3];
}


// =====================================================
// SAFE OUTPUT
// =====================================================

$safe_name =
    htmlspecialchars(
        $detected_name,
        ENT_QUOTES,
        'UTF-8'
    );

$safe_aadhaar =
    htmlspecialchars(
        $display_aadhaar,
        ENT_QUOTES,
        'UTF-8'
    );

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        DigiVerify | Verification Result
    </title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            font-family:
                'Segoe UI',
                Arial,
                sans-serif;

            background:
                radial-gradient(
                    circle at top,
                    #10254a 0%,
                    #08111f 45%,
                    #040914 100%
                );

            color: #ffffff;

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 30px;
        }


        .container {

            width: 100%;

            max-width: 650px;
        }


        .result-card {

            background:
                linear-gradient(
                    145deg,
                    rgba(15, 23, 42, 0.98),
                    rgba(8, 18, 35, 0.98)
                );

            border:
                1px solid
                rgba(56, 189, 248, 0.25);

            border-radius: 25px;

            padding: 40px;

            box-shadow:
                0 30px 80px
                rgba(0, 0, 0, 0.65);

            text-align: center;
        }


        .logo {

            font-size: 14px;

            letter-spacing: 3px;

            color: #38bdf8;

            font-weight: 800;

            margin-bottom: 15px;
        }


        h1 {

            font-size: 30px;

            margin-bottom: 8px;

            font-weight: 800;
        }


        .subtitle {

            color: #94a3b8;

            font-size: 14px;

            margin-bottom: 30px;
        }


        .status-box {

            border-radius: 18px;

            padding: 25px;

            margin-bottom: 25px;
        }


        .status-box.success {

            background:
                rgba(34, 197, 94, 0.08);

            border:
                1px solid
                rgba(34, 197, 94, 0.35);
        }


        .status-box.warning {

            background:
                rgba(245, 158, 11, 0.08);

            border:
                1px solid
                rgba(245, 158, 11, 0.35);
        }


        .status-icon {

            width: 65px;

            height: 65px;

            border-radius: 50%;

            display: flex;

            justify-content: center;

            align-items: center;

            margin: 0 auto 15px;

            font-size: 32px;

            font-weight: 800;
        }


        .success .status-icon {

            background:
                rgba(34, 197, 94, 0.15);

            color: #4ade80;
        }


        .warning .status-icon {

            background:
                rgba(245, 158, 11, 0.15);

            color: #fbbf24;
        }


        .status-title {

            font-size: 20px;

            font-weight: 800;

            margin-bottom: 8px;
        }


        .status-message {

            color: #94a3b8;

            font-size: 13px;

            line-height: 1.6;
        }


        .details {

            text-align: left;

            border:
                1px solid
                rgba(148, 163, 184, 0.15);

            border-radius: 16px;

            overflow: hidden;

            margin-bottom: 25px;
        }


        .detail-row {

            display: flex;

            justify-content: space-between;

            gap: 20px;

            padding: 17px 20px;

            border-bottom:
                1px solid
                rgba(148, 163, 184, 0.1);
        }


        .detail-row:last-child {

            border-bottom: none;
        }


        .detail-label {

            color: #64748b;

            font-size: 13px;

            font-weight: 600;
        }


        .detail-value {

            color: #e2e8f0;

            font-size: 14px;

            font-weight: 700;

            text-align: right;

            word-break: break-word;
        }


        .ai-badge {

            display: inline-block;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 800;

            letter-spacing: 0.5px;
        }


        .ai-safe {

            background:
                rgba(34, 197, 94, 0.12);

            color: #4ade80;
        }


        .ai-risk {

            background:
                rgba(245, 158, 11, 0.12);

            color: #fbbf24;
        }


        .notice {

            background:
                rgba(56, 189, 248, 0.06);

            border:
                1px solid
                rgba(56, 189, 248, 0.15);

            border-radius: 14px;

            padding: 15px;

            color: #94a3b8;

            font-size: 12px;

            line-height: 1.6;

            text-align: left;

            margin-bottom: 25px;
        }


        .buttons {

            display: flex;

            gap: 12px;
        }


        .btn {

            flex: 1;

            padding: 14px 20px;

            border-radius: 12px;

            text-decoration: none;

            font-size: 14px;

            font-weight: 700;

            transition: 0.3s;
        }


        .btn-primary {

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #9333ea
                );

            color: white;
        }


        .btn-secondary {

            background:
                rgba(56, 189, 248, 0.06);

            border:
                1px solid
                rgba(56, 189, 248, 0.3);

            color: #38bdf8;
        }


        .btn:hover {

            transform:
                translateY(-2px);
        }


        @media (max-width: 600px) {

            .result-card {

                padding: 25px;
            }


            h1 {

                font-size: 25px;
            }


            .detail-row {

                flex-direction: column;

                gap: 5px;
            }


            .detail-value {

                text-align: left;
            }


            .buttons {

                flex-direction: column;
            }
        }

    </style>

</head>


<body>


<div class="container">


    <div class="result-card">


        <div class="logo">
            ENTERPRISE DIGIVERIFY
        </div>


        <h1>
            Verification Result
        </h1>


        <div class="subtitle">
            AI-assisted document screening
        </div>


        <!-- STATUS -->

        <div
            class="status-box <?php echo $status_class; ?>"
        >

            <div class="status-icon">
                <?php echo $status_icon; ?>
            </div>


            <div class="status-title">
                <?php echo $status; ?>
            </div>


            <div class="status-message">
                <?php echo $status_message; ?>
            </div>

        </div>


        <!-- DETAILS -->

        <div class="details">


            <div class="detail-row">

                <div class="detail-label">
                    Document Type
                </div>

                <div class="detail-value">
                    Aadhaar Card
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Detected Name
                </div>

                <div class="detail-value">
                    <?php echo $safe_name; ?>
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Aadhaar Number
                </div>

                <div class="detail-value">
                    <?php echo $safe_aadhaar; ?>
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    OCR Engine
                </div>

                <div class="detail-value">
                    Tesseract.js
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    AI Screening
                </div>

                <div class="detail-value">

                    <?php if ($is_ai === 'true'): ?>

                        <span class="ai-badge ai-risk">
                            REVIEW REQUIRED
                        </span>

                    <?php else: ?>

                        <span class="ai-badge ai-safe">
                            BASIC SCREENING PASSED
                        </span>

                    <?php endif; ?>

                </div>

            </div>


        </div>


        <!-- NOTICE -->

        <div class="notice">

            <strong>Verification Notice:</strong><br>

            This result is based on OCR extraction and
            basic document screening performed by the
            DigiVerify application. It does not by itself
            constitute official Aadhaar authentication or
            confirmation from UIDAI.

        </div>


        <!-- BUTTONS -->

        <div class="buttons">


            <a
                href="upload.php"
                class="btn btn-primary"
            >
                Verify Another Document
            </a>


            <a
                href="/"
                class="btn btn-secondary"
            >
                Home
            </a>


        </div>


    </div>


</div>


</body>

</html>
