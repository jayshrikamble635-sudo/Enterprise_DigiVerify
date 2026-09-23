<?php

/*
=========================================================
ENTERPRISE DIGIVERIFY
AI DOCUMENT VERIFICATION RESULT
ONLY APPROVED / REJECTED
=========================================================
*/


/*
=========================================================
GET RESULT DATA
=========================================================
*/

$status = isset($_GET['status'])
    ? strtoupper(trim($_GET['status']))
    : 'REJECTED';


$score = isset($_GET['score'])
    ? intval($_GET['score'])
    : 0;


$name = isset($_GET['name'])
    ? trim($_GET['name'])
    : '';


$aadhaar = isset($_GET['aadhaar'])
    ? trim($_GET['aadhaar'])
    : '';


$reasonString = isset($_GET['reason'])
    ? trim($_GET['reason'])
    : '';


$warningString = isset($_GET['warning'])
    ? trim($_GET['warning'])
    : '';


/*
=========================================================
DEFAULT VALUES
=========================================================
*/

if ($name === '') {

    $name = 'NOT DETECTED';
}


if ($aadhaar === '') {

    $aadhaar = 'XXXX XXXX XXXX';
}


/*
=========================================================
REASONS
=========================================================
*/

$reasons = [];


if ($reasonString !== '') {

    $reasons = explode(
        '|',
        $reasonString
    );
}


/*
=========================================================
WARNINGS
=========================================================
*/

$warnings = [];


if ($warningString !== '') {

    $warnings = explode(
        '|',
        $warningString
    );
}


/*
=========================================================
REMOVE EMPTY ITEMS
=========================================================
*/

$reasons = array_values(
    array_filter(
        $reasons,
        function ($value) {

            return trim($value) !== '';

        }
    )
);


$warnings = array_values(
    array_filter(
        $warnings,
        function ($value) {

            return trim($value) !== '';

        }
    )
);


/*
=========================================================
SCORE LIMIT
=========================================================
*/

if ($score < 0) {

    $score = 0;
}


if ($score > 100) {

    $score = 100;
}


/*
=========================================================
STATUS
ONLY APPROVED / REJECTED
=========================================================
*/

if ($status === 'APPROVED') {

    $status = 'APPROVED';

    $title = 'APPROVED';

    $subtitle =
        'AI SCREENING PASSED';

    $class = 'approved';

    $icon = '✓';

} else {

    $status = 'REJECTED';

    $title = 'REJECTED';

    $subtitle =
        'AI SCREENING FAILED';

    $class = 'rejected';

    $icon = '✕';
}


/*
=========================================================
SAFE OUTPUT
=========================================================
*/

function e($value)
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

<meta
    name="robots"
    content="noindex,nofollow"
>

<title>
    Enterprise DigiVerify | Verification Result
</title>


<style>

/* =====================================================
   GLOBAL RESET
===================================================== */

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;
}


html {

    scroll-behavior: smooth;
}


body {

    min-height: 100vh;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    color: #ffffff;

    background:

        radial-gradient(
            circle at 10% 0%,
            rgba(0, 229, 255, 0.13),
            transparent 32%
        ),

        radial-gradient(
            circle at 90% 100%,
            rgba(0, 120, 255, 0.14),
            transparent 35%
        ),

        #050b17;

    padding: 28px 15px;
}


/* =====================================================
   MAIN CONTAINER
===================================================== */

.container {

    width: 100%;

    max-width: 1100px;

    margin: 0 auto;
}


/* =====================================================
   HEADER
===================================================== */

.header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 22px 28px;

    margin-bottom: 22px;

    background:

        linear-gradient(
            145deg,
            rgba(13, 31, 57, 0.98),
            rgba(5, 15, 30, 0.98)
        );

    border: 2px solid #16496d;

    border-radius: 18px;

    box-shadow:

        0 0 30px
        rgba(0, 229, 255, 0.10),

        inset 0 0 25px
        rgba(0, 229, 255, 0.03);
}


.logo {

    display: flex;

    align-items: center;

    gap: 14px;
}


.logo-icon {

    width: 52px;

    height: 52px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 13px;

    color: #00131d;

    font-size: 24px;

    font-weight: 1000;

    background:

        linear-gradient(
            135deg,
            #00e5ff,
            #00a8ff
        );

    box-shadow:

        0 0 22px
        rgba(0, 229, 255, 0.40);
}


.logo-text h1 {

    font-size: 23px;

    font-weight: 1000;

    letter-spacing: .4px;
}


.logo-text p {

    margin-top: 5px;

    color: #7edfff;

    font-size: 11px;

    font-weight: 800;

    letter-spacing: 1.2px;
}


