from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    page = browser.new_page(viewport={"width": 1920, "height": 1080})
    page.goto(
        "http://predict.local/it", wait_for_load_state="networkidle", timeout=30000
    )
    page.screenshot(
        path="/var/www/_bases/base_predict_fila5/laravel/Themes/TwentyOne/docs/screenshots/homepage-2026-03-19.png",
        full_page=True,
    )
    browser.close()
