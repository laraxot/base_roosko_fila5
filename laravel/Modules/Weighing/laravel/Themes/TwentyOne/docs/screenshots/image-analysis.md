---
title: "image analysis"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "image analysis"
issues: []
discussions: []
status: draft
---

# Image Analysis Guide

## Overview

This guide documents how to analyze screenshots using ImageMagick and Tesseract OCR in this project.

## Tools Available

### ImageMagick
- **Location:** `/usr/bin/convert`, `/usr/bin/identify`
- **Version:** ImageMagick 6.9.12-98 Q16 x86_64

### Tesseract OCR
- **Command:** `tesseract`
- **Install:** `sudo apt-get install tesseract-ocr`
- **Usage:** `tesseract image.png stdout`

## Quick Analysis Commands

### Get Image Info
```bash
identify -verbose /path/to/image.png
```

### Sample Color at Point
```bash
convert /path/to/image.png -format "%[pixel:p{x,y}]" info:
```

### Get Dominant Colors
```bash
convert /path/to/image.png -resize 100x100 -colors 10 -format "%c" histogram:
```

### Extract Text (OCR)
```bash
tesseract /path/to/image.png stdout
```

## Python Analysis Script

```python
import subprocess

def analyze_image(img_path):
    # Get image info
    result = subprocess.run(["identify", "-verbose", img_path], capture_output=True, text=True)
    
    # Sample colors at key points
    points = [("Center", 640, 400), ("Top", 640, 60)]
    for name, x, y in points:
        color = subprocess.run(
            ["convert", img_path, "-format", f"%[pixel:p{{{x},{y}}}]", "info:"],
            capture_output=True, text=True
        )
        print(f"{name}: {color.stdout.strip()}")
    
    # Try OCR
    ocr = subprocess.run(["tesseract", img_path, "stdout"], capture_output=True, text=True)
    print(ocr.stdout[:1000])

analyze_image("/path/to/screenshot.png")
```

## Screenshot Workflow

1. **Capture screenshot:**
   ```bash
   node take_screenshot.js http://url /path/to/screenshot.png
   ```

2. **Analyze colors:**
   ```python
   python3 analyze_colors.py /path/to/screenshot.png
   ```

3. **Create markdown doc:**
   ```bash
   echo "# Analysis" > /path/to/screenshot.md
   ```

## File Locations

- **Screenshot tool:** `/var/www/_bases/base_predict_fila5/take_screenshot.js`
- **Screenshot dir:** `Themes/TwentyOne/docs/screenshots/`
- **Analysis docs:** `Themes/TwentyOne/docs/screenshots/*.md`
