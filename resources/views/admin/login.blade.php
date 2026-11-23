<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Richwell Hospital</title>
    <style>
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.82), rgba(49, 46, 129, 0.72)), url('/assets/richwell%20hospital.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.97);
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 20px 42px rgba(109, 40, 217, 0.25), 0 8px 20px rgba(15, 23, 42, 0.3);
            width: 100%;
            max-width: 400px;
            border-top: 6px solid #1e3a8a;
            border-left: 1px solid #c7d2fe;
            border-right: 1px solid #c7d2fe;
            border-bottom: 1px solid #c7d2fe;
            animation: fadeIn 0.35s ease forwards;
            box-sizing: border-box;
        }
        .brand-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .brand-badge {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1e3a8a, #4338ca);
            border: 1.5px solid #c4b5fd;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            box-sizing: border-box;
        }
        .brand-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        h2 { color: #1e3a8a; margin: 0; text-align: center; font-size: 20px; }
        .subtitle { text-align: center; color: #64748b; font-size: 12.5px; margin: 4px 0 18px 0; }
        .form-group { margin-bottom: 14px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; color: #1e3a8a; }
        input {
            width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1;
            border-radius: 8px; box-sizing: border-box; font-size: 14px; background: #fff;
        }
        input:focus { border-color: #1e3a8a; outline: none; box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.14); }
        button {
            background: linear-gradient(135deg, #1e3a8a, #3730a3);
            color: white; padding: 12px; border: none; border-radius: 8px;
            cursor: pointer; width: 100%; font-size: 15px; font-weight: bold;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        button:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(30, 58, 138, 0.36); }
        .error {
            background: #fef2f2; color: #b91c1c; padding: 10px;
            border-radius: 8px; margin-bottom: 14px; font-size: 13px;
            border: 1px solid #fecaca; text-align: center;
        }
        .back-link { display: block; text-align: center; margin-top: 16px; color: #1e3a8a; text-decoration: none; font-size: 13px; font-weight: 600; }
        .back-link:hover { color: #6d28d9; text-decoration: underline; }
    </style>
</head>
<body>
<div class="login-box">
    <div class="brand-row">
        <div class="brand-badge">
            <img src="/assets/logo.png" alt="Richwell Logo">
        </div>
        <h2>Richwell Hospital</h2>
    </div>
    <div class="subtitle">Outpatient Administrative Portal</div>
    @if ($error)
        <div class="error">{{ $error }}</div>
    @endif
    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Admin Username:</label>
            <input type="text" name="username" required autofocus>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login to Admin Panel</button>
    </form>
    <a href="{{ route('portal.index') }}" class="back-link">&larr; Back to Patient Reservation Portal</a>
</div>
</body>
</html>