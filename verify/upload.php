<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


/*
=========================================================
ENTERPRISE DIGIVERIFY
AI DOCUMENT SCREENING
ONLY APPROVED / REJECTED
=========================================================
*/


/*
=========================================================
POST OCR DATA
=========================================================
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['ocr_text'])
) {

    $ocr = trim($_POST['ocr_text']);

    $jsName = isset($_POST['js_name'])
        ? trim($_POST['js_name'])
        : '';


    $score = 0;

    $reasons = [];

    $warnings = [];


    /*
    =====================================================
    NORMALIZE OCR
    =====================================================
    */

    $text = preg_replace(
        '/[ \t]+/',
        ' ',
        $ocr
    );

    $text = trim($text);

    $upper = strtoupper($text);


    /*
    =====================================================
    1. AADHAAR NUMBER DETECTION
    =====================================================
    */

    $aadhaar = '';

    $numberPatterns = [

        /*
        1234 5678 9012
        */

        '/\b([0-9]{4})[\s\-]+([0-9]{4})[\s\-]+([0-9]{4})\b/',

        /*
        1234-5678-9012
        */

        '/\b([0-9]{4})[\-]+([0-9]{4})[\-]+([0-9]{4})\b/',

        /*
        123456789012
        */

        '/\b([0-9]{4})([0-9]{4})([0-9]{4})\b/'
    ];


    foreach ($numberPatterns as $pattern) {

        if (
            preg_match(
                $pattern,
                $text,
                $m
            )
        ) {

            $aadhaar =
                $m[1] . ' ' .
                $m[2] . ' ' .
                $m[3];

            break;
        }
    }


    if ($aadhaar !== '') {

        $score += 35;

        $reasons[] =
            '12-digit Aadhaar number pattern detected.';

    } else {

        $warnings[] =
            'Aadhaar number was not clearly detected.';
    }


    /*
    =====================================================
    2. AADHAAR KEYWORD
    =====================================================
    */

    $aadhaarKeyword = false;


    $aadhaarWords = [

        'AADHAAR',
        'AADHAR',
        'आधार'

    ];


    foreach ($aadhaarWords as $word) {

        if (
            stripos(
                $upper,
                strtoupper($word)
            ) !== false
            ||
            strpos(
                $text,
                $word
            ) !== false
        ) {

            $aadhaarKeyword = true;

            break;
        }
    }


    if ($aadhaarKeyword) {

        $score += 20;

        $reasons[] =
            'Aadhaar identity indicator detected.';

    } else {

        $warnings[] =
            'Aadhaar keyword was not clearly detected.';
    }


    /*
    =====================================================
    3. GOVERNMENT / INDIA INDICATOR
    =====================================================
    */

    $govKeyword = false;


    $governmentPatterns = [

        'GOVERNMENT OF INDIA',
        'GOVT OF INDIA',
        'GOVERNMENT',
        'GOVT.',
        'INDIA',
        'भारत सरकार',
        'भारत'

    ];


    foreach ($governmentPatterns as $word) {

        if (
            stripos(
                $upper,
                strtoupper($word)
            ) !== false
            ||
            strpos(
                $text,
                $word
            ) !== false
        ) {

            $govKeyword = true;

            break;
        }
    }


    if ($govKeyword) {

        $score += 10;

        $reasons[] =
            'Government/India indicator detected.';

    } else {

        $warnings[] =
            'Government/India indicator not clearly detected.';
    }


    /*
    =====================================================
    4. DOB CHECK
    =====================================================
    */

    $dobFound = false;


    $dobPatterns = [

        /*
        DD/MM/YYYY
        DD-MM-YYYY
        */

        '/\b[0-3]?[0-9][\/\-][0-1]?[0-9][\/\-][12][0-9]{3}\b/',

        /*
        YYYY/MM/DD
        YYYY-MM-DD
        */

        '/\b[12][0-9]{3}[\/\-][0-1]?[0-9][\/\-][0-3]?[0-9]\b/',

        /*
        DOB
        */

        '/\bDOB\b/i',

        /*
        DATE OF BIRTH
        */

        '/\bDATE OF BIRTH\b/i'

    ];


    foreach ($dobPatterns as $pattern) {

        if (
            preg_match(
                $pattern,
                $text
            )
        ) {

            $dobFound = true;

            break;
        }
    }


    if ($dobFound) {

        $score += 8;

        $reasons[] =
            'Date-of-birth information detected.';

    } else {

        $warnings[] =
            'Date-of-birth information not detected.';
    }


    /*
    =====================================================
    5. GENDER CHECK
    =====================================================
    */

    $genderFound = false;


    $genderWords = [

        'MALE',
        'FEMALE',
        'TRANSGENDER',
        'पुरुष',
        'महिला'

    ];


    foreach ($genderWords as $word) {

        if (
            stripos(
                $upper,
                strtoupper($word)
            ) !== false
            ||
            strpos(
                $text,
                $word
            ) !== false
        ) {

            $genderFound = true;

            break;
        }
    }


    if ($genderFound) {

        $score += 5;

        $reasons[] =
            'Demographic indicator detected.';
    }


    /*
    =====================================================
    6. PIN CODE
    =====================================================
    */

    $pinFound = false;


    if (
        preg_match(
            '/\b[1-9][0-9]{5}\b/',
            $text
        )
    ) {

        $pinFound = true;

        $score += 5;

        $reasons[] =
            'Six-digit postal code pattern detected.';
    }


    /*
    =====================================================
    7. NAME DETECTION
    =====================================================
    */

    $detectedName = '';


    $lines = preg_split(
        '/\r\n|\r|\n/',
        $ocr
    );


    foreach ($lines as $line) {

        $line = trim($line);


        if ($line === '') {
            continue;
        }


        $u = strtoupper($line);


        /*
        Skip obvious headings
        */

        if (
            strpos($u, 'GOVERNMENT') !== false ||
            strpos($u, 'GOVT') !== false ||
            strpos($u, 'INDIA') !== false ||
            strpos($u, 'AADHAAR') !== false ||
            strpos($u, 'AADHAR') !== false ||
            strpos($u, 'DATE OF BIRTH') !== false ||
            strpos($u, 'DOB') !== false ||
            strpos($u, 'YEAR OF BIRTH') !== false ||
            strpos($u, 'MALE') !== false ||
            strpos($u, 'FEMALE') !== false ||
            strpos($line, 'भारत') !== false ||
            strpos($line, 'आधार') !== false
        ) {

            continue;
        }


        /*
        Skip lines containing numbers
        */

        if (
            preg_match(
                '/[0-9]/',
                $line
            )
        ) {

            continue;
        }


        /*
        Remove OCR garbage symbols
        */

        $cleanName = preg_replace(
            '/[^A-Za-zÀ-ÿ .\'\-]/',
            '',
            $line
        );


        $cleanName = trim(
            preg_replace(
                '/\s+/',
                ' ',
                $cleanName
            )
        );


        /*
        Basic name quality check
        */

        if (
            strlen($cleanName) >= 3 &&
            strlen($cleanName) <= 60
        ) {

            /*
            Require at least 2 alphabetic characters
            */

            if (
                preg_match(
                    '/[A-Za-z]{2,}/',
                    $cleanName
                )
            ) {

                $detectedName = $cleanName;

                break;
            }
        }
    }


    /*
    JS detected name fallback
    */

    if (
        $detectedName === '' &&
        $jsName !== ''
    ) {

        $cleanJsName = preg_replace(
            '/[^A-Za-zÀ-ÿ .\'\-]/',
            '',
            $jsName
        );


        $cleanJsName = trim(
            preg_replace(
                '/\s+/',
                ' ',
                $cleanJsName
            )
        );


        if (
            strlen($cleanJsName) >= 3 &&
            strlen($cleanJsName) <= 60
        ) {

            $detectedName = $cleanJsName;
        }
    }


    if ($detectedName !== '') {

        $score += 7;

        $reasons[] =
            'Possible document-holder name detected.';

    } else {

        $warnings[] =
            'Holder name could not be confidently detected.';
    }


    /*
    =====================================================
    8. OCR QUALITY
    =====================================================
    */

    $length = strlen(
        trim($ocr)
    );


    if ($length >= 150) {

        $score += 10;

        $reasons[] =
            'OCR extracted sufficient document information.';

    } elseif ($length >= 80) {

        $score += 6;

        $reasons[] =
            'OCR extracted moderate document information.';

    } elseif ($length >= 40) {

        $score += 2;

        $warnings[] =
            'OCR output is limited.';

    } else {

        $warnings[] =
            'OCR output is too short.';
    }


    /*
    =====================================================
    9. SUSPICIOUS DOCUMENT WORDS
    =====================================================
    */

    $suspiciousWords = [

        'DUPLICATE',
        'SAMPLE',
        'SPECIMEN',
        'FAKE',
        'DEMO',
        'NOT VALID',
        'INVALID',
        'FOR DEMO',
        'SAMPLE COPY'

    ];


    $suspiciousFound = [];


    foreach (
        $suspiciousWords as $word
    ) {

        if (
            stripos(
                $upper,
                $word
            ) !== false
        ) {

            $suspiciousFound[] =
                $word;
        }
    }


    /*
    =====================================================
    10. SUSPICIOUS PENALTY
    =====================================================
    */

    if (
        count($suspiciousFound) > 0
    ) {

        $score -= 55;

        $reasons[] =
            'Suspicious document indicator detected: ' .
            implode(
                ', ',
                $suspiciousFound
            );
    }


    /*
    =====================================================
    SCORE LIMIT
    =====================================================
    */

    if ($score < 0) {

        $score = 0;
    }


    if ($score > 100) {

        $score = 100;
    }


    /*
    =====================================================
    FINAL DECISION
    STRICT APPROVAL
    ONLY APPROVED / REJECTED
    =====================================================
    */

    $hasAadhaarNumber =
        ($aadhaar !== '');


    $hasAadhaarWord =
        ($aadhaarKeyword === true);


    $hasGovIndicator =
        ($govKeyword === true);


    /*
    =====================================================
    RULE 1
    SUSPICIOUS = REJECTED
    =====================================================
    */

    if (
        count($suspiciousFound) > 0
    ) {

        $status = 'REJECTED';
    }


    /*
    =====================================================
    RULE 2
    STRONG APPROVAL
    Aadhaar Number
    +
    Aadhaar Keyword
    +
    Government Indicator
    +
    Score >= 45
    =====================================================
    */

    elseif (

        $hasAadhaarNumber &&
        $hasAadhaarWord &&
        $hasGovIndicator &&
        $score >= 45

    ) {

        $status = 'APPROVED';
    }


    /*
    =====================================================
    RULE 3
    SECOND APPROVAL
    Aadhaar Number
    +
    Aadhaar Keyword
    +
    Score >= 55
    =====================================================
    */

    elseif (

        $hasAadhaarNumber &&
        $hasAadhaarWord &&
        $score >= 55

    ) {

        $status = 'APPROVED';
    }


    /*
    =====================================================
    EVERYTHING ELSE = REJECTED
    =====================================================
    */

    else {

        $status = 'REJECTED';
    }


    /*
    =====================================================
    MASK AADHAAR
    =====================================================
    */

    $maskedAadhaar =
        'XXXX XXXX XXXX';


    if ($aadhaar !== '') {

        $parts =
            preg_split(
                '/\s+/',
                $aadhaar
            );


        if (
            count($parts) === 3
        ) {

            $maskedAadhaar =
                'XXXX XXXX ' .
                $parts[2];
        }
    }


    /*
    =====================================================
    SEND RESULT
    =====================================================
    */

    $params = [

        'status' =>
            $status,

        'score' =>
            $score,

        'name' =>
            $detectedName,

        'aadhaar' =>
            $maskedAadhaar,

        'reason' =>
            implode(
                '|',
                $reasons
            ),

        'warning' =>
            implode(
                '|',
                $warnings
            )

    ];


    header(
        'Location: verification_result.php?' .
        http_build_query($params)
    );


    exit;
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
Enterprise DigiVerify - Document Verification
</title>


