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

$name = isset($_GET['name']) && trim($_GET['name']) !== ''
    ? trim($_GET['name'])
    : 'NOT DETECTED';

$aadhaar = isset($_GET['aadhaar']) && trim($_GET['aadhaar']) !== ''
    ? trim($_GET['aadhaar'])
    : 'XXXX XXXX XXXX';

$reasonString = isset($_GET['reason'])
    ? trim($_GET['reason'])
    : '';

$warningString = isset($_GET['warning'])
    ? trim($_GET['warning'])
    : '';


/*
=========================================================
REASONS / WARNINGS
=========================================================
*/

$reasons = [];

if ($reasonString !== '') {
    $reasons = explode('|', $reasonString);
}

$warnings = [];

if ($warningString !== '') {
    $warnings = explode('|', $warningString);
}


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
STATUS HANDLING
ONLY APPROVED OR REJECTED
=========================================================
*/

if ($status === 'APPROVED') {

    $status = 'APPROVED';

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
=========================================================
SAFE OUTPUT FUNCTION
=========================================================
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
    DigiVerify | Verification Result
</title>


<style>

/* =====================================================
   GLOBAL
===================================================== */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {

    min-height: 100vh;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background:
        radial-gradient(
            circle at top left,
            rgba(0, 229, 255, 0.12),
            transparent 35%
        ),
        radial-gradient(
            circle at bottom right,
            rgba(0, 90, 180, 0.15),
            transparent 35%
        ),
        #050b17;

    color: #ffffff;

    padding: 30px 15px;
}


/* =====================================================
   MAIN CONTAINER
===================================================== */

.container {

    width: 100%;

    max-width: 1050px;

    margin: auto;
}


/* =====================================================
   HEADER
===================================================== */

.header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 22px 28px;

    margin-bottom: 25px;

    background:
        linear-gradient(
            145deg,
            rgba(13, 30, 55, 0.98),
            rgba(5, 15, 30, 0.98)
        );

    border: 2px solid #16496d;

    border-radius: 18px;

    box-shadow:
        0 0 25px rgba(0, 229, 255, 0.10),
        inset 0 0 25px rgba(0, 229, 255, 0.03);
}


.logo {

    display: flex;

    align-items: center;

    gap: 14px;
}


.logo-icon {

    width: 50px;

    height: 50px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    font-size: 25px;

    font-weight: 900;

    color: #00131d;

    background:
        linear-gradient(
            135deg,
            #00e5ff,
            #00a8ff
        );

    box-shadow:
        0 0 20px rgba(0, 229, 255, 0.45);
}


.logo-text h1 {

    font-size: 23px;

    font-weight: 900;

    letter-spacing: 0.5px;
}


.logo-text p {

    margin-top: 4px;

    font-size: 12px;

    font-weight: 700;

    color: #7edfff;

    letter-spacing: 1px;
}


.enterprise-badge {

    padding: 9px 15px;

    border: 2px solid #00bcd4;

    border-radius: 9px;

    color: #7eeeff;

    font-size: 11px;

    font-weight: 900;

    letter-spacing: 1px;

    background: rgba(0, 188, 212, 0.08);
}


/* =====================================================
   RESULT CARD
===================================================== */

.result-card {

    position: relative;

    overflow: hidden;

    background:
        linear-gradient(
            145deg,
            rgba(10, 25, 47, 0.98),
            rgba(4, 13, 27, 0.98)
        );

    border: 2px solid #174b70;

    border-radius: 22px;

    padding: 35px;

    box-shadow:
        0 20px 60px rgba(0, 0, 0, 0.55),
        inset 0 0 35px rgba(0, 229, 255, 0.025);
}


.result-card::before {

    content: "";

    position: absolute;

    top: 0;

    left: 0;

    right: 0;

    height: 4px;

    background:
        linear-gradient(
            90deg,
            #00e5ff,
            #008cff,
            #00e5ff
        );
}


/* =====================================================
   STATUS AREA
===================================================== */

.status-area {

    text-align: center;

    padding: 15px 0 35px;
}


.status-icon {

    width: 105px;

    height: 105px;

    margin: 0 auto 20px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    font-size: 58px;

    font-weight: 900;
}


.status-title {

    font-size: 45px;

    line-height: 1;

    font-weight: 1000;

    letter-spacing: 2px;

    margin-bottom: 10px;
}


.status-subtitle {

    font-size: 14px;

    font-weight: 900;

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
        0 0 35px rgba(0, 255, 174, 0.30),
        inset 0 0 20px rgba(0, 255, 174, 0.08);
}


.approved .status-title {

    color: #00ffae;

    text-shadow:
        0 0 20px rgba(0, 255, 174, 0.35);
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
        0 0 35px rgba(255, 79, 103, 0.28),
        inset 0 0 20px rgba(255, 79, 103, 0.08);
}


.rejected .status-title {

    color: #ff4f67;

    text-shadow:
        0 0 20px rgba(255, 79, 103, 0.35);
}


.rejected .status-subtitle {

    color: #ff9eaa;
}


/* =====================================================
   SCORE
===================================================== */

.score-box {

    margin: 0 auto 30px;

    max-width: 500px;

    padding: 22px;

    text-align: center;

    border: 2px solid #205879;

    border-radius: 16px;

    background:
        rgba(0, 20, 40, 0.70);
}


