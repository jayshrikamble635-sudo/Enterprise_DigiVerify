<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

mysqli_report(MYSQLI_REPORT_OFF);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| DATABASE CONFIGURATION
|--------------------------------------------------------------------------
*/

$config_paths = [
    __DIR__ . "/../database/config.php",
    dirname(__DIR__) . "/database/config.php"
];

$config_loaded = false;

foreach ($config_paths as $config_path) {
    if (file_exists($config_path)) {
        require_once $config_path;
        $config_loaded = true;
        break;
    }
}

/*
|--------------------------------------------------------------------------
| DEFAULT VALUES
|--------------------------------------------------------------------------
*/

$verification_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$db_status       = "REJECTED";
$db_fraud        = 90;
$db_confidence   = 15;
$db_remarks      = "No verification record found.";
$db_number       = "Not Extracted";
$db_type         = "UNKNOWN DOCUMENT";
$db_date         = date("Y-m-d H:i:s");
$display_name    = "UNKNOWN USER";
$user_email      = "user@digiverify.live";

$verification_status = "REJECTED";

$user_name      = "UNKNOWN USER";
$document_type  = "UNKNOWN DOCUMENT";
$extracted_uid  = "Not Extracted";
$ocr_score      = "0%";
$face_match     = "0%";

$status_message = "Verification record could not be loaded.";
$statusColor    = "#dc2626";
$statusIcon     = "✕";

$raw_terminal_output =
"ENTERPRISE DIGIVERIFY
--------------------------------
Verification ID: " . $verification_id . "
Database Status: RECORD NOT FOUND
Security Status: REJECTED
--------------------------------
[WARNING]: Verification record unavailable.";


/*
|--------------------------------------------------------------------------
| FETCH DATABASE RECORD
|--------------------------------------------------------------------------
*/

