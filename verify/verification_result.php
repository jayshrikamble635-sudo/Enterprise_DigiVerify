<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

mysqli_report(MYSQLI_REPORT_OFF);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =========================================================
   DATABASE CONFIG
   ========================================================= */

$configFile = dirname(__DIR__) . "/database/config.php";

if (!file_exists($configFile)) {
    die("ERROR: database/config.php not found.");
}

require_once $configFile;


/* =========================================================
   DATABASE CONNECTION CHECK
   ========================================================= */

if (!isset($conn) || !($conn instanceof mysqli)) {
    die("ERROR: Database connection \$conn not available.");
}

if ($conn->connect_error) {
    die(
        "ERROR: Database connection failed: " .
        htmlspecialchars($conn->connect_error)
    );
}


/* =========================================================
   GET VERIFICATION ID
   ========================================================= */

$verification_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($verification_id <= 0) {
    die("ERROR: Invalid verification ID.");
}


/* =========================================================
   DEFAULT VALUES
   ========================================================= */

$status = "PENDING";

$fraud_score = 0;

$ai_confidence = 0;

$document_type = "UNKNOWN DOCUMENT";

$document_number = "Not Extracted";

$user_name = "Unknown User";

$user_email = "Not Available";

$remarks = "No verification remarks available.";

$uploaded_at = "Not Available";

$qr_status = "Not Checked";

$face_match = 0;


/* =========================================================
   FETCH DOCUMENT
   ========================================================= */

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

if (!$stmt) {
    die(
        "ERROR: Database query could not be prepared: " .
        htmlspecialchars(mysqli_error($conn))
    );
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $verification_id
);

if (!mysqli_stmt_execute($stmt)) {
    die(
        "ERROR: Database query failed: " .
        htmlspecialchars(mysqli_stmt_error($stmt))
    );
}


/* =========================================================
   MYSQLND-SAFE RESULT FETCH
   ========================================================= */

$result = mysqli_stmt_get_result($stmt);

if (!$result) {

    /*
     * Fallback for servers where mysqlnd is unavailable.
     */

    $meta = mysqli_stmt_result_metadata($stmt);

    if (!$meta) {
        die("ERROR: Unable to read database result.");
    }

    $fields = [];
    $row = [];

    while ($field = mysqli_fetch_field($meta)) {
        $fields[] = $field->name;
        $row[$field->name] = null;
    }

    $bind = [];

    foreach ($fields as $fieldName) {
        $bind[] = &$row[$fieldName];
    }

    mysqli_stmt_bind_result($stmt, ...$bind);

    if (!mysqli_stmt_fetch($stmt)) {
        die(
            "Verification record #" .
            $verification_id .
            " was not found."
        );
    }

    $data = $row;

} else {

    if (mysqli_num_rows($result) <= 0) {
        die(
            "Verification record #" .
            $verification_id .
            " was not found in database."
        );
    }

    $data = mysqli_fetch_assoc($result);
}

mysqli_stmt_close($stmt);


/* =========================================================
   READ DATABASE VALUES
   ========================================================= */

$status = strtoupper(
    trim(
        $data['verification_status'] ??
        $data['status'] ??
        'PENDING'
    )
);

$fraud_score = isset($data['fraud_score'])
    ? (float)$data['fraud_score']
    : 0;

$ai_confidence = isset($data['ai_confidence'])
    ? (float)$data['ai_confidence']
    : 0;

$document_type = !empty($data['document_type'])
    ? $data['document_type']
    : "AADHAAR";

$document_number = !empty($data['extracted_document_number'])
    ? $data['extracted_document_number']
    : "Not Extracted";

$user_name = !empty($data['user_fullname'])
    ? $data['user_fullname']
    : (
        !empty($data['fullname'])
            ? $data['fullname']
            : "Unknown User"
    );

$user_email = !empty($data['user_email_address'])
    ? $data['user_email_address']
    : (
        !empty($data['email'])
            ? $data['email']
            : "Not Available"
    );