.enterprise-badge {

    padding: 10px 15px;

    border: 2px solid #00bcd4;

    border-radius: 9px;

    color: #7eeeff;

    background:
        rgba(0, 188, 212, 0.08);

    font-size: 11px;

    font-weight: 1000;

    letter-spacing: 1px;

    white-space: nowrap;
}


/* =====================================================
   RESULT CARD
===================================================== */

.result-card {

    position: relative;

    overflow: hidden;

    padding: 38px;

    background:

        linear-gradient(
            145deg,
            rgba(10, 25, 47, 0.99),
            rgba(4, 13, 27, 0.99)
        );

    border: 2px solid #174b70;

    border-radius: 22px;

    box-shadow:

        0 22px 65px
        rgba(0, 0, 0, 0.58),

        inset 0 0 40px
        rgba(0, 229, 255, 0.025);
}


.result-card::before {

    content: "";

    position: absolute;

    top: 0;

    left: 0;

    right: 0;

    height: 5px;

    background:

        linear-gradient(
            90deg,
            #00e5ff,
            #008cff,
            #00e5ff
        );
}


/* =====================================================
   STATUS
===================================================== */

.status-area {

    text-align: center;

    padding: 10px 0 35px;
}


.status-icon {

    width: 108px;

    height: 108px;

    margin: 0 auto 21px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    font-size: 58px;

    font-weight: 1000;
}


.status-title {

    font-size: 46px;

    line-height: 1;

    font-weight: 1000;

    letter-spacing: 2px;

    margin-bottom: 11px;
}


.status-subtitle {

    font-size: 14px;

    font-weight: 1000;

    letter-spacing: 2px;
}


/* =====================================================
   APPROVED
===================================================== */

.approved .status-icon {

    color: #00ffae;

    border: 5px solid #00ffae;

    background:
        rgba(0, 255, 174, 0.08);

    box-shadow:

        0 0 38px
        rgba(0, 255, 174, 0.30),

        inset 0 0 20px
        rgba(0, 255, 174, 0.08);
}


.approved .status-title {

    color: #00ffae;

    text-shadow:

        0 0 20px
        rgba(0, 255, 174, 0.35);
}


.approved .status-subtitle {

    color: #79ffd1;
}


/* =====================================================
   REJECTED
===================================================== */

.rejected .status-icon {

    color: #ff4f67;

    border: 5px solid #ff4f67;

    background:
        rgba(255, 79, 103, 0.08);

    box-shadow:

        0 0 38px
        rgba(255, 79, 103, 0.28),

        inset 0 0 20px
        rgba(255, 79, 103, 0.08);
}


.rejected .status-title {

    color: #ff4f67;

    text-shadow:

        0 0 20px
        rgba(255, 79, 103, 0.35);
}


.rejected .status-subtitle {

    color: #ff9eaa;
}


/* =====================================================
   SCORE
===================================================== */

.score-box {

    max-width: 520px;

    margin: 0 auto 32px;

    padding: 23px;

    text-align: center;

    background:
        rgba(0, 20, 40, 0.72);

    border: 2px solid #205879;

    border-radius: 16px;

    box-shadow:

        inset 0 0 20px
        rgba(0, 229, 255, 0.025);
}


.score-label {

    margin-bottom: 8px;

    color: #7ba7c4;

    font-size: 12px;

    font-weight: 1000;

    letter-spacing: 2px;
}


.score-value {

    color: #00e5ff;

    font-size: 43px;

    font-weight: 1000;

    text-shadow:

        0 0 18px
        rgba(0, 229, 255, 0.35);
}


.score-bar {

    width: 100%;

    height: 13px;

    margin-top: 15px;

    overflow: hidden;

    background: #10263b;

    border: 1px solid #24536e;

    border-radius: 20px;
}


.score-fill {

    width: <?php echo $score; ?>%;

    height: 100%;

    border-radius: 20px;

    background:

        linear-gradient(
            90deg,
            #00a8ff,
            #00e5ff
        );

    box-shadow:

        0 0 16px
        rgba(0, 229, 255, 0.45);
}


/* =====================================================
   SECTIONS
===================================================== */

.section {

    margin-top: 30px;
}


.section-title {

    display: flex;

    align-items: center;

    gap: 10px;

    margin-bottom: 15px;

    padding-left: 14px;

    border-left: 5px solid #00e5ff;

    color: #dffaff;

    font-size: 17px;

    font-weight: 1000;

    letter-spacing: .4px;
}


/* =====================================================
   INFORMATION GRID
===================================================== */

.details-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 15px;
}


.detail-card {

    min-height: 92px;

    padding: 19px;

    background:
        rgba(6, 21, 39, 0.94);

    border: 2px solid #173f5b;

    border-radius: 13px;

    transition: .2s ease;
}