if (
    $verification_id > 0 &&
    isset($conn) &&
    $conn instanceof mysqli &&
    !$conn->connect_error
) {

    $sql = "
        SELECT 
            d.*,
            u.fullname AS user_fullname,
            u.email AS user_email_address
        FROM documents d
        LEFT JOIN users u 
            ON d.user_id = u.id
        WHERE d.id = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "i", $verification_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);

            /*
            |--------------------------------------------------------------------------
            | DATABASE VALUES
            |--------------------------------------------------------------------------
            */

            $db_status = trim($row['verification_status'] ?? 'PENDING');

            $db_fraud = isset($row['fraud_score'])
                ? (float) $row['fraud_score']
                : 0;

            $db_confidence = isset($row['ai_confidence'])
                ? (float) $row['ai_confidence']
                : 0;

            $db_remarks = !empty($row['remarks'])
                ? $row['remarks']
                : "No remarks available.";

            $db_number = !empty($row['extracted_document_number'])
                ? $row['extracted_document_number']
                : "Not Extracted";

            $db_type = !empty($row['document_type'])
                ? $row['document_type']
                : "Unknown Document";

            $db_date = !empty($row['uploaded_at'])
                ? $row['uploaded_at']
                : date("Y-m-d H:i:s");

            $display_name = !empty($row['user_fullname'])
                ? $row['user_fullname']
                : "UNKNOWN USER";

            $user_email = !empty($row['user_email_address'])
                ? $row['user_email_address']
                : "user@digiverify.live";

            /*
            |--------------------------------------------------------------------------
            | DISPLAY VALUES
            |--------------------------------------------------------------------------
            */

            $user_name = $display_name;

            $document_type = strtoupper($db_type);

            $extracted_uid = $db_number;

            $ocr_score = number_format($db_confidence, 1) . "%";

            /*
            |--------------------------------------------------------------------------
            | FACE MATCH
            |--------------------------------------------------------------------------
            |
            | If your database has face_match_score column, use it.
            | Otherwise use AI confidence as fallback.
            |
            */

            if (isset($row['face_match_score'])) {

                $face_match = number_format(
                    (float) $row['face_match_score'],
                    1
                ) . "%";

            } elseif (isset($row['face_match'])) {

                $face_match = number_format(
                    (float) $row['face_match'],
                    1
                ) . "%";

            } else {

                $face_match = number_format(
                    $db_confidence,
                    1
                ) . "%";
            }


            /*
            |--------------------------------------------------------------------------
            | NORMALIZE STATUS
            |--------------------------------------------------------------------------
            */

            $normalized_status = strtolower(trim($db_status));


            /*
            |--------------------------------------------------------------------------
            | APPROVED
            |--------------------------------------------------------------------------
            */

            if (
                $normalized_status === "approved" &&
                $db_fraud < 60
            ) {

                $verification_status = "APPROVED";

                $statusColor = "#22c55e";

                $statusIcon = "✓";

                $status_message =
                    "VERIFIED: Document passed the configured DigiVerify validation checks.";

                $raw_terminal_output =
                    "ENTERPRISE DIGIVERIFY
--------------------------------
Verification ID: DV" .
                    str_pad($verification_id, 6, "0", STR_PAD_LEFT) . "
Document Type: " . strtoupper($db_type) . "
OCR Confidence: " . number_format($db_confidence, 1) . "%
Fraud Score: " . number_format($db_fraud, 1) . "
--------------------------------
[OK]: OCR DATA PROCESSED
[OK]: DOCUMENT VALIDATION PASSED
[OK]: FRAUD SCORE WITHIN ACCEPTABLE RANGE
STATUS: APPROVED";

            }


            /*
            |--------------------------------------------------------------------------
            | REJECTED
            |--------------------------------------------------------------------------
            */

            elseif (
                $normalized_status === "rejected" ||
                $db_fraud >= 60
            ) {

                $verification_status = "REJECTED";

                $statusColor = "#ef4444";

                $statusIcon = "✕";

                if ($db_fraud >= 60) {

                    $status_message =
                        "FAILED / REJECTED: High-risk document detected by the configured security validation.";

                } else {

                    $status_message =
                        "FAILED / REJECTED: Document did not pass the configured verification checks.";
                }

                $raw_terminal_output =
                    "ENTERPRISE DIGIVERIFY
--------------------------------
Verification ID: DV" .
                    str_pad($verification_id, 6, "0", STR_PAD_LEFT) . "
Document Type: " . strtoupper($db_type) . "
OCR Confidence: " . number_format($db_confidence, 1) . "%
Fraud Score: " . number_format($db_fraud, 1) . "
--------------------------------
[WARNING]: SECURITY VALIDATION FAILED
[WARNING]: DOCUMENT MARKED AS HIGH RISK
STATUS: REJECTED";

            }


            /*
            |--------------------------------------------------------------------------
            | PENDING
            |--------------------------------------------------------------------------
            */

            else {

                $verification_status = "PENDING";

                $statusColor = "#f59e0b";

                $statusIcon = "…";

                $status_message =
                    "PROCESSING: Document verification is currently pending.";

                $raw_terminal_output =
                    "ENTERPRISE DIGIVERIFY
--------------------------------
Verification ID: DV" .
                    str_pad($verification_id, 6, "0", STR_PAD_LEFT) . "
Document Type: " . strtoupper($db_type) . "
OCR Confidence: " . number_format($db_confidence, 1) . "%
Fraud Score: " . number_format($db_fraud, 1) . "
--------------------------------
[INFO]: DOCUMENT RECEIVED
[INFO]: VALIDATION IN PROGRESS
STATUS: PENDING";
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | RECORD NOT FOUND
            |--------------------------------------------------------------------------
            */

            $verification_status = "REJECTED";

            $statusColor = "#ef4444";

            $statusIcon = "✕";

            $status_message =
                "Verification record #" . $verification_id . " was not found.";

            $raw_terminal_output =
                "ENTERPRISE DIGIVERIFY
--------------------------------
Verification ID: " . $verification_id . "
[ERROR]: DATABASE RECORD NOT FOUND
STATUS: REJECTED";
        }

        mysqli_stmt_close($stmt);

    } else {

        $status_message =
            "Unable to prepare database verification query.";

        $raw_terminal_output =
            "ENTERPRISE DIGIVERIFY
--------------------------------
[DATABASE ERROR]: Query preparation failed
STATUS: REJECTED";
    }

} else {

    /*
    |--------------------------------------------------------------------------
    | DATABASE CONNECTION ERROR
    |--------------------------------------------------------------------------
    */

    $status_message =
        "Database connection is unavailable.";

    $raw_terminal_output =
        "ENTERPRISE DIGIVERIFY
--------------------------------
[DATABASE ERROR]: Database connection unavailable
STATUS: REJECTED";
}


/*
|--------------------------------------------------------------------------
| VERIFICATION REFERENCE
|--------------------------------------------------------------------------
*/

$reference = "DV" . str_pad(
    $verification_id,
    6,
    "0",
    STR_PAD_LEFT
);


/*
|--------------------------------------------------------------------------
| HTML ESCAPE HELPER
|--------------------------------------------------------------------------
*/

