<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


/* ==============================
   GET RESULT DATA
   ============================== */

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


$reasons = [];

if ($reasonString !== '') {

    $reasons =
        explode(
            '|',
            $reasonString
        );
}


/* ==============================
   STATUS DESIGN
   ============================== */

if ($status === 'APPROVED') {

    $statusTitle = 'APPROVED';

    $statusClass = 'approved';

    $statusIcon = '✓';

} elseif ($status === 'REJECTED') {

    $statusTitle = 'REJECTED';

    $statusClass = 'rejected';

    $statusIcon = '✕';

} else {

    $statusTitle = 'MANUAL REVIEW';

    $statusClass = 'review';

    $statusIcon = '!';
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

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 25px;
}


.container {

    width: 100%;

    max-width: 800px;

    background:
        rgba(7, 18, 34, .97);

    border: 1px solid #1e6784;

    border-radius: 22px;

    padding: 35px;

    box-shadow:
        0 0 50px
        rgba(0, 200, 255, .12);
}


.header {

    text-align: center;
}


.logo {

    color: #42d9ff;

    font-size: 30px;

    font-weight: bold;
}


.subtitle {

    color: #91a8bb;

    margin-top: 8px;
}


/* ==============================
   STATUS CIRCLE
   ============================== */

.status {

    margin: 30px auto;

    width: 210px;

    height: 210px;

    border-radius: 50%;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    border: 5px solid;
}


.status-icon {

    font-size: 65px;

    font-weight: bold;
}


.status-text {

    font-size: 22px;

    font-weight: bold;

    margin-top: 8px;
}


.approved {

    border-color: #00d99b;

    color: #00e5a3;

    box-shadow:
        0 0 35px
        rgba(0, 229, 163, .25);
}


.rejected {

    border-color: #ff4d67;

    color: #ff647a;

    box-shadow:
        0 0 35px
        rgba(255, 77, 103, .25);
}


.review {

    border-color: #ffb52e;

    color: #ffc04a;

    box-shadow:
        0 0 35px
        rgba(255, 181, 46, .25);
}


/* ==============================
   INFORMATION CARD
   ============================== */

.card {

    background: #0a192c;

    border: 1px solid #1b425a;

    border-radius: 15px;

    padding: 22px;

    margin-top: 20px;
}


.card-title {

    color: #42d9ff;

    font-weight: bold;

    margin-bottom: 18px;

    font-size: 18px;
}


.row {

    display: flex;

    justify-content: space-between;

    gap: 20px;

    padding: 12px 0;

    border-bottom: 1px solid #173247;
}


.row:last-child {

    border-bottom: none;
}


.label {

    color: #8fa5b8;
}


.value {

    text-align: right;

    font-weight: bold;
}


/* ==============================
   SCORE
   ============================== */

.score {

    font-size: 30px;

    color: #42d9ff;

    text-align: center;

    margin: 15px 0;
}


/* ==============================
   REASONS
   ============================== */

.reason {

    padding: 10px 0;

    color: #c4d2dd;

    border-bottom:
        1px solid #173247;
}


.reason:last-child {

    border-bottom: none;
}


/* ==============================
   NOTICE
   ============================== */

.notice {

    margin-top: 25px;

    padding: 15px;

    border-radius: 10px;

    background: #101e2e;

    border: 1px solid #31516a;

    color: #9eb2c2;

    font-size: 13px;

    line-height: 1.6;
}


/* ==============================
   BUTTONS
   ============================== */

.buttons {

    display: flex;

    gap: 15px;

    margin-top: 25px;
}


.buttons a {

    flex: 1;

    text-align: center;

    padding: 14px;

    border-radius: 10px;

    text-decoration: none;

    font-weight: bold;
}


.verify {

    background:
        linear-gradient(
            90deg,
            #00a8e8,
            #00d4aa
        );

    color: #001018;
}


.home {

    background: #14283c;

    color: white;

    border: 1px solid #31516a;
}


/* ==============================
   MOBILE
   ============================== */

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

            AI-Assisted Document Verification Result

        </div>

    </div>


    <!-- STATUS -->

    <div
        class="status <?php
        echo htmlspecialchars($statusClass);
        ?>"
    >

        <div class="status-icon">

            <?php
            echo htmlspecialchars($statusIcon);
            ?>

        </div>


        <div class="status-text">

            <?php
            echo htmlspecialchars($statusTitle);
            ?>

        </div>

    </div>


    <!-- DOCUMENT INFORMATION -->

    <div class="card">

        <div class="card-title">

            Document Information

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

                echo htmlspecialchars(
                    $aadhaar
                );

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

                Verification Type

            </div>


            <div class="value">

                AI-Assisted Screening

            </div>

        </div>

    </div>


    <!-- SCORE -->

    <div class="card">

        <div class="card-title">

            AI Screening Score

        </div>


        <div class="score">

            <?php
            echo htmlspecialchars($score);
            ?>

            / 100

        </div>

    </div>


    <!-- ANALYSIS -->

    <div class="card">

        <div class="card-title">

            Verification Analysis

        </div>


        <?php

        if (count($reasons) > 0):

            foreach ($reasons as $reason):

        ?>

            <div class="reason">

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

                No additional analysis
                information available.

            </div>

        <?php endif; ?>

    </div>


    <!-- NOTICE -->

    <div class="notice">

        <strong>Verification Result:</strong>

        <br><br>


        <?php

        if ($status === 'APPROVED'):

        ?>

            This document has passed the configured
            DigiVerify AI-assisted screening rules.

        <?php

        elseif ($status === 'REJECTED'):

        ?>

            This document failed the configured
            DigiVerify screening rules or contained
            suspicious indicators.

        <?php

        else:

        ?>

            The available evidence was not sufficient
            for automatic approval. Manual verification
            is recommended.

        <?php endif; ?>


        <br><br>


        <strong>Important:</strong>

        This is a project-level document screening
        result. It is

        <strong>
            NOT official UIDAI authentication
        </strong>

        and does not confirm government-issued
        authenticity.

    </div>


    <!-- BUTTONS -->

    <div class="buttons">

        <a
            class="verify"
            href="upload.php"
        >

            Verify Another Document

        </a>


        <a
            class="home"
            href="../index.php"
        >

            Home

        </a>

    </div>


</div>


</body>

</html>
