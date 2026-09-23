<?php

$status = isset($_GET['status'])
    ? strtoupper(trim($_GET['status']))
    : 'REJECTED';

$score = isset($_GET['score'])
    ? intval($_GET['score'])
    : 0;

$name = isset($_GET['name']) && trim($_GET['name']) !== ''
    ? trim($_GET['name'])
    : 'NOT DETECTED';

$aadhaar = isset($_GET['aadhaar']) && trim($_GET['aadhaar']) !== ''
    ? trim($_GET['aadhaar'])
    : 'XXXX XXXX XXXX';

$reasonString = isset($_GET['reason'])
    ? $_GET['reason']
    : '';

$warningString = isset($_GET['warning'])
    ? $_GET['warning']
    : '';

$reasons = $reasonString !== ''
    ? explode('|', $reasonString)
    : [];

$warnings = $warningString !== ''
    ? explode('|', $warningString)
    : [];

/*
|--------------------------------------------------------------------------
| STATUS HANDLING - ONLY APPROVED / REJECTED
|--------------------------------------------------------------------------
*/

if ($status === 'APPROVED') {

    $title = 'APPROVED';
    $subtitle = 'AI SCREENING PASSED';
    $class = 'approved';
    $icon = '✓';

} else {

    $status = 'REJECTED';
    $title = 'REJECTED';
    $subtitle = 'AI SCREENING FAILED';
    $class = 'rejected';
    $icon = '✕';
}


/*
|--------------------------------------------------------------------------
| SAFE OUTPUT FUNCTION
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Enterprise DigiVerify - Verification Result
</title>

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    min-height: 100vh;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background:

        radial-gradient(
            circle at top left,
            #123a68 0%,
            transparent 35%
        ),

        radial-gradient(
            circle at bottom right,
            #062d48 0%,
            transparent 35%
        ),

        #020b16;

    color: #ffffff;

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 30px;
}


/* MAIN CONTAINER */

.container {

    width: 100%;

    max-width: 850px;
}


/* CARD */

.card {

    background:
        rgba(5, 20, 35, 0.96);

    border:
        1px solid
        rgba(0, 212, 255, 0.35);

    border-radius: 22px;

    padding: 35px;

    box-shadow:

        0 0 35px
        rgba(0, 180, 255, 0.15),

        inset 0 0 25px
        rgba(0, 120, 180, 0.05);
}


/* LOGO */

.logo {

    text-align: center;

    font-size: 28px;

    font-weight: bold;

    color: #00d9ff;

    margin-bottom: 25px;
}


/* RESULT BOX */

.result-box {

    text-align: center;

    padding: 30px 20px;

    border-radius: 18px;

    margin-bottom: 25px;
}


/* APPROVED */

.result-box.approved {

    border:
        2px solid #00ff9d;

    background:
        rgba(0, 255, 157, 0.07);

    box-shadow:
        0 0 30px
        rgba(0, 255, 157, 0.15);
}


/* REVIEW */

.result-box.review {

    border:
        2px solid #ffc107;

    background:
        rgba(255, 193, 7, 0.07);

    box-shadow:
        0 0 30px
        rgba(255, 193, 7, 0.12);
}


/* REJECTED */

.result-box.rejected {

    border:
        2px solid #ff4757;

    background:
        rgba(255, 71, 87, 0.07);

    box-shadow:
        0 0 30px
        rgba(255, 71, 87, 0.15);
}


/* ICON */

.icon {

    width: 85px;

    height: 85px;

    border-radius: 50%;

    margin:
        0 auto 18px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 50px;

    font-weight: bold;
}


/* APPROVED ICON */

.approved .icon {

    color: #00ff9d;

    border:
        3px solid #00ff9d;
}


/* REVIEW ICON */

.review .icon {

    color: #ffc107;

    border:
        3px solid #ffc107;
}


/* REJECTED ICON */

.rejected .icon {

    color: #ff4757;

    border:
        3px solid #ff4757;
}


/* RESULT TITLE */

.result-title {

    font-size: 38px;

    font-weight: bold;

    letter-spacing: 2px;
}


/* TITLE COLORS */

.approved .result-title {

    color: #00ff9d;
}

.review .result-title {

    color: #ffc107;
}

.rejected .result-title {

    color: #ff4757;
}


/* SUBTITLE */

.subtitle {

    margin-top: 8px;

    color: #a8c7d8;

    font-size: 15px;

    letter-spacing: 1px;
}


/* SCORE */

.score {

    margin:
        20px auto 0;

    display: inline-block;

    padding:
        10px 22px;

    border-radius: 30px;

    background:
        rgba(0, 212, 255, 0.1);

    border:
        1px solid
        rgba(0, 212, 255, 0.35);

    color: #00d9ff;

    font-size: 18px;

    font-weight: bold;
}


/* SECTION TITLE */

.section-title {

    color: #00d9ff;

    font-size: 18px;

    font-weight: bold;

    margin:
        25px 0 12px;
}


/* DETAILS GRID */

.details {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 15px;
}


/* DETAIL CARD */

.detail {

    background:
        rgba(255,255,255,0.035);

    border:
        1px solid
        rgba(255,255,255,0.08);

    padding: 18px;

    border-radius: 12px;
}


/* LABEL */

.label {

    color: #7896a8;

    font-size: 12px;

    text-transform: uppercase;

    margin-bottom: 7px;
}


/* VALUE */

