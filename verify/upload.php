<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ocr_text'])) {

    $extracted_text = $_POST['ocr_text'];

    $detected_name = "NOT DETECTED";
    $aadhaar_no = "XXXX XXXX XXXX";
    $is_ai = "false";

    // ==========================================
    // AADHAAR NUMBER DETECTION
    // ==========================================

    if (
        preg_match(
            '/[0-9]{4}\s[0-9]{4}\s[0-9]{4}/',
            $extracted_text,
            $matches
        )
    ) {
        $aadhaar_no = $matches[0];
    }


    // ==========================================
    // BASIC DOCUMENT RISK CHECK
    // ==========================================

    $risk_words = [
        'DUPLICATE',
        'SAMPLE',
        'COPY',
        'FAKE'
    ];

    foreach ($risk_words as $word) {

        if (
            stripos($extracted_text, $word) !== false
        ) {
            $is_ai = "true";
            break;
        }
    }


    // ==========================================
    // NAME DETECTION
    // ==========================================

    $lines = preg_split(
        "/\r\n|\n|\r/",
        $extracted_text
    );

    foreach ($lines as $key => $line) {

        $line = trim($line);

        if (
            stripos($line, 'GOVERNMENT') !== false ||
            stripos($line, 'INDIA') !== false ||
            stripos($line, 'सरकार') !== false
        ) {

            if (
                isset($lines[$key + 1]) &&
                strlen(trim($lines[$key + 1])) > 3 &&
                !preg_match('/[0-9]/', $lines[$key + 1])
            ) {

                $detected_name =
                    trim($lines[$key + 1]);

                break;
            }
        }
    }


    // ==========================================
    // JAVASCRIPT DETECTED NAME
    // ==========================================

    if (
        (
            $detected_name === "NOT DETECTED" ||
            strlen($detected_name) < 3
        )
        &&
        isset($_POST['js_name']) &&
        !empty($_POST['js_name'])
    ) {

        $detected_name =
            trim($_POST['js_name']);
    }


    // ==========================================
    // SEND RESULT
    // ==========================================

    header(
        "Location: verification_result.php?" .
        "name=" . urlencode($detected_name) .
        "&aadhaar=" . urlencode($aadhaar_no) .
        "&is_ai=" . urlencode($is_ai)
    );

    exit();
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
        AI Document Secure Upload | DigiVerify
    </title>

    <!-- Tesseract.js -->
    <script
        src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js">
    </script>


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

            background: #08111f;

            color: #fff;

            display: flex;

            justify-content: center;

            align-items: center;

            min-height: 100vh;

            padding: 40px 0;
        }


        .upload-card {

            background:
                linear-gradient(
                    145deg,
                    #0f172a,
                    #0b1324
                );

            border:
                1px solid
                rgba(56, 189, 248, 0.2);

            border-radius: 24px;

            padding: 40px;

            max-width: 500px;

            width: 90%;

            box-shadow:
                0 25px 60px
                rgba(0, 0, 0, 0.7);

            text-align: center;

            position: relative;
        }


        h1 {

            font-size: 24px;

            font-weight: 800;

            margin-bottom: 10px;
        }


        p {

            color: #9fb3d6;

            font-size: 14px;

            margin-bottom: 30px;

            line-height: 1.5;
        }


        .file-box {

            border:
                2px dashed
                rgba(56, 189, 248, 0.4);

            padding: 30px;

            border-radius: 14px;

            margin-bottom: 25px;

            background:
                rgba(19, 29, 52, 0.4);

            cursor: pointer;

            position: relative;
        }


        .file-box input[type="file"] {

            position: absolute;

            left: 0;

            top: 0;

            width: 100%;

            height: 100%;

            opacity: 0;

            cursor: pointer;

            z-index: 2;
        }


        .btn-submit {

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #9333ea
                );

            color: #fff;

            border: none;

            padding: 14px 28px;

            border-radius: 12px;

            font-weight: 700;

            font-size: 15px;

            width: 100%;

            cursor: pointer;

            box-shadow:
                0 4px 20px
                rgba(59, 130, 246, 0.3);

            transition: 0.3s;

            position: relative;

            z-index: 10;
        }


        .btn-submit:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 6px 25px
                rgba(147, 51, 234, 0.4);
        }


        .btn-back {

            background: transparent;

            color: #38bdf8;

            border:
                2px solid
                rgba(56, 189, 248, 0.4);

            padding: 14px 28px;

            border-radius: 12px;

            font-weight: 700;

            font-size: 15px;

            width: 100%;

            cursor: pointer;

            transition: 0.3s;
        }


        .btn-back:hover {

            background:
                rgba(56, 189, 248, 0.1);

            border-color: #38bdf8;

            transform:
                translateY(-2px);
        }


        .loading-overlay {

            display: none;

            position: absolute;

            top: 0;

            left: 0;

            width: 100%;

            height: 100%;

            background:
                rgba(11, 23, 42, 0.97);

            border-radius: 24px;

            flex-direction: column;

            justify-content: center;

            align-items: center;

            z-index: 100;
        }


        .spinner {

            width: 50px;

            height: 50px;

            border:
                5px solid
                #1e293b;

            border-top:
                5px solid
                #38bdf8;

            border-radius: 50%;

            animation:
                spin 1s linear infinite;
        }


        @keyframes spin {

            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }


        .loading-text {

            margin-top: 20px;

            font-weight: 600;

            color: #38bdf8;

            font-size: 16px;
        }


        .file-selected {

            margin-top: 10px;

            color: #38bdf8;

            font-size: 13px;

            font-weight: 600;
        }

    </style>

