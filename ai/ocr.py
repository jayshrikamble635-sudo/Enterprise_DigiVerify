import sys
import pytesseract
from PIL import Image

# Tesseract Path
pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"

if len(sys.argv) < 2:
    print("ERROR: No image file provided")
    sys.exit()

image_path = sys.argv[1]

try:
    img = Image.open(image_path)

    text = pytesseract.image_to_string(img, lang="eng")

    print(text)

except Exception as e:
    print("ERROR:", e)