<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>


<style>

/* =====================================================
   GLOBAL
===================================================== */

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

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 25px;
}


/* =====================================================
   MAIN CONTAINER
===================================================== */

.container {

    width: 100%;

    max-width: 760px;

    padding: 40px;

    background:
        linear-gradient(
            145deg,
            rgba(8, 25, 45, .98),
            rgba(4, 13, 27, .98)
        );

    border: 2px solid #195477;

    border-radius: 22px;

    box-shadow:
        0 0 50px
        rgba(0, 190, 255, .16);
}


/* =====================================================
   LOGO
===================================================== */

.logo {

    text-align: center;

    color: #42d9ff;

    font-size: 31px;

    font-weight: 1000;

    letter-spacing: .5px;
}


.subtitle {

    margin: 10px 0 30px;

    text-align: center;

    color: #9fb5c9;

    font-size: 14px;

    font-weight: 700;
}


/* =====================================================
   UPLOAD BOX
===================================================== */

.upload-box {

    padding: 35px;

    background:
        rgba(5, 18, 34, .8);

    border: 2px dashed #2386a8;

    border-radius: 16px;
}


input[type=file] {

    width: 100%;

    padding: 15px;

    background: #0c1a2c;

    color: white;

    border: 2px solid #31556e;

    border-radius: 10px;

    cursor: pointer;
}


