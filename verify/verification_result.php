<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


$status = isset($_GET['status'])
    ? strtoupper(trim($_GET['status']))
    : 'MANUAL REVIEW';

$score = isset($_GET['score'])
    ? intval($_GET['score'])
    : 0;

$name = isset($_GET['name'])
    ? trim($_GET['name'])
    : 'NOT DETECTED';

$aadhaar = isset($_GET['aadhaar'])
    ? trim($_GET['aadhaar'])
    : 'XXXX XXXX XXXX';

$reasonString = isset($_GET['reason'])
    ? $_GET['reason']
    : '';

$warningString = isset($_GET['warning'])
    ? $_GET['warning']
    : '';

$reasons = [];
$warnings = [];

if ($reasonString !== '') {
    $reasons = explode('|', $reasonString);
}

if ($warningString !== '') {
    $warnings = explode('|', $warningString);
}


/*
=========================================================
STATUS
=========================================================
*/

if ($status === 'APPROVED') {

    $title = 'APPROVED';

    $subtitle =
        'AI SCREENING PASSED';

    $class = 'approved';

    $icon = '✓';

} elseif ($status === 'REJECTED') {

    $title = 'REJECTED';

    $subtitle =
        'SCREENING FAILED';

    $class = 'rejected';

    $icon = '✕';

} else {

    $title = 'MANUAL REVIEW';

    $subtitle =
        'ADDITIONAL CHECK REQUIRED';

    $class = 'review';

    $icon = '!';
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
Enterprise DigiVerify - Verification Result
</title>

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    min-height: 100vh;

    font-family: Arial, sans-serif;

    background:
        radial-gradient(
            circle at top left,
            #12345b,
            transparent 40%
        ),
        radial-gradient(
            circle at bottom right,
            #063b45,
            transparent 40%
        ),
        #050b16;

    color: white;

    padding: 25px;
}

.container {

    width: 100%;

    max-width: 850px;

    margin: auto;

    background: rgba(7,18,34,.97);

    border: 1px solid #1e6784;

    border-radius: 22px;

    padding: 35px;

    box-shadow:
        0 0 50px
        rgba(0,200,255,.12);
}

.header {

    text-align: center;
}

.logo {

    color: #42d9ff;

    font-size: 31px;

    font-weight: bold;
}

.subtitle {

    margin-top: 8px;

    color: #91a8bb;
}


/*
STATUS
*/

.status {

    width: 230px;

    height: 230px;

    margin: 30px auto;

    border-radius: 50%;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    border: 5px solid;
}

.status-icon {

    font-size: 70px;

    font-weight: bold;
}

.status-title {

    font-size: 24px;

    font-weight: bold;

    margin-top: 5px;
}

.status-subtitle {

    font-size: 12px;

    margin-top: 8px;

    letter-spacing: 1px;
}

.approved {

    color: #00e5a3;

    border-color: #00d99b;

    box-shadow:
        0 0 40px
        rgba(0,229,163,.25);
}

.rejected {

    color: #ff647a;

    border-color: #ff4d67;

    box-shadow:
        0 0 40px
        rgba(255,77,103,.25);
}

.review {

    color: #ffc04a;

    border-color: #ffb52e;

    box-shadow:
        0 0 40px
        rgba(255,181,46,.25);
}


/*
CARDS
*/

.card {

    background: #0a192c;

    border: 1px solid #1b425a;

    border-radius: 15px;

    padding: 23px;

    margin-top: 20px;
}

.card-title {

    color: #42d9ff;

    font-size: 19px;

    font-weight: bold;

    margin-bottom: 18px;
}

.row {

    display: flex;

    justify-content: space-between;

    padding: 13px 0;

    border-bottom: 1px solid #173247;

    gap: 20px;
}

.row:last-child {

    border-bottom: 0;
}

.label {

    color: #8fa5b8;
}

.value {

    text-align: right;

    font-weight: bold;
}


/*
SCORE
*/

.score-number {

    text-align: center;

    font-size: 42px;

    color: #42d9ff;

    font-weight: bold;
}

.score-label {

    text-align: center;

    color: #8299aa;

    margin-top: 5px;
}


/*
REASONS
*/

.reason {

    padding: 11px 0;

    border-bottom: 1px solid #173247;

    color: #c4d2dd;
}

.reason:last-child {

    border-bottom: 0;
}

.warning {

    padding: 11px 0;

    border-bottom: 1px solid #3b3020;

    color: #ffc04a;
}

.warning:last-child {

    border-bottom: 0;
}


/*
NOTICE
*/

.notice {

    margin-top: 20px;

    padding: 18px;

    background: #101e2e;

    border: 1px solid #31516a;

    border-radius: 12px;

    color: #9eb2c2;

    font-size: 13px;

    line-height: 1.7;
}