.detail-card:hover {

    border-color: #00a9d1;

    box-shadow:

        0 0 18px
        rgba(0, 229, 255, 0.08);
}


.detail-label {

    margin-bottom: 8px;

    color: #7094ad;

    font-size: 11px;

    font-weight: 1000;

    text-transform: uppercase;

    letter-spacing: 1px;
}


.detail-value {

    color: #ffffff;

    font-size: 16px;

    font-weight: 900;

    line-height: 1.4;

    word-break: break-word;
}


/* =====================================================
   ANALYSIS
===================================================== */

.analysis-list {

    display: flex;

    flex-direction: column;

    gap: 10px;
}


.analysis-item {

    display: flex;

    align-items: flex-start;

    gap: 12px;

    padding: 15px 17px;

    background:
        rgba(5, 19, 35, 0.92);

    border: 2px solid #183e58;

    border-radius: 12px;

    color: #d9edf5;

    font-size: 14px;

    font-weight: 700;

    line-height: 1.5;
}


.analysis-icon {

    width: 25px;

    height: 25px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    font-size: 13px;

    font-weight: 1000;
}


.reason .analysis-icon {

    color: #00e5ff;

    border: 2px solid #00e5ff;

    background:
        rgba(0, 229, 255, 0.08);
}


.warning .analysis-icon {

    color: #ffc857;

    border: 2px solid #ffc857;

    background:
        rgba(255, 200, 87, 0.08);
}


/* =====================================================
   EMPTY
===================================================== */

.empty-message {

    padding: 18px;

    border: 2px dashed #31536a;

    border-radius: 12px;

    color: #7695a8;

    text-align: center;

    font-size: 13px;

    font-weight: 700;
}


/* =====================================================
   IMPORTANT NOTICE
===================================================== */

.notice {

    margin-top: 30px;

    padding: 20px;

    background:
        rgba(117, 95, 36, 0.10);

    border: 2px solid #755f24;

    border-radius: 14px;
}


.notice-title {

    margin-bottom: 8px;

    color: #ffd66b;

    font-size: 14px;

    font-weight: 1000;
}


.notice p {

    color: #d8cfae;

    font-size: 12px;

    font-weight: 600;

    line-height: 1.7;
}


/* =====================================================
   BUTTONS
===================================================== */

.buttons {

    display: flex;

    justify-content: center;

    gap: 15px;

    margin-top: 32px;

    flex-wrap: wrap;
}


.btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 205px;

    padding: 15px 22px;

    color: #00131d;

    text-decoration: none;

    border: 2px solid #00b8dc;

    border-radius: 10px;

    background:

        linear-gradient(
            135deg,
            #00e5ff,
            #00a8ff
        );

    font-size: 14px;

    font-weight: 1000;

    letter-spacing: .3px;

    box-shadow:

        0 0 18px
        rgba(0, 229, 255, 0.18);

    transition: .2s ease;
}


.btn:hover {

    transform: translateY(-2px);

    box-shadow:

        0 0 28px
        rgba(0, 229, 255, 0.35);
}


.btn-secondary {

    color: #b9eafa;

    background:
        rgba(0, 229, 255, 0.05);

    border: 2px solid #285a73;

    box-shadow: none;
}


.btn-secondary:hover {

    color: #ffffff;

    border-color: #00bcd4;

    background:
        rgba(0, 188, 212, 0.10);

    box-shadow:
        0 0 18px
        rgba(0, 188, 212, 0.15);
}


/* =====================================================
   FOOTER
===================================================== */

.footer {

    margin-top: 22px;

    color: #527287;

    text-align: center;

    font-size: 11px;

    font-weight: 700;

    line-height: 1.7;
}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 700px) {

    body {

        padding: 15px 10px;
    }


    .header {

        flex-direction: column;

        justify-content: center;

        padding: 18px;

        text-align: center;
    }


    .logo {

        justify-content: center;
    }


    .enterprise-badge {

        width: 100%;

        text-align: center;
    }


    .result-card {

        padding: 23px 16px;

        border-radius: 17px;
    }


    .status-icon {

        width: 88px;

        height: 88px;

        font-size: 46px;
    }


    .status-title {

        font-size: 35px;
    }


    .details-grid {

        grid-template-columns: 1fr;
    }


    .buttons {

        flex-direction: column;
    }


    .btn {

        width: 100%;
    }
}

</style>

</head>


<body>