input[type=file]::file-selector-button {

    margin-right: 12px;

    padding: 9px 14px;

    border: 0;

    border-radius: 7px;

    background: #00b8e6;

    color: #00131c;

    font-weight: 800;

    cursor: pointer;
}


/* =====================================================
   BUTTON
===================================================== */

button {

    width: 100%;

    margin-top: 20px;

    padding: 17px;

    border: 2px solid #00b8dc;

    border-radius: 10px;

    background:
        linear-gradient(
            90deg,
            #00a8e8,
            #00d4aa
        );

    color: #001018;

    font-size: 17px;

    font-weight: 1000;

    cursor: pointer;

    box-shadow:
        0 0 20px
        rgba(0, 212, 255, .18);

    transition: .2s ease;
}


button:hover {

    transform: translateY(-2px);

    box-shadow:
        0 0 28px
        rgba(0, 212, 255, .30);
}


/* =====================================================
   LOADING
===================================================== */

#loading-box {

    display: none;

    margin-top: 25px;

    padding: 22px;

    border-radius: 12px;

    background: #0b1b2e;

    border: 2px solid #24728e;
}


#status-text {

    color: #42d9ff;

    text-align: center;

    font-size: 14px;

    font-weight: 800;
}


/* =====================================================
   NOTICE
===================================================== */

