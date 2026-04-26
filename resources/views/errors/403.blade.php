<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Access Denied | {{ config('app.name', 'Partnals System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Tajawal:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --secondary: #06b6d4;
            --bg-dark: #0f172a;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', 'Tajawal', sans-serif;
            background-color: var(--bg-dark);
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated Background */
        .bg-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(15, 23, 42, 0) 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(80px);
            animation: move 20s infinite alternate;
        }

        .bg-glow-2 {
            position: absolute;
            bottom: -100px;
            right: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, rgba(15, 23, 42, 0) 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(100px);
            animation: move-reverse 25s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-20%, -20%); }
            to { transform: translate(20%, 20%); }
        }

        @keyframes move-reverse {
            from { transform: translate(20%, 20%); }
            to { transform: translate(-20%, -20%); }
        }

        /* Error Card */
        .card {
            background: var(--glass);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            padding: 3rem;
            border-radius: 2rem;
            text-align: center;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-wrapper {
            margin-bottom: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 100px;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 50%;
            color: #ef4444;
            border: 2px solid rgba(239, 68, 68, 0.2);
        }

        .error-code {
            font-size: 6rem;
            font-weight: 800;
            background: linear-gradient(to right, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #f8fafc;
        }

        .error-message {
            color: #94a3b8;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-decoration: none;
            border-radius: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4);
            filter: brightness(1.1);
        }

        .btn svg {
            margin: 0 8px;
            transition: transform 0.3s ease;
        }

        .btn:hover svg {
            transform: translateX(app()->getLocale() == 'ar' ? 5px : -5px);
        }

        /* Language specific adjustments */
        [dir="rtl"] .btn svg {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="bg-glow-2"></div>

    <div class="card">
        <div class="icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>

        <div class="error-code">403</div>
        
        <h1 class="error-title">
            {{ app()->getLocale() == 'ar' ? 'وصول غير مسموح' : 'Access Denied' }}
        </h1>
        
        <p class="error-message">
            {{ app()->getLocale() == 'ar' ? 'عذراً، يبدو أنك لا تمتلك الصلاحيات الكافية للوصول إلى هذه الصفحة، أو أن هذه الميزة غير مفعلة حالياً.' : 'Sorry, it seems you do not have sufficient permissions to access this page, or this feature is currently disabled.' }}
        </p>

        <a href="{{ url('/admin/dashboard') }}" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            {{ app()->getLocale() == 'ar' ? 'العودة للوحة التحكم' : 'Return to Dashboard' }}
        </a>
    </div>
</body>
</html>