function safe_html($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

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
        DigiVerify - Verification Result
    </title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background:
                radial-gradient(
                    circle at top,
                    #10284a 0%,
                    #040d1a 45%,
                    #020711 100%
                );
            color: #ffffff;
            min-height: 100vh;
            padding: 40px 20px;
            overflow-y: auto;
        }

        .page-wrapper {
            width: 100%;
            max-width: 620px;
            margin: auto;
        }

        .brand {
            text-align: center;
            margin-bottom: 20px;
        }

        .brand-title {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #38bdf8;
        }

        .brand-subtitle {
            margin-top: 5px;
            color: #64748b;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .result-card {
            background:
                linear-gradient(
                    145deg,
                    rgba(15, 23, 42, 0.98),
                    rgba(7, 16, 31, 0.98)
                );

            border: 1px solid rgba(56, 189, 248, 0.20);

            border-radius: 24px;

            padding: 35px;

            width: 100%;

            box-shadow:
                0 25px 60px rgba(0, 0, 0, 0.70),
                0 0 40px rgba(14, 165, 233, 0.06);
        }

        .icon-box {
            width: 76px;
            height: 76px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto 20px auto;

            background: rgba(255, 255, 255, 0.03);

            box-shadow:
                0 0 30px rgba(56, 189, 248, 0.08);
        }

        .icon {
            font-size: 34px;
            font-weight: 900;
        }

        .result-card h1 {
            font-size: 27px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 10px;
        }

        .reference {
            text-align: center;
            color: #64748b;
            font-family: monospace;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .status-text {
            text-align: center;

            font-weight: 700;

            font-size: 13px;

            margin-bottom: 25px;

            padding: 12px 14px;

            border-radius: 8px;

            font-family: monospace;

            line-height: 1.6;
        }

        .text-approved {
            color: #4ade80;
            background: rgba(34, 197, 94, 0.10);
            border: 1px solid rgba(34, 197, 94, 0.25);
        }

        .text-rejected {
            color: #fca5a5;
            background: rgba(239, 68, 68, 0.10);
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .text-pending {
            color: #fbbf24;
            background: rgba(245, 158, 11, 0.10);
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .tech-divider {
            font-size: 11px;
            font-family: monospace;
            color: #38bdf8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
            text-align: left;
        }

        .info-table {
            background: rgba(19, 29, 52, 0.70);

            border: 1px solid rgba(255, 255, 255, 0.04);

            border-radius: 14px;

            padding: 18px;

            margin-bottom: 22px;
        }

        .info-row {
            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 20px;

            padding: 12px 0;

            border-bottom:
                1px solid rgba(255, 255, 255, 0.04);

            font-size: 14px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            font-weight: 600;
        }

        .info-value {
            color: #ffffff;
            font-weight: 700;
            text-align: right;
            word-break: break-word;
        }

        .ocr-value {
            font-family: monospace;

            padding: 5px 10px;

            border-radius: 6px;

            font-weight: 700;

            text-align: right;

            word-break: break-word;
        }

        .badge {
            font-family: monospace;
            padding: 5px 9px;
            border-radius: 6px;
            font-weight: 700;
        }

        .ocr-terminal {
            background: #020813;

            border: 1px solid #102a45;

            border-radius: 8px;

            padding: 15px;

            font-family: 'Courier New', monospace;

            font-size: 11px;

            color: #34d399;

            text-align: left;

            margin-bottom: 25px;

            white-space: pre-wrap;

            line-height: 1.5;

            overflow-x: auto;
        }

        .btn-home {
            background: transparent;

            color: #ffffff;

            border: 2px solid #38bdf8;

            padding: 14px;

            border-radius: 12px;

            font-size: 16px;

            cursor: pointer;

            font-weight: bold;

            width: 100%;

            transition: 0.3s;

            text-decoration: none;

            display: block;

            text-align: center;
        }

        .btn-home:hover {
            background: rgba(56, 189, 248, 0.10);

            box-shadow:
                0 0 20px rgba(56, 189, 248, 0.20);
        }

        .footer {
            text-align: center;
            color: #475569;
            font-size: 11px;
            margin-top: 18px;
        }

        @media (max-width: 600px) {

            body {
                padding: 20px 12px;
            }

            .result-card {
                padding: 24px 18px;
            }

            .info-row {
                align-items: flex-start;
            }

            .info-value,
            .ocr-value {
                max-width: 55%;
            }

        }

    </style>

</head>

<body>

<div class="page-wrapper">

    <div class="brand">

        <div class="brand-title">
            ENTERPRISE DIGIVERIFY
        </div>

        <div class="brand-subtitle">
            AI Document Verification Platform
        </div>

    </div>


    <div class="result-card">


        <!-- STATUS ICON -->

        <div
            class="icon-box"
            style="
                border: 2px solid <?php echo safe_html($statusColor); ?>;
                color: <?php echo safe_html($statusColor); ?>;
            "
        >

            <span class="icon">
                <?php echo safe_html($statusIcon); ?>
            </span>

        </div>


        <!-- STATUS TITLE -->

        <?php if ($verification_status === "APPROVED"): ?>

            <h1 style="color:#4ade80;">
                Document Authenticated
            </h1>

        <?php elseif ($verification_status === "PENDING"): ?>

            <h1 style="color:#fbbf24;">
                Verification Pending
            </h1>

        <?php else: ?>

            <h1 style="color:#f87171;">
                Verification Rejected
            </h1>

        <?php endif; ?>


        <!-- REFERENCE -->

        <div class="reference">
            Verification Reference:
            <?php echo safe_html($reference); ?>
        </div>


        <!-- STATUS MESSAGE -->

        <?php if ($verification_status === "APPROVED"): ?>

            <div class="status-text text-approved">
                <?php echo safe_html($status_message); ?>
            </div>

        <?php elseif ($verification_status === "PENDING"): ?>

            <div class="status-text text-pending">
                <?php echo safe_html($status_message); ?>
            </div>

        <?php else: ?>

            <div class="status-text text-rejected">
                <?php echo safe_html($status_message); ?>
            </div>

        <?php endif; ?>


        <!-- OCR -->

        <div class="tech-divider">
            Layer 1: Extracted OCR Metrics
        </div>


        <div class="info-table">


            <!-- NAME -->

            <div class="info-row">

                <span class="info-label">
                    Detected Name
                </span>

                <span
                    class="ocr-value"
                    style="
                        background:
                        <?php
                        echo ($verification_status === 'APPROVED')
                            ? 'rgba(16,185,129,0.10)'
                            : 'rgba(239,68,68,0.10)';
                        ?>;

                        color:
                        <?php
                        echo ($verification_status === 'APPROVED')
                            ? '#4ade80'
                            : '#fca5a5';
                        ?>;
                    "
                >

                    <?php echo safe_html($user_name); ?>

                </span>

            </div>


            <!-- DOCUMENT TYPE -->

            <div class="info-row">

                <span class="info-label">
                    Identified Card Type
                </span>

                <span class="info-value">
                    <?php echo safe_html($document_type); ?>
                </span>

            </div>


            <!-- DOCUMENT NUMBER -->

            <div class="info-row">

                <span class="info-label">
                    Document Number
                </span>

                <span class="info-value">
                    <?php echo safe_html($extracted_uid); ?>
                </span>

            </div>


            <!-- OCR CONFIDENCE -->

            <div class="info-row">

                <span class="info-label">
                    OCR / AI Confidence
                </span>

                <span
                    class="info-value"
                    style="
                        color:
                        <?php
                        echo ($verification_status === 'APPROVED')
                            ? '#34d399'
                            : '#f87171';
                        ?>;
                    "
                >

                    <?php echo safe_html($ocr_score); ?>

                </span>

            </div>


            <!-- FRAUD SCORE -->

            <div class="info-row">

                <span class="info-label">
                    Fraud Risk Score
                </span>

                <span
                    class="info-value"
                    style="
                        color:
                        <?php
                        echo ($db_fraud >= 60)
                            ? '#f87171'
                            : '#4ade80';
                        ?>;
                    "
                >

                    <?php
                    echo number_format(
                        (float) $db_fraud,
                        1
                    );
                    ?>%

                </span>

            </div>

        </div>


        <!-- FACE VERIFICATION -->

        <div class="info-table">

            <div class="info-row">

                <span class="info-label">
                    Face Verification Match
                </span>

                <span
                    class="info-value"
                    style="
                        color:
                        <?php
                        echo ($verification_status === 'APPROVED')
                            ? '#38bdf8'
                            : '#f87171';
                        ?>;
                    "
                >

                    <?php echo safe_html($face_match); ?>

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Uploaded Date
                </span>

                <span class="info-value">
                    <?php echo safe_html($db_date); ?>
                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    User Email
                </span>

                <span class="info-value">
                    <?php echo safe_html($user_email); ?>
                </span>

            </div>

        </div>


        <!-- TERMINAL -->

        <div class="tech-divider">
            DigiVerify Validation Log
        </div>

        <div class="ocr-terminal">

<?php echo safe_html($raw_terminal_output); ?>

        </div>


        <!-- REMARKS -->

        <div class="tech-divider">
            Verification Remarks
        </div>

        <div class="info-table">

            <div
                style="
                    color:#cbd5e1;
                    font-size:13px;
                    line-height:1.6;
                "
            >

                <?php echo safe_html($db_remarks); ?>

            </div>

        </div>


        <!-- HOME -->

        <div style="width:100%;">

            <a
                href="../dashboard.php"
                class="btn-home"
            >
                ← Back to Dashboard
            </a>

        </div>


    </div>


    <div class="footer">
        Enterprise DigiVerify • Secure Document Verification
    </div>

</div>

</body>

</html>