/*
BUTTONS
*/

.buttons {

    display: flex;

    gap: 15px;

    margin-top: 25px;
}

.buttons a {

    flex: 1;

    padding: 15px;

    text-align: center;

    border-radius: 10px;

    text-decoration: none;

    font-weight: bold;
}

.verify {

    color: #001018;

    background:
        linear-gradient(
            90deg,
            #00a8e8,
            #00d4aa
        );
}

.home {

    color: white;

    background: #14283c;

    border: 1px solid #31516a;
}


@media(max-width:600px) {

    .container {
        padding: 22px;
    }

    .row {
        flex-direction: column;
        gap: 5px;
    }

    .value {
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


    <!-- HEADER -->

    <div class="header">

        <div class="logo">

            Enterprise DigiVerify

        </div>

        <div class="subtitle">

            AI-Assisted Document Verification System

        </div>

    </div>


    <!-- STATUS -->

    <div
        class="status <?php
        echo htmlspecialchars($class);
        ?>"
    >

        <div class="status-icon">

            <?php
            echo htmlspecialchars($icon);
            ?>

        </div>


        <div class="status-title">

            <?php
            echo htmlspecialchars($title);
            ?>

        </div>


        <div class="status-subtitle">

            <?php
            echo htmlspecialchars($subtitle);
            ?>

        </div>

    </div>


    <!-- DOCUMENT -->

    <div class="card">

        <div class="card-title">

            Document Analysis

        </div>


        <div class="row">

            <div class="label">
                Document Type
            </div>

            <div class="value">
                Aadhaar
            </div>

        </div>


        <div class="row">

            <div class="label">
                Detected Name
            </div>

            <div class="value">

                <?php

                echo htmlspecialchars(
                    $name !== ''
                        ? $name
                        : 'NOT DETECTED'
                );

                ?>

            </div>

        </div>


        <div class="row">

            <div class="label">
                Aadhaar Number
            </div>

            <div class="value">

                <?php
                echo htmlspecialchars($aadhaar);
                ?>

            </div>

        </div>


        <div class="row">

            <div class="label">
                OCR Engine
            </div>

            <div class="value">
                Tesseract.js
            </div>

        </div>


        <div class="row">

            <div class="label">
                Screening Engine
            </div>

            <div class="value">
                DigiVerify AI Rules
            </div>

        </div>

    </div>


    <!-- SCORE -->

    <div class="card">

        <div class="card-title">
            AI Screening Score
        </div>


        <div class="score-number">

            <?php
            echo htmlspecialchars($score);
            ?>/100

        </div>


        <div class="score-label">

            Document Screening Confidence

        </div>

    </div>


    <!-- POSITIVE CHECKS -->

    <div class="card">

        <div class="card-title">

            Checks Passed

        </div>


        <?php

        if (count($reasons) > 0):

            foreach ($reasons as $reason):

        ?>

            <div class="reason">

                ✓

                <?php

                echo htmlspecialchars(
                    trim($reason)
                );

                ?>

            </div>

        <?php

            endforeach;

        else:

        ?>

            <div class="reason">

                No positive checks recorded.

            </div>

        <?php endif; ?>

    </div>


    <!-- WARNINGS -->

    <?php if (count($warnings) > 0): ?>

    <div class="card">

        <div class="card-title">

            Additional Observations

        </div>


        <?php foreach ($warnings as $warning): ?>

            <div class="warning">

                !

                <?php

                echo htmlspecialchars(
                    trim($warning)
                );

                ?>

            </div>

        <?php endforeach; ?>

    </div>

    <?php endif; ?>


    <!-- NOTICE -->

    <div class="notice">

        <strong>DigiVerify Result:</strong>

        <br><br>

        <?php if ($status === 'APPROVED'): ?>

            The document passed the configured
            DigiVerify AI-assisted screening checks.

        <?php elseif ($status === 'REJECTED'): ?>

            The document failed the configured
            screening criteria or contained
            suspicious indicators.

        <?php else: ?>

            The available OCR evidence is insufficient
            for automatic approval. Manual review
            is recommended.

        <?php endif; ?>


        <br><br>

        <strong>Important:</strong>

        This is a project-level AI-assisted
        document screening result. It does not
        constitute official UIDAI authentication
        or government confirmation of authenticity.

    </div>


    <!-- BUTTONS -->

    <div class="buttons">

        <a
            class="verify"
            href="upload.php"
        >

            🔍 Verify Another Document

        </a>


        <a
            class="home"
            href="../index.php"
        >

            🏠 Home

        </a>

    </div>

</div>

</body>

</html>