</head>


<body>


<div class="upload-card">


    <!-- LOADING -->

    <div
        class="loading-overlay"
        id="loading-box"
    >

        <div class="spinner"></div>

        <div
            class="loading-text"
            id="status-text"
        >
            AI Scanning Document...
        </div>

    </div>


    <h1>
        AI Document Secure Upload
    </h1>


    <p>
        Please upload a clear scanned image of your
        Aadhaar for real-time verification.
    </p>


    <form
        method="POST"
        action=""
        id="main-form"
    >


        <div class="file-box">


            <span
                style="
                    color:#38bdf8;
                    font-weight:600;
                "
                id="browse-label"
            >
                Click to browse files
            </span>


            <div
                style="
                    font-size:12px;
                    color:#64748b;
                    margin-top:5px;
                "
            >
                Supports: JPG, JPEG, PNG
            </div>


            <input
                type="file"
                id="file-input"
                name="document_file"
                accept="image/jpeg,image/png"
                onchange="displayFileName()"
                required
            >


            <div
                class="file-selected"
                id="file-name-display"
            ></div>


        </div>


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


        <div
            style="
                display:flex;
                gap:15px;
                width:100%;
            "
        >


            <button
                type="button"
                onclick="history.back()"
                class="btn-back"
            >
                Back
            </button>


            <button
                type="button"
                class="btn-submit"
                onclick="startLiveOCR()"
            >
                Upload & Verify Live
            </button>


        </div>


    </form>


</div>


<script>

/* ==========================================
   SHOW SELECTED FILE
   ========================================== */

function displayFileName() {

    const input =
        document.getElementById('file-input');

    const display =
        document.getElementById(
            'file-name-display'
        );

    if (input.files.length > 0) {

        display.textContent =
            "Selected: " +
            input.files[0].name;
    }
}


/* ==========================================
   TESSERACT.JS OCR
   ========================================== */

async function startLiveOCR() {

    const input =
        document.getElementById('file-input');


    if (input.files.length === 0) {

        alert(
            "Please select an Aadhaar card image first!"
        );

        return;
    }


    const loadingBox =
        document.getElementById(
            'loading-box'
        );

    const statusText =
        document.getElementById(
            'status-text'
        );


    loadingBox.style.display = 'flex';


    try {

        const file =
            input.files[0];


        statusText.textContent =
            "Loading AI OCR Engine...";


        /*
         * Tesseract.js runs OCR on the
         * uploaded Aadhaar image.
         */

        const result =
            await Tesseract.recognize(
                file,
                'eng',
                {
                    logger: function (message) {

                        if (
                            message.status ===
                            'recognizing text'
                        ) {

                            const progress =
                                Math.floor(
                                    message.progress * 100
                                );

                            statusText.textContent =
                                "Analyzing Aadhaar: " +
                                progress +
                                "%";
                        }

                    }
                }
            );


        const text =
            result.data.text;


        console.log(
            "OCR RESULT:",
            text
        );


        statusText.textContent =
            "Extracting Aadhaar Details...";


        /*
         * Store OCR result
         * for PHP server.
         */

        document.getElementById(
            'ocr-hidden-input'
        ).value = text;


        /* ======================================
           NAME DETECTION
           ====================================== */

        let extractedName = "";


        const lines =
            text.split(/\r?\n/);


        for (
            let i = 0;
            i < lines.length;
            i++
        ) {

            const current =
                lines[i]
                .trim()
                .toUpperCase();


            if (
                current.includes(
                    "GOVERNMENT"
                ) ||
                current.includes(
                    "INDIA"
                )
            ) {

                if (
                    lines[i + 1] &&
                    lines[i + 1]
                        .trim()
                        .length > 3
                ) {

                    extractedName =
                        lines[i + 1].trim();

                    break;
                }
            }
        }


        document.getElementById(
            'js-name-input'
        ).value =
            extractedName;


        statusText.textContent =
            "Verification Processing...";


        /*
         * Send OCR result to PHP.
         */

        document
            .getElementById('main-form')
            .submit();

    }

    catch (error) {

        console.error(
            "OCR ERROR:",
            error
        );


        loadingBox.style.display =
            'none';


        alert(
            "Tesseract OCR failed.\n\n" +
            error.message
        );
    }

}

</script>


</body>

</html>
