<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Ristorante Mobile</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        .app-container { min-height: 100vh; display: flex; flex-direction: column; }
        .header { background: #1976D2; color: white; padding: 16px; text-align: center; font-weight: 600; }
        .content { flex: 1; padding: 16px; overflow-y: auto; }
        .nav { background: white; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-around; padding: 8px 0; }
        .nav-item { text-align: center; padding: 8px; color: #666; font-size: 12px; }
        .nav-item.active { color: #1976D2; }
        .card { background: white; border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { background: #1976D2; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-size: 16px; width: 100%; }
        .btn-secondary { background: #e0e0e0; color: #333; }
        .table-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
        .table-card { background: white; border-radius: 8px; padding: 12px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .table-card.available { border: 2px solid #4CAF50; }
        .table-card.reserved { border: 2px solid #FF9800; }
        .table-card.occupied { border: 2px solid #f44336; }
        .table-card.offline { border: 2px solid #9E9E9E; }
    </style>
</head>
<body>
    <div id="app">
        <div class="app-container">
            <div class="header">Cameriere - Ordini</div>
            <div class="content" id="main-content">
                Loading...
            </div>
            <div class="nav">
                <div class="nav-item active">Tavoli</div>
                <div class="nav-item">Ordini</div>
                <div class="nav-item">Menu</div>
                <div class="nav-item">Account</div>
            </div>
        </div>
    </div>
    <script>
        // NativePHP offline-first app shell
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>