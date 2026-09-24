<?php

/*
=========================================================
ENTERPRISE DIGIVERIFY
VERIFICATION RESULT
ONLY APPROVED / REJECTED
=========================================================
*/


/*
=========================================================
SAFE OUTPUT FUNCTION
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


/*
=========================================================
GET RESULT DATA
=========================================================
*/

$status = isset($_GET['status'])
    ? strtoupper(trim($_GET['status']))
    : 'REJECTED';


$score = isset($_GET['score'])
    ? (int)$_GET['score']
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
ONLY TWO STATUS VALUES
=========================================================
*/

if ($status !== 'APPROVED') {

    $status = 'REJECTED';
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


$reasons = array_values(
    array_filter(
        array_map(
            'trim',
            $reasons
        )
    )
);


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


$warnings = array_values(
    array_filter(
        array_map(
            'trim',
            $warnings
        )
    )
);


/*
=========================================================
STATUS UI
=========================================================
*/

if ($status === 'APPROVED') {

    $statusClass = 'approved';

    $statusIcon = '✓';

    $statusTitle = 'APPROVED';

    $statusSubtitle =
        'AI SCREENING PASSED';

    $statusDescription =
        'The document met the minimum DigiVerify screening criteria.';

} else {

    $statusClass = 'rejected';

    $statusIcon = '✕';

    $statusTitle = 'REJECTED';

    $statusSubtitle =
        'AI SCREENING FAILED';

    $statusDescription =
        'The document did not meet the minimum DigiVerify screening criteria.';
}


/*
=========================================================
DATE / TIME
=========================================================
*/

$resultDate =
    date('d M Y');

$resultTime =
    date('h:i A');

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

/*
=========================================================
GLOBAL
=========================================================
*/

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

    color: #ffffff;

    background:

        radial-gradient(
            circle at 15% 10%,
            rgba(0, 160, 220, .18),
            transparent 35%
        ),

        radial-gradient(
            circle at 90% 85%,
            rgba(0, 220, 180, .13),
            transparent 35%
        ),

        #040a14;

    padding: 30px;

}


/*
=========================================================
MAIN CONTAINER
=========================================================
*/

.page {

    width: 100%;

    max-width: 1050px;

    margin: auto;

}


/*
=========================================================
HEADER
=========================================================
*/

.header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 18px 24px;

    margin-bottom: 25px;

    background:
        rgba(7, 21, 38, .92);

    border: 1px solid #1c536e;

    border-radius: 15px;

    box-shadow:
        0 0 30px
        rgba(0, 180, 255, .08);

}


.brand {

    display: flex;

    align-items: center;

    gap: 13px;

}


.logo {

    width: 44px;

    height: 44px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 11px;

    background:
        linear-gradient(
            135deg,
            #00b8e6,
            #00d6ad
        );

    color: #00121b;

    font-size: 22px;

    font-weight: 1000;

}


.brand-name {

    color: #47dfff;

    font-size: 21px;

    font-weight: 1000;

}


.brand-subtitle {

    color: #7f9caf;

    font-size: 11px;

    margin-top: 3px;

    letter-spacing: .5px;

}


.header-label {

    color: #7f9caf;

    font-size: 11px;

    font-weight: 700;

}


/*
=========================================================
RESULT CARD
=========================================================
*/

.result-card {

    overflow: hidden;

    background:
        linear-gradient(
            145deg,
            rgba(8, 25, 44, .98),
            rgba(4, 13, 26, .98)
        );

    border: 1px solid #1d536d;

    border-radius: 22px;

    box-shadow:
        0 0 50px
        rgba(0, 180, 255, .09);

}


/*
=========================================================
STATUS AREA
=========================================================
*/

.status-area {

    text-align: center;

    padding: 48px 25px 40px;

    border-bottom: 1px solid #17384d;

}


.status-icon {

    width: 92px;

    height: 92px;

    margin: 0 auto 20px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    font-size: 50px;

    font-weight: 1000;

}


.status-area.approved .status-icon {

    color: #00e0ad;

    border: 3px solid #00e0ad;

    background:
        rgba(0, 224, 173, .08);

    box-shadow:
        0 0 35px
        rgba(0, 224, 173, .22);

}


.status-area.rejected .status-icon {

    color: #ff5570;

    border: 3px solid #ff5570;

    background:
        rgba(255, 85, 112, .08);

    box-shadow:
        0 0 35px
        rgba(255, 85, 112, .20);

}


.status-title {

    margin: 0;

    font-size: 42px;

    font-weight: 1000;

    letter-spacing: 2px;

}


.status-area.approved .status-title {

    color: #00e0ad;

}


.status-area.rejected .status-title {

    color: #ff5570;

}