.notice {

    margin-top: 25px;

    padding: 17px;

    background:
        rgba(11, 23, 39, .95);

    border: 1px solid #254b62;

    border-radius: 10px;

    color: #9fb1c0;

    font-size: 12px;

    line-height: 1.7;

    text-align: center;
}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 600px) {

    body {

        padding: 12px;
    }


    .container {

        padding: 22px 16px;

        border-radius: 17px;
    }


    .logo {

        font-size: 25px;
    }


    .upload-box {

        padding: 22px 15px;
    }


    button {

        font-size: 15px;
    }
}

</style>

</head>


<body>


<div class="container">


    <!-- =================================================
         HEADER
    ================================================== -->

    <div class="logo">

        Enterprise DigiVerify

    </div>


    <div class="subtitle">

        AI-Assisted Aadhaar Document Screening

    </div>



    <!-- =================================================
         UPLOAD
    ================================================== -->

    <div class="upload-box">


        <input
            type="file"
            id="file-input"
            accept="image/jpeg,image/png,image/webp"
        >


        <button
            type="button"
            id="verify-button"
            onclick="startVerification()"
        >

            🔍 ANALYZE & VERIFY DOCUMENT

        </button>


    </div>



    <!-- =================================================
         LOADING
    ================================================== -->

    <div id="loading-box">

        <div id="status-text">

            Initializing OCR...

        </div>

    </div>



    <!-- =================================================
         NOTICE
    ================================================== -->

    <div class="notice">

        <strong>
            AI Screening Engine
        </strong>

        <br>

        OCR • Aadhaar Pattern • Government Indicator •
        DOB • Demographic Data • PIN Code •
        Suspicious Document Detection

        <br><br>

        Result represents DigiVerify
        project-level screening and is not
        official UIDAI authentication.

    </div>



    <!-- =================================================
         HIDDEN FORM
    ================================================== -->

    <form
        method="POST"
        id="main-form"
    >

        <input
            type="hidden"
            name="ocr_text"
            id="ocr-hidden-input"
        >


        <input
            type="hidden"
            name="js_name"
            id="js-name-input"
        >

    </form>


</div>



<script>

/*
=========================================================
START VERIFICATION
=========================================================
*/

