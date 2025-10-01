@php
    $approved = auth()->user()->approved ?? false;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approval</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #18181b;
            color: #f3f4f6;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: linear-gradient(135deg, #232946 60%, #1a1a2e 100%);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
            border: 1px solid #2d3748;
        }
        .card h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #ffd700;
        }
        .card p {
            color: #cbd5e1;
            margin-bottom: 1.2rem;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(90deg, #6366f1 0%, #7c3aed 100%);
            color: #fff;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 8px rgba(99,102,241,0.15);
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1.5rem;
            transition: background 0.2s;
        }
        .btn:hover {
            background: linear-gradient(90deg, #7c3aed 0%, #6366f1 100%);
        }
        .icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: #6366f1;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">&#128274;</div>
        @if(!$approved)
            <h2>Awaiting Approval</h2>
            <p>Your account has been created and is awaiting admin approval.</p>
            <p>Please stand still as you await approval.<br>You'll be notified once your account is approved.</p>
        @else
            <h2>Account Approved!</h2>
            <p>Your account has been approved. You can now access all features of the app.</p>
            <form action="{{ route('dashboard') }}">
                <button type="submit" class="btn">Go to Dashboard</button>
            </form>
        @endif
    </div>
</body>
</html>