.status-subtitle {

    margin-top: 9px;

    color: #8faabd;

    font-size: 13px;

    font-weight: 800;

    letter-spacing: 2px;

}


.status-description {

    max-width: 600px;

    margin: 16px auto 0;

    color: #9eb2c2;

    font-size: 14px;

    line-height: 1.6;

}


/*
=========================================================
CONTENT
=========================================================
*/

.content {

    padding: 30px;

}


/*
=========================================================
GRID
=========================================================
*/

.info-grid {

    display: grid;

    grid-template-columns:
        repeat(
            2,
            minmax(0, 1fr)
        );

    gap: 18px;

}


.info-box {

    padding: 20px;

    background:
        rgba(5, 17, 31, .85);

    border: 1px solid #1a4258;

    border-radius: 13px;

}


.info-label {

    margin-bottom: 8px;

    color: #6f91a5;

    font-size: 11px;

    font-weight: 800;

    letter-spacing: 1px;

    text-transform: uppercase;

}


.info-value {

    color: #edf8ff;

    font-size: 17px;

    font-weight: 800;

    word-break: break-word;

}


/*
=========================================================
SCORE
=========================================================
*/

.score-section {

    margin-top: 22px;

    padding: 22px;

    background:
        rgba(5, 17, 31, .85);

    border: 1px solid #1a4258;

    border-radius: 13px;

}


.score-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 13px;

}


.score-label {

    color: #8ea9ba;

    font-size: 12px;

    font-weight: 800;

    letter-spacing: 1px;

}


.score-value {

    color: #45dfff;

    font-size: 24px;

    font-weight: 1000;

}


.progress {

    height: 12px;

    overflow: hidden;

    background: #0b1726;

    border: 1px solid #23465a;

    border-radius: 20px;

}


.progress-fill {

    width:
        <?php echo $score; ?>%;

    height: 100%;

    border-radius: 20px;

    background:
        linear-gradient(
            90deg,
            #00aeea,
            #00d7a8
        );

}


/*
=========================================================
ANALYSIS
=========================================================
*/

.section {

    margin-top: 25px;

}


.section-title {

    margin-bottom: 13px;

    color: #48dfff;

    font-size: 15px;

    font-weight: 1000;

    letter-spacing: .5px;

}


.analysis-list {

    display: flex;

    flex-direction: column;

    gap: 10px;

}


.analysis-item {

    display: flex;

    align-items: flex-start;

    gap: 11px;

    padding: 13px 15px;

    background:
        rgba(7, 20, 34, .75);

    border: 1px solid #193c50;

    border-radius: 10px;

    color: #c5d6e1;

    font-size: 13px;

    line-height: 1.5;

}


.analysis-check {

    flex: 0 0 auto;

    color: #00dcb0;

    font-weight: 1000;

}


.warning-box {

    margin-top: 25px;

    padding: 19px;

    background:
        rgba(255, 166, 0, .055);

    border: 1px solid rgba(255, 166, 0, .28);

    border-radius: 12px;

}


.warning-title {

    margin-bottom: 10px;

    color: #ffc45c;

    font-size: 13px;

    font-weight: 1000;

}


.warning-item {

    padding: 5px 0;

    color: #c5bfae;

    font-size: 12px;

    line-height: 1.5;

}


/*
=========================================================
NOTICE
=========================================================
*/

.notice {

    margin-top: 25px;

    padding: 20px;

    background:
        rgba(10, 29, 46, .65);

    border: 1px solid #1b5068;

    border-radius: 12px;

    color: #8fa6b6;

    font-size: 12px;

    line-height: 1.7;

    text-align: center;

}


.notice strong {

    color: #c8e5f2;

}


/*
=========================================================
BUTTONS
=========================================================
*/

.buttons {

    display: grid;

    grid-template-columns:
        repeat(
            2,
            minmax(0, 1fr)
        );

    gap: 15px;

    margin-top: 25px;

}


.btn {

    display: flex;

    align-items: center;

    justify-content: center;

    min-height: 50px;

    border-radius: 10px;

    text-decoration: none;

    font-size: 14px;

    font-weight: 1000;

    transition: .2s ease;

}


.btn-primary {

    color: #00131a;

    background:
        linear-gradient(
            90deg,
            #00b7e8,
            #00d5ac
        );

    border: 1px solid #00c9dc;

}


.btn-secondary {

    color: #b7d1de;

    background:
        #0b1a2b;

    border: 1px solid #28546b;

}


.btn:hover {

    transform: translateY(-2px);

}


/*
=========================================================
FOOTER
=========================================================
*/

.footer {

    padding: 22px 10px 5px;

    text-align: center;

    color: #526c7d;

    font-size: 11px;

}