<div class="container">


    <!-- =================================================
         HEADER
    ================================================== -->

    <header class="header">


        <div class="logo">


            <div class="logo-icon">
                DV
            </div>


            <div class="logo-text">

                <h1>
                    Enterprise DigiVerify
                </h1>

                <p>
                    DIGITAL DOCUMENT VERIFICATION PLATFORM
                </p>

            </div>


        </div>


        <div class="enterprise-badge">

            AI SCREENING SYSTEM

        </div>


    </header>



    <!-- =================================================
         RESULT
    ================================================== -->

    <main
        class="result-card <?php echo e($class); ?>"
    >


        <!-- =================================================
             STATUS
        ================================================== -->

        <div class="status-area">


            <div class="status-icon">

                <?php echo e($icon); ?>

            </div>


            <div class="status-title">

                <?php echo e($title); ?>

            </div>


            <div class="status-subtitle">

                <?php echo e($subtitle); ?>

            </div>


        </div>



        <!-- =================================================
             SCORE
        ================================================== -->

        <div class="score-box">


            <div class="score-label">

                AI SCREENING SCORE

            </div>


            <div class="score-value">

                <?php echo e($score); ?>/100

            </div>


            <div class="score-bar">

                <div class="score-fill"></div>

            </div>


        </div>



        <!-- =================================================
             DOCUMENT INFORMATION
        ================================================== -->

        <section class="section">


            <div class="section-title">

                📄 Document Information

            </div>


            <div class="details-grid">


                <div class="detail-card">

                    <div class="detail-label">

                        Document Holder

                    </div>


                    <div class="detail-value">

                        <?php echo e($name); ?>

                    </div>

                </div>



                <div class="detail-card">

                    <div class="detail-label">

                        Aadhaar Number

                    </div>


                    <div class="detail-value">

                        <?php echo e($aadhaar); ?>

                    </div>

                </div>



                <div class="detail-card">

                    <div class="detail-label">

                        OCR Engine

                    </div>


                    <div class="detail-value">

                        Tesseract.js

                    </div>

                </div>



                <div class="detail-card">

                    <div class="detail-label">

                        AI Screening Engine

                    </div>


                    <div class="detail-value">

                        DigiVerify AI

                    </div>

                </div>



                <div class="detail-card">

                    <div class="detail-label">

                        Verification Mode

                    </div>


                    <div class="detail-value">

                        Document Screening

                    </div>

                </div>



                <div class="detail-card">

                    <div class="detail-label">

                        Final Status

                    </div>


                    <div class="detail-value">

                        <?php echo e($status); ?>

                    </div>

                </div>


            </div>

        </section>



        <!-- =================================================
             ANALYSIS DETAILS
        ================================================== -->

        <section class="section">


            <div class="section-title">

                🔍 Analysis Details

            </div>


            <?php if (count($reasons) > 0): ?>


                <div class="analysis-list">


                    <?php foreach ($reasons as $reason): ?>


                        <div class="analysis-item reason">


                            <div class="analysis-icon">

                                ✓

                            </div>


                            <div>

                                <?php echo e(trim($reason)); ?>

                            </div>


                        </div>


                    <?php endforeach; ?>


                </div>


            <?php else: ?>


                <div class="empty-message">

                    No additional analysis details available.

                </div>


            <?php endif; ?>


        </section>



        <!-- =================================================
             WARNINGS
        ================================================== -->

        <?php if (count($warnings) > 0): ?>


        <section class="section">


            <div class="section-title">

                ⚠ Screening Warnings

            </div>


            <div class="analysis-list">


                <?php foreach ($warnings as $warning): ?>


                    <div class="analysis-item warning">


                        <div class="analysis-icon">

                            !

                        </div>


                        <div>

                            <?php echo e(trim($warning)); ?>

                        </div>


                    </div>


                <?php endforeach; ?>


            </div>


        </section>


        <?php endif; ?>



        <!-- =================================================
             IMPORTANT NOTICE
        ================================================== -->

        <div class="notice">


            <div class="notice-title">

                ⚠ Important Verification Notice

            </div>


            <p>

                Enterprise DigiVerify performs
                project-level document screening
                using OCR and rule-based analysis.
                This result does not represent official
                UIDAI authentication or government
                validation. Final acceptance of an
                identity document should be performed
                using the appropriate official
                verification process.

            </p>


        </div>



        <!-- =================================================
             BUTTONS
        ================================================== -->

        <div class="buttons">


            <a
                href="upload.php"
                class="btn"
            >

                🔄 Verify Another Document

            </a>


            <a
                href="../index.php"
                class="btn btn-secondary"
            >

                🏠 Back to Dashboard

            </a>


        </div>


    </main>



    <!-- =================================================
         FOOTER
    ================================================== -->

    <footer class="footer">


        Enterprise DigiVerify
        &nbsp;•&nbsp;
        AI Document Screening Platform
        &nbsp;•&nbsp;
        B.Sc. Computer Science Project


        <br>


        Project-level screening only —
        not official government authentication.


    </footer>


</div>


</body>

</html>