.value {

    color: #ffffff;

    font-size: 16px;

    font-weight: bold;

    word-break: break-word;
}


/* LIST */

.list {

    background:
        rgba(255,255,255,0.03);

    border-radius: 12px;

    padding:
        15px 20px;
}


/* LIST ITEM */

.list div {

    padding: 9px 0;

    border-bottom:
        1px solid
        rgba(255,255,255,0.06);

    color: #c7dce7;

    line-height: 1.5;
}

.list div:last-child {

    border-bottom: none;
}


/* POSITIVE */

.good {

    color: #00ff9d !important;
}


/* WARNING */

.bad {

    color: #ff6b78 !important;
}


/* REVIEW */

.review-text {

    color: #ffc107 !important;
}


/* NOTICE */

.notice {

    margin-top: 25px;

    padding: 15px;

    border-radius: 12px;

    background:
        rgba(255,193,7,0.07);

    border:
        1px solid
        rgba(255,193,7,0.25);

    color: #d9c98b;

    font-size: 13px;

    line-height: 1.6;
}


/* BUTTONS */

.buttons {

    display: flex;

    gap: 15px;

    margin-top: 28px;
}


/* BUTTON */

.btn {

    flex: 1;

    text-decoration: none;

    text-align: center;

    padding: 14px;

    border-radius: 10px;

    font-weight: bold;

    transition: 0.2s;
}


/* PRIMARY */

.btn-primary {

    background:
        #00c8ff;

    color:
        #00121c;
}


/* SECONDARY */

.btn-secondary {

    background:
        rgba(255,255,255,0.06);

    color:
        #ffffff;

    border:
        1px solid
        rgba(255,255,255,0.15);
}


/* HOVER */

.btn:hover {

    transform:
        translateY(-2px);

    opacity: 0.92;
}


/* FOOTER */

.footer {

    text-align: center;

    color: #5d7888;

    font-size: 12px;

    margin-top: 25px;
}


/* MOBILE */

@media(max-width:650px) {

    body {

        padding: 15px;
    }

    .card {

        padding: 20px;
    }

    .details {

        grid-template-columns:
            1fr;
    }

    .buttons {

        flex-direction:
            column;
    }

    .result-title {

        font-size: 30px;
    }

}

</style>

</head>


<body>

<div class="container">

<div class="card">


<!-- LOGO -->

<div class="logo">

    🛡 Enterprise DigiVerify

</div>


<!-- RESULT -->

<div class="result-box <?= e($class) ?>">


    <div class="icon">

        <?= e($icon) ?>

    </div>


    <div class="result-title">

        <?= e($title) ?>

    </div>


    <div class="subtitle">

        <?= e($subtitle) ?>

    </div>


    <div class="score">

        AI SCREENING SCORE:
        <?= e($score) ?>/100

    </div>


</div>


<!-- DOCUMENT DETAILS -->

<div class="section-title">

    📄 Document Details

</div>


<div class="details">


    <div class="detail">

        <div class="label">

            Document Holder

        </div>

        <div class="value">

            <?= e($name) ?>

        </div>

    </div>


    <div class="detail">

        <div class="label">

            Aadhaar Number

        </div>

        <div class="value">

            <?= e($aadhaar) ?>

        </div>

    </div>


    <div class="detail">

        <div class="label">

            OCR Engine

        </div>

        <div class="value">

            Tesseract.js

        </div>

    </div>


    <div class="detail">

        <div class="label">

            AI Screening Engine

        </div>

        <div class="value">

            DigiVerify AI

        </div>

    </div>


</div>


<!-- ANALYSIS -->

<div class="section-title">

    ✓ Analysis Details

</div>


<div class="list">

<?php

if (count($reasons) > 0) {

    foreach ($reasons as $reason) {

        if (trim($reason) === '') {
            continue;
        }

        echo
            '<div class="good">✓ '
            . e($reason)
            . '</div>';
    }

} else {

    echo
        '<div>No positive checks detected.</div>';

}

?>

</div>


<!-- WARNINGS -->

<?php if (count($warnings) > 0): ?>

<div class="section-title">

    ⚠ Warnings

</div>


<div class="list">

<?php

foreach ($warnings as $warning) {

    if (trim($warning) === '') {
        continue;
    }

    echo
        '<div class="bad">⚠ '
        . e($warning)
        . '</div>';
}

?>

</div>

<?php endif; ?>


<!-- REVIEW MESSAGE -->

<?php if ($status === 'MANUAL REVIEW'): ?>

<div class="section-title">

    🔎 Review Status

</div>


<div class="list">

    <div class="review-text">

        ⚠ The document contains some recognizable
        identity information, but additional review
        is recommended before acceptance.

    </div>

</div>

<?php endif; ?>


<!-- IMPORTANT NOTICE -->

<div class="notice">

<strong>Important:</strong><br>

This result represents a
<strong>project-level AI document screening</strong>.

It does not constitute official UIDAI authentication
or a legal confirmation that an Aadhaar document is genuine.

</div>


<!-- BUTTONS -->

<div class="buttons">


<a
    href="upload.php"
    class="btn btn-primary"
>
    🔍 Verify Another Document
</a>


<a
    href="../index.php"
    class="btn btn-secondary"
>
    🏠 Home
</a>


</div>


<!-- FOOTER -->

<div class="footer">

    Enterprise DigiVerify
    •
    Digital Document Screening Platform

</div>


</div>

</div>

</body>

</html>