@media (max-width: 700px) {


    body {

        padding: 12px;

    }


    .header {

        padding: 15px;

    }


    .header-label {

        display: none;

    }


    .content {

        padding: 20px 15px;

    }


    .info-grid {

        grid-template-columns: 1fr;

    }


    .buttons {

        grid-template-columns: 1fr;

    }


    .status-title {

        font-size: 34px;

    }


    .status-icon {

        width: 78px;

        height: 78px;

        font-size: 40px;

    }

}

</style>

</head>


<body>


<div class="page">


    <!-- HEADER -->

    <div class="header">


        <div class="brand">


            <div class="logo">

                DV

            </div>


            <div>

                <div class="brand-name">

                    Enterprise DigiVerify

                </div>


                <div class="brand-subtitle">

                    DIGITAL IDENTITY & DOCUMENT SCREENING

                </div>

            </div>


        </div>


        <div class="header-label">

            VERIFICATION RESULT

        </div>


    </div>



    <!-- RESULT -->

    <div class="result-card">


        <div
            class="status-area <?php echo e($statusClass); ?>"
        >


            <div class="status-icon">

                <?php echo e($statusIcon); ?>

            </div>


            <h1 class="status-title">

                <?php echo e($statusTitle); ?>

            </h1>


            <div class="status-subtitle">

                <?php echo e($statusSubtitle); ?>

            </div>


            <div class="status-description">

                <?php echo e($statusDescription); ?>

            </div>


        </div>



        <div class="content">


            <!-- DOCUMENT INFORMATION -->

            <div class="info-grid">


                <div class="info-box">


                    <div class="info-label">

                        Document Holder

                    </div>


                    <div class="info-value">

                        <?php echo e($name); ?>

                    </div>


                </div>



                <div class="info-box">


                    <div class="info-label">

                        Aadhaar Number

                    </div>


                    <div class="info-value">

                        <?php echo e($aadhaar); ?>

                    </div>


                </div>



                <div class="info-box">


                    <div class="info-label">

                        Screening Date

                    </div>


                    <div class="info-value">

                        <?php echo e($resultDate); ?>

                    </div>


                </div>



                <div class="info-box">


                    <div class="info-label">

                        Screening Time

                    </div>


                    <div class="info-value">

                        <?php echo e($resultTime); ?>

                    </div>


                </div>


            </div>



            <!-- SCORE -->

            <div class="score-section">


                <div class="score-header">


                    <div class="score-label">

                        DIGIVERIFY SCREENING SCORE

                    </div>


                    <div class="score-value">

                        <?php echo e($score); ?>/100

                    </div>


                </div>


                <div class="progress">


                    <div class="progress-fill"></div>


                </div>


            </div>



            <!-- ANALYSIS -->

            <div class="section">


                <div class="section-title">

                    ANALYSIS DETAILS

                </div>


                <div class="analysis-list">


                    <?php if (count($reasons) > 0): ?>


                        <?php foreach ($reasons as $reason): ?>


                            <div class="analysis-item">


                                <span class="analysis-check">

                                    ✓

                                </span>


                                <span>

                                    <?php echo e($reason); ?>

                                </span>


                            </div>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <div class="analysis-item">


                            <span class="analysis-check">

                                ✓

                            </span>


                            <span>

                                DigiVerify screening completed.

                            </span>


                        </div>


                    <?php endif; ?>


                </div>


            </div>



            <!-- WARNINGS -->

            <?php if (count($warnings) > 0): ?>


                <div class="warning-box">


                    <div class="warning-title">

                        ⚠ SCREENING NOTES

                    </div>


                    <?php foreach ($warnings as $warning): ?>


                        <div class="warning-item">

                            • <?php echo e($warning); ?>

                        </div>


                    <?php endforeach; ?>


                </div>


            <?php endif; ?>



            <!-- IMPORTANT NOTICE -->

            <div class="notice">


                <strong>

                    Important:

                </strong>


                DigiVerify performs
                project-level document screening
                using OCR and document-pattern rules.


                <br>


                An <strong>APPROVED</strong> result means that
                the document met the configured
                DigiVerify screening criteria.


                <br>


                It does <strong>not</strong> represent official
                UIDAI authentication or confirmation
                that the document is legally genuine.


            </div>



            <!-- BUTTONS -->

            <div class="buttons">


                <a
                    href="upload.php"
                    class="btn btn-primary"
                >

                    ↻ VERIFY ANOTHER DOCUMENT

                </a>


                <a
                    href="../index.php"
                    class="btn btn-secondary"
                >

                    ← BACK TO DASHBOARD

                </a>


            </div>


        </div>


    </div>



    <div class="footer">

        Enterprise DigiVerify • AI-Assisted
        Digital Identity Screening

    </div>


</div>


</body>

</html>