.score-label {

    color: #7ba7c4;

    font-size: 12px;

    font-weight: 900;

    letter-spacing: 2px;

    margin-bottom: 8px;
}


.score-value {

    font-size: 42px;

    font-weight: 1000;

    color: #00e5ff;

    text-shadow:
        0 0 18px rgba(0, 229, 255, 0.35);
}


.score-bar {

    width: 100%;

    height: 12px;

    margin-top: 14px;

    overflow: hidden;

    border-radius: 20px;

    background: #10263b;

    border: 1px solid #24536e;
}


.score-fill {

    height: 100%;

    width: <?php echo $score; ?>%;

    border-radius: 20px;

    background:
        linear-gradient(
            90deg,
            #00a8ff,
            #00e5ff
        );

    box-shadow:
        0 0 15px rgba(0, 229, 255, 0.45);
}


/* =====================================================
   SECTION
===================================================== */

.section {

    margin-top: 28px;
}


.section-title {

    display: flex;

    align-items: center;

    gap: 10px;

    padding-left: 14px;

    margin-bottom: 15px;

    border-left: 5px solid #00e5ff;

    color: #dffaff;

    font-size: 17px;

    font-weight: 1000;

    letter-spacing: 0.4px;
}


/* =====================================================
   DETAILS GRID
===================================================== */

.details-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 15px;
}


.detail-card {

    padding: 19px;

    min-height: 90px;

    background:
        rgba(6, 21, 39, 0.92);

    border: 2px solid #173f5b;

    border-radius: 13px;

    transition: 0.2s ease;
}


.detail-card:hover {

    border-color: #00a9d1;

    box-shadow:
        0 0 18px rgba(0, 229, 255, 0.08);
}


.detail-label {

    margin-bottom: 8px;

    color: #7094ad;

    font-size: 11px;

    font-weight: 900;

    text-transform: uppercase;

    letter-spacing: 1px;
}


.detail-value {

    color: #ffffff;

    font-size: 16px;

    font-weight: 900;

    word-break: break-word;
}


/* =====================================================
   ANALYSIS LIST
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

    border: 2px solid #183e58;

    border-radius: 12px;

    background:
        rgba(5, 19, 35, 0.90);

    color: #d9edf5;

    font-size: 14px;

    font-weight: 700;

    line-height: 1.5;
}


.analysis-icon {

    flex-shrink: 0;

    width: 24px;

    height: 24px;

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
   EMPTY MESSAGE
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
   NOTICE
===================================================== */

.notice {

    margin-top: 30px;

    padding: 20px;

    border: 2px solid #755f24;

    border-radius: 14px;

    background:
        rgba(117, 95, 36, 0.10);
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

    min-width: 190px;

    padding: 14px 22px;

    text-decoration: none;

    border-radius: 10px;

    font-size: 14px;

    font-weight: 1000;

    letter-spacing: 0.3px;

    border: 2px solid #00b8dc;

    color: #00131d;

    background:
        linear-gradient(
            135deg,
            #00e5ff,
            #00a8ff
        );

    box-shadow:
        0 0 18px rgba(0, 229, 255, 0.18);

    transition: 0.2s ease;
}


.btn:hover {

    transform: translateY(-2px);

    box-shadow:
        0 0 28px rgba(0, 229, 255, 0.35);
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
}


/* =====================================================
   FOOTER
===================================================== */

.footer {

    margin-top: 22px;

    text-align: center;

    color: #527287;

    font-size: 11px;

    font-weight: 700;

    line-height: 1.7;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 700px) {

    body {
        padding: 15px 10px;
    }

    .header {

        padding: 17px;

        flex-direction: column;

        gap: 15px;

        text-align: center;
    }

    .logo {
        justify-content: center;
    }

    .result-card {

        padding: 22px 16px;

        border-radius: 17px;
    }

    .status-icon {

        width: 85px;

        height: 85px;

        font-size: 45px;
    }

    .status-title {

        font-size: 34px;
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
         RESULT CARD
    ================================================== -->

    <main class="result-card <?php echo e($class); ?>">


        <!-- STATUS -->

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
             ANALYSIS REASONS
        ================================================== -->

        <section class="section">

            <div class="section-title">
                🔍 Analysis Details
            </div>


            <?php if (count($reasons) > 0): ?>

                <div class="analysis-list">

                    <?php foreach ($reasons as $reason): ?>

                        <?php

                        $reason = trim($reason);

                        if ($reason === '') {
                            continue;
                        }

                        ?>

                        <div class="analysis-item reason">

                            <div class="analysis-icon">
                                ✓
                            </div>

                            <div>
                                <?php echo e($reason); ?>
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

                    <?php

                    $warning = trim($warning);

                    if ($warning === '') {
                        continue;
                    }

                    ?>

                    <div class="analysis-item warning">

                        <div class="analysis-icon">
                            !
                        </div>

                        <div>
                            <?php echo e($warning); ?>
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
                Enterprise DigiVerify performs project-level
                document screening using OCR and rule-based
                analysis. This result does not represent official
                UIDAI authentication or government validation.
                Final acceptance of an identity document should
                be performed using the appropriate official
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

        Project-level screening only — not official government authentication.

    </footer>


</div>


</body>

</html>
