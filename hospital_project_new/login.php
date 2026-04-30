<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'config.php';

$error = "";
$saved_email = isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password FROM registrations WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email']= $email;

                // Cookie: remember email for 30 days
                if ($remember) {
                    setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), '/');
                } else {
                    setcookie('user_email', '', time() - 3600, '/');
                }

                // Cookie: track last login time
                setcookie('last_login', date('Y-m-d H:i:s'), time() + (30 * 24 * 60 * 60), '/');

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MedLearn Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0a0f1e;
            --panel: #111827;
            --border: #1e2d45;
            --accent: #00d4ff;
            --accent2: #7c3aed;
            --text: #e2e8f0;
            --muted: #64748b;
            --error: #f87171;
            --input-bg: #0d1526;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background-image:
                radial-gradient(ellipse at 15% 20%, rgba(0,212,255,0.07) 0%, transparent 50%),
                radial-gradient(ellipse at 85% 80%, rgba(124,58,237,0.09) 0%, transparent 50%);
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 48px 40px;
            box-shadow: 0 0 60px rgba(0,212,255,0.05);
            animation: fadeUp 0.5s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.8rem;
            letter-spacing: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 4px;
        }

        .subtitle { color: var(--muted); font-size: 0.875rem; margin-bottom: 36px; }

        .alert-error {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 20px;
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.3);
            color: var(--error);
        }

        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 7px;
        }
        .field input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 14px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .field input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            color: var(--muted);
        }
        .remember-row input[type="checkbox"] { width: 15px; height: 15px; accent-color: var(--accent); }

        .btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border: none;
            border-radius: 8px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.04em;
            transition: opacity 0.2s, transform 0.2s;
        }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.875rem;
            color: var(--muted);
        }
        .register-link a { color: var(--accent); text-decoration: none; font-weight: 500; }

        .cookie-hint {
            font-size: 0.75rem;
            color: var(--accent);
            margin-top: 5px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">MedLearn</div>
    <p class="subtitle">Sign in to your account</p>

    <?php if ($error): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="field">
            <label>Email Address</label>
            <input type="email" name="email" value="<?= $saved_email ?>" placeholder="you@example.com" required>
            <?php if ($saved_email): ?>
                <p class="cookie-hint">✓ Email remembered from your last visit</p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Your password" required>
        </div>

        <div class="remember-row">
            <input type="checkbox" name="remember" id="remember" <?= $saved_email ? 'checked' : '' ?>>
            <label for="remember">Remember my email for next time</label>
        </div>

        <button type="submit" class="btn">Sign In</button>
    </form>

    <p class="register-link">Don't have an account? <a href="register.php">Register now</a></p>
</div>
</body>
</html>
