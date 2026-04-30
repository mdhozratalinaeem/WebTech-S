<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'config.php';

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name   = trim($_POST['full_name']);
    $email       = trim($_POST['email']);
    $phone       = trim($_POST['phone']);
    $password    = $_POST['password'];
    $confirm     = $_POST['confirm_password'];
    $role        = $_POST['role'];
    $track       = $_POST['track'];
    $start_date  = $_POST['start_date'];
    $notes       = trim($_POST['notes']);
    $terms       = isset($_POST['terms']) ? 1 : 0;

    if (empty($full_name))  $errors[] = "Full name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";
    if (empty($role))  $errors[] = "Role is required.";
    if (empty($track)) $errors[] = "Track is required.";
    if (empty($start_date)) $errors[] = "Start date is required.";
    if (!$terms) $errors[] = "You must accept the terms.";

    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM registrations WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "This email is already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO registrations (full_name, email, phone, password, role, track, start_date, notes, terms_accepted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssi", $full_name, $email, $phone, $hashed, $role, $track, $start_date, $notes, $terms);

            if ($stmt->execute()) {
                $success = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — MedLearn Portal</title>
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
            --success: #34d399;
            --input-bg: #0d1526;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 16px;
            background-image:
                radial-gradient(ellipse at 20% 10%, rgba(0,212,255,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 90%, rgba(124,58,237,0.08) 0%, transparent 50%);
        }

        .card {
            width: 100%;
            max-width: 600px;
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
            font-size: 2.4rem;
            letter-spacing: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 4px;
        }

        .subtitle {
            color: var(--muted);
            font-size: 0.875rem;
            margin-bottom: 36px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 20px;
        }
        .alert-error { background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.3); color: var(--error); }
        .alert-error ul { padding-left: 18px; }
        .alert-success { background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.3); color: var(--success); }
        .alert-success a { color: var(--accent); }

        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

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
        .field input,
        .field select,
        .field textarea {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 11px 14px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
        }
        .field select option { background: var(--panel); }
        .field textarea { resize: vertical; min-height: 80px; }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            color: var(--muted);
        }
        .checkbox-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--accent); }

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

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.875rem;
            color: var(--muted);
        }
        .login-link a { color: var(--accent); text-decoration: none; font-weight: 500; }

        @media (max-width: 520px) {
            .card { padding: 32px 20px; }
            .row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">MedLearn</div>
    <p class="subtitle">Create your account to get started</p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error"><ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="row">
            <div class="field">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" placeholder="Enter your full name" required>
            </div>
            <div class="field">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="Enter your phone number">
            </div>
        </div>

        <div class="field">
            <label>Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="you@example.com" required>
        </div>

        <div class="row">
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Min. 6 characters" required>
            </div>
            <div class="field">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Repeat password" required>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>Role</label>
                <select name="role" required>
                    <option value="">Select role...</option>
                    <option value="student"      <?= (($_POST['role'] ?? '') == 'student')       ? 'selected' : '' ?>>Student</option>
                    <option value="parent"       <?= (($_POST['role'] ?? '') == 'parent')        ? 'selected' : '' ?>>Parent</option>
                    <option value="teacher"      <?= (($_POST['role'] ?? '') == 'teacher')       ? 'selected' : '' ?>>Teacher</option>
                    <option value="professional" <?= (($_POST['role'] ?? '') == 'professional')  ? 'selected' : '' ?>>Professional</option>
                </select>
            </div>
            <div class="field">
                <label>Track</label>
                <select name="track" required>
                    <option value="">Select track...</option>
                    <option value="creative-coding"  <?= (($_POST['track'] ?? '') == 'creative-coding')  ? 'selected' : '' ?>>Creative Coding</option>
                    <option value="ui-ux"            <?= (($_POST['track'] ?? '') == 'ui-ux')            ? 'selected' : '' ?>>UI/UX Design</option>
                    <option value="ai-fundamentals"  <?= (($_POST['track'] ?? '') == 'ai-fundamentals')  ? 'selected' : '' ?>>AI Fundamentals</option>
                    <option value="foundations"      <?= (($_POST['track'] ?? '') == 'foundations')      ? 'selected' : '' ?>>Foundations</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label>Start Date</label>
            <input type="date" name="start_date" value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>" required>
        </div>

        <div class="field">
            <label>Notes (optional)</label>
            <textarea name="notes" placeholder="Any additional information..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
        </div>

        <div class="checkbox-row">
            <input type="checkbox" name="terms" id="terms" <?= isset($_POST['terms']) ? 'checked' : '' ?>>
            <label for="terms">I agree to the Terms & Conditions</label>
        </div>

        <button type="submit" class="btn">Create Account</button>
    </form>

    <p class="login-link">Already have an account? <a href="login.php">Sign in</a></p>
</div>
</body>
</html>