async function startVerification() {


    const input =
        document.getElementById(
            'file-input'
        );


    const button =
        document.getElementById(
            'verify-button'
        );


    const loading =
        document.getElementById(
            'loading-box'
        );


    const status =
        document.getElementById(
            'status-text'
        );


    /*
    -------------------------------------------------------
    CHECK FILE
    -------------------------------------------------------
    */

    if (
        !input.files ||
        input.files.length === 0
    ) {

        alert(
            'Please select an Aadhaar document image.'
        );

        return;
    }


    const file =
        input.files[0];


    /*
    -------------------------------------------------------
    CHECK FILE TYPE
    -------------------------------------------------------
    */

    const allowedTypes = [

        'image/jpeg',
        'image/png',
        'image/webp'

    ];


    if (
        !allowedTypes.includes(
            file.type
        )
    ) {

        alert(
            'Please upload JPG, PNG or WEBP image.'
        );

        return;
    }


    /*
    -------------------------------------------------------
    CHECK FILE SIZE
    -------------------------------------------------------
    */

    if (
        file.size > 10 * 1024 * 1024
    ) {

        alert(
            'Image size must be less than 10 MB.'
        );

        return;
    }


    /*
    -------------------------------------------------------
    UI
    -------------------------------------------------------
    */

    button.disabled = true;

    button.style.opacity = '0.65';

    button.style.cursor = 'not-allowed';

    loading.style.display = 'block';


    try {


        /*
        ---------------------------------------------------
        INITIALIZE OCR
        ---------------------------------------------------
        */

        status.textContent =
            'Loading OCR engine...';


        /*
        ---------------------------------------------------
        TESSERACT OCR
        ---------------------------------------------------
        */

        const result =
            await Tesseract.recognize(

                file,

                'eng',

                {

                    logger: function(message) {


                        if (
                            message.status ===
                            'recognizing text'
                        ) {


                            const progress =
                                Math.round(
                                    message.progress * 100
                                );


                            status.textContent =
                                'OCR Analysis: ' +
                                progress +
                                '%';

                        }

                    }

                }

            );


        /*
        ---------------------------------------------------
        OCR TEXT
        ---------------------------------------------------
        */

        const text =
            result &&
            result.data &&
            result.data.text
                ? result.data.text
                : '';


        /*
        ---------------------------------------------------
        CHECK OCR
        ---------------------------------------------------
        */

        if (
            text.trim() === ''
        ) {

            throw new Error(
                'No OCR text detected.'
            );
        }


        /*
        ---------------------------------------------------
        SEND OCR TO PHP
        ---------------------------------------------------
        */

        document
            .getElementById(
                'ocr-hidden-input'
            )
            .value = text;


        /*
        ---------------------------------------------------
        POSSIBLE NAME
        ---------------------------------------------------
        */

        let possibleName = '';


        const lines =
            text.split(
                /\r?\n/
            );


        for (
            let i = 0;
            i < lines.length;
            i++
        ) {


            const originalLine =
                lines[i].trim();


            const current =
                originalLine
                    .toUpperCase();


            if (
                current.includes(
                    'GOVERNMENT'
                ) ||
                current.includes(
                    'GOVT'
                ) ||
                current === 'INDIA'
            ) {


                for (
                    let j = i + 1;
                    j < Math.min(
                        i + 4,
                        lines.length
                    );
                    j++
                ) {


                    const candidate =
                        lines[j].trim();


                    if (
                        candidate.length >= 3 &&
                        candidate.length <= 60 &&
                        !/[0-9]/.test(candidate) &&
                        !candidate
                            .toUpperCase()
                            .includes('AADHAAR') &&
                        !candidate
                            .toUpperCase()
                            .includes('AADHAR') &&
                        !candidate
                            .toUpperCase()
                            .includes('DOB')
                    ) {


                        possibleName =
                            candidate;


                        break;
                    }

                }


                if (
                    possibleName !== ''
                ) {

                    break;
                }

            }

        }


        /*
        ---------------------------------------------------
        STORE POSSIBLE NAME
        ---------------------------------------------------
        */

        document
            .getElementById(
                'js-name-input'
            )
            .value =
                possibleName;


        /*
        ---------------------------------------------------
        SUBMIT
        ---------------------------------------------------
        */

        status.textContent =
            'OCR completed. Running document screening...';


        document
            .getElementById(
                'main-form'
            )
            .submit();


    } catch (error) {


        console.error(
            'OCR Error:',
            error
        );


        loading.style.display =
            'block';


        status.textContent =
            'OCR processing failed.';


        alert(
            'Could not analyze this image. Please use a clear JPG, PNG or WEBP image.'
        );


        button.disabled = false;

        button.style.opacity = '1';

        button.style.cursor = 'pointer';

    }

}

</script>


</body>

</html>