$remarks = !empty($data['remarks'])
    ? $data['remarks']
    : "No verification remarks available.";

$uploaded_at = !empty($data['uploaded_at'])
    ? $data['uploaded_at']
    : "Not Available";


/* =========================================================
   FACE MATCH
   ========================================================= */

if (isset($data['face_match_score'])) {

    $face_match = (float)$data['face_match_score'];

} elseif (isset($data['face_match'])) {

    $face_match = (float)$data['face_match'];

}


/* =========================================================
   QR STATUS
   ========================================================= */

if (isset($data['qr_verified'])) {

    $qr_status = ((int)$data['qr_verified'] === 1)
        ? "VERIFIED"
        : "NOT VERIFIED";

} elseif (isset($data['qr_status'])) {

    $qr_status = strtoupper(
        trim($data['qr_status'])
    );

}


/* =========================================================
   NORMALIZE STATUS
   ========================================================= */

if (
    $status === "VERIFIED" ||
    $status === "AUTHENTICATED"
) {
    $status = "APPROVED";
}


/* =========================================================
   FINAL STATUS
   ========================================================= */

if ($status === "APPROVED") {

    $statusColor = "#22c55e";

    $statusIcon = "✓";

    $statusTitle = "Document Authenticated";

    $statusMessage =
        "Document passed the verification checks recorded by DigiVerify.";

    $statusClass = "approved";

} elseif ($status === "REJECTED") {

    $statusColor = "#ef4444";

    $statusIcon = "✕";

    $statusTitle = "Verification Rejected";

    $statusMessage =
        "Document failed the verification checks recorded by DigiVerify.";

    $statusClass = "rejected";

} else {

    $status = "PENDING";

    $statusColor = "#f59e0b";

    $statusIcon = "…";

    $statusTitle = "Verification Pending";

    $statusMessage =
        "Document verification is still pending.";

    $statusClass = "pending";
}


/* =========================================================
   REFERENCE
   ========================================================= */

$reference =
    "DV" .
    str_pad(
        $verification_id,
        6,
        "0",
        STR_PAD_LEFT
    );


/* =========================================================
   SAFE HTML
   ========================================================= */

function h($value)
{
    return htmlspecialchars(
        (string)$value,
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

    min-height: 100vh;

    padding: 35px 15px;

    font-family:
        "Segoe UI",
        Arial,
        sans-serif;

    color: white;

    background:
        radial-gradient(
            circle at top,
            #12325a 0%,
            #071426 40%,
            #020712 100%
        );
}

.container {

    width: 100%;

    max-width: 620px;

    margin: auto;
}

.header {

    text-align: center;

    margin-bottom: 22px;
}

.logo {

    font-size: 25px;

    font-weight: 900;

    letter-spacing: 1.5px;

    color: #38bdf8;
}

.subtitle {

    color: #64748b;

    margin-top: 5px;

    font-size: 11px;

    letter-spacing: 2px;

    text-transform: uppercase;
}

.card {

    background:
        linear-gradient(
            145deg,
            #0f172a,
            #07111f
        );

    border:

        1px solid
        rgba(56,189,248,.20);

    border-radius: 24px;

    padding: 30px;

    box-shadow:
        0 30px 70px
        rgba(0,0,0,.70);
}

.status-icon {

    width: 78px;

    height: 78px;

    margin: auto;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 36px;

    font-weight: 900;

    border:
        2px solid
        <?php echo h($statusColor); ?>;

    color:
        <?php echo h($statusColor); ?>;

    background:
        rgba(255,255,255,.03);

    box-shadow:
        0 0 35px
        <?php echo h($statusColor); ?>33;
}

h1 {

    text-align: center;

    margin-top: 18px;

    font-size: 27px;

    font-weight: 800;

    color:
        <?php echo h($statusColor); ?>;
}

.reference {

    text-align: center;

    margin-top: 8px;

    margin-bottom: 20px;

    color: #64748b;

    font-family: monospace;

    font-size: 12px;
}

.status {

    padding: 13px;

    border-radius: 10px;

    text-align: center;

    margin-bottom: 25px;

    font-size: 13px;

    font-weight: 700;

    line-height: 1.5;

    font-family: monospace;
}

.status.approved {

    color: #4ade80;

    background:
        rgba(34,197,94,.10);

    border:
        1px solid
        rgba(34,197,94,.25);
}

.status.rejected {

    color: #fca5a5;

    background:
        rgba(239,68,68,.10);

    border:
        1px solid
        rgba(239,68,68,.25);
}

.status.pending {

    color: #fbbf24;

    background:
        rgba(245,158,11,.10);

    border:
        1px solid
        rgba(245,158,11,.25);
}

.section-title {

    color: #38bdf8;

    font-family: monospace;

    font-size: 11px;

    letter-spacing: 2px;

    text-transform: uppercase;

    margin-bottom: 10px;
}

.table {

    background:
        rgba(15,23,42,.80);

    border:
        1px solid
        rgba(255,255,255,.05);

    border-radius: 14px;

    padding: 15px;

    margin-bottom: 22px;
}

.row {

    display: flex;

    justify-content:
        space-between;

    align-items: center;

    gap: 15px;

    padding: 13px 0;

    border-bottom:
        1px solid
        rgba(255,255,255,.05);
}

.row:last-child {
    border-bottom: none;
}

.label {

    color: #64748b;

    font-size: 13px;

    font-weight: 600;
}

.value {

    text-align: right;

    color: #f8fafc;

    font-size: 13px;

    font-weight: 700;

    word-break: break-word;
}

.green {
    color: #4ade80 !important;
}

.red {
    color: #f87171 !important;
}

.yellow {
    color: #fbbf24 !important;
}

.blue {
    color: #38bdf8 !important;
}

.terminal {

    background: #020812;

    border:
        1px solid
        #12304d;

    border-radius: 10px;

    padding: 16px;

    margin-bottom: 25px;

    font-family:
        "Courier New",
        monospace;

    font-size: 11px;

    line-height: 1.6;

    color: #34d399;

    white-space: pre-wrap;

    word-break: break-word;
}

.back {

    display: block;

    width: 100%;

    text-align: center;

    text-decoration: none;

    padding: 14px;

    border-radius: 12px;

    border:
        2px solid
        #38bdf8;

    color: white;

    font-weight: 800;

    transition: .25s;
}

.back:hover {

    background:
        rgba(56,189,248,.10);

    box-shadow:
        0 0 25px
        rgba(56,189,248,.20);
}

.footer {

    text-align: center;

    margin-top: 18px;

    color: #475569;

    font-size: 11px;
}

@media(max-width:600px) {

    .card {
        padding: 22px 17px;
    }

    .row {
        align-items: flex-start;
    }

    .value {
        max-width: 55%;
    }

}

</style>

</head>


<body>


<div class="container">


    <div class="header">

        <div class="logo">
            ENTERPRISE DIGIVERIFY
        </div>

        <div class="subtitle">
            AI Document Verification Platform
        </div>

    </div>


    <div class="card">


        <!-- STATUS -->

        <div class="status-icon">
            <?php echo h($statusIcon); ?>
        </div>


        <h1>
            <?php echo h($statusTitle); ?>
        </h1>


        <div class="reference">

            Verification Reference:
            <?php echo h($reference); ?>

        </div>


        <div class="status <?php echo h($statusClass); ?>">

            <?php echo h($statusMessage); ?>

        </div>


        <!-- DOCUMENT INFORMATION -->

        <div class="section-title">
            Document Information
        </div>


        <div class="table">


            <div class="row">

                <span class="label">
                    Verification ID
                </span>

                <span class="value blue">
                    #<?php echo h($verification_id); ?>
                </span>

            </div>


            <div class="row">

                <span class="label">
                    Detected Name
                </span>

                <span class="value">
                    <?php echo h($user_name); ?>
                </span>

            </div>


            <div class="row">

                <span class="label">
                    Document Type
                </span>

                <span class="value">
                    <?php echo h(strtoupper($document_type)); ?>
                </span>

            </div>


            <div class="row">

                <span class="label">
                    Document Number
                </span>

                <span class="value">
                    <?php echo h($document_number); ?>
                </span>

            </div>


            <div class="row">

                <span class="label">
                    Uploaded At
                </span>

                <span class="value">
                    <?php echo h($uploaded_at); ?>
                </span>

            </div>


        </div>


        <!-- AI ANALYSIS -->

        <div class="section-title">
            AI Verification Analysis
        </div>


        <div class="table">


            <div class="row">

                <span class="label">
                    Verification Status
                </span>

                <span
                    class="value
                    <?php

                    echo $status === "APPROVED"
                        ? "green"
                        : (
                            $status === "REJECTED"
                            ? "red"
                            : "yellow"
                        );

                    ?>"
                >

                    <?php echo h($status); ?>

                </span>

            </div>


            <div class="row">

                <span class="label">
                    AI Confidence
                </span>

                <span class="value blue">

                    <?php
                    echo number_format(
                        $ai_confidence,
                        1
                    );
                    ?>%

                </span>

            </div>


            <div class="row">

                <span class="label">
                    Fraud Risk Score
                </span>

                <span
                    class="value
                    <?php
                    echo $fraud_score >= 60
                        ? "red"
                        : "green";
                    ?>"
                >

                    <?php
                    echo number_format(
                        $fraud_score,
                        1
                    );
                    ?>%

                </span>

            </div>


            <div class="row">

                <span class="label">
                    QR Verification
                </span>

                <span class="value blue">

                    <?php echo h($qr_status); ?>

                </span>

            </div>


            <div class="row">

                <span class="label">
                    Face Match
                </span>

                <span class="value">

                    <?php
                    echo number_format(
                        $face_match,
                        1
                    );
                    ?>%

                </span>

            </div>


        </div>


        <!-- USER -->

        <div class="section-title">
            Account Information
        </div>


        <div class="table">

            <div class="row">

                <span class="label">
                    User
                </span>

                <span class="value">
                    <?php echo h($user_name); ?>
                </span>

            </div>


            <div class="row">

                <span class="label">
                    Email
                </span>

                <span class="value">
                    <?php echo h($user_email); ?>
                </span>

            </div>

        </div>


        <!-- TERMINAL -->

        <div class="section-title">
            DigiVerify Security Log
        </div>


        <div class="terminal">

<?php

echo "DIGIVERIFY SECURITY NODE\n";
echo "--------------------------------\n";
echo "Verification ID : " . $verification_id . "\n";
echo "Reference       : " . $reference . "\n";
echo "Document Type   : " . strtoupper($document_type) . "\n";
echo "AI Confidence   : " . number_format($ai_confidence, 1) . "%\n";
echo "Fraud Score     : " . number_format($fraud_score, 1) . "%\n";
echo "QR Status       : " . $qr_status . "\n";
echo "--------------------------------\n";
echo "FINAL STATUS    : " . $status . "\n";

if ($status === "APPROVED") {

    echo "[OK] Document verification completed.\n";

} elseif ($status === "REJECTED") {

    echo "[ALERT] Document rejected by verification engine.\n";

} else {

    echo "[INFO] Verification is still pending.\n";
}

?>

        </div>


        <!-- REMARKS -->

        <div class="section-title">
            Verification Remarks
        </div>


        <div class="table">

            <div
                style="
                    color:#cbd5e1;
                    font-size:13px;
                    line-height:1.7;
                "
            >

                <?php echo h($remarks); ?>

            </div>

        </div>


        <!-- BACK -->

        <a
            href="../dashboard.php"
            class="back"
        >
            ← Back to Dashboard
        </a>


    </div>


    <div class="footer">

        Enterprise DigiVerify
        •
        Secure Document Verification

    </div>


</div>


</body>

</html>
