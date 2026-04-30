<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';
$user_name  = htmlspecialchars($_SESSION['user_name']);
$user_email = htmlspecialchars($_SESSION['user_email']);

$stmt = $conn->prepare("SELECT * FROM registrations WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();


$last_login = isset($_COOKIE['last_login']) ? htmlspecialchars($_COOKIE['last_login']) : "First login";


$track_badges = [
    'creative-coding'  => ['🎨', 'Creative Coding',  '#f59e0b'],
    'ui-ux'            => ['✏️', 'UI/UX Design',      '#8b5cf6'],
    'ai-fundamentals'  => ['🤖', 'AI Fundamentals',   '#06b6d4'],
    'foundations'      => ['📐', 'Foundations',        '#10b981'],
];

$role_icons = [
    'student'      => '🎓',
    'parent'       => '👨‍👩‍👦',
    'teacher'      => '📚',
    'professional' => '💼',
];

$track     = $user['track'] ?? 'foundations';
$badge     = $track_badges[$track] ?? $track_badges['foundations'];
$role_icon = $role_icons[$user['role'] ?? 'student'] ?? '👤';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — MedLearn Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0a0f1e;
            --panel: #111827;
            --panel2: #0f1d30;
            --border: #1e2d45;
            --accent: #00d4ff;
            --accent2: #7c3aed;
            --text: #e2e8f0;
            --muted: #64748b;
            --success: #34d399;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse at 0% 0%, rgba(0,212,255,0.05) 0%, transparent 45%),
                radial-gradient(ellipse at 100% 100%, rgba(124,58,237,0.07) 0%, transparent 45%);
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 64px;
            background: rgba(17,24,39,0.9);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-right { display: flex; align-items: center; gap: 16px; }

        .nav-user {
            font-size: 0.875rem;
            color: var(--muted);
        }
        .nav-user span { color: var(--text); font-weight: 500; }

        .logout-btn {
            padding: 8px 18px;
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.3);
            border-radius: 8px;
            color: #f87171;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .logout-btn:hover { background: rgba(248,113,113,0.2); }

        main {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .welcome-banner {
            background: linear-gradient(135deg, rgba(0,212,255,0.08), rgba(124,58,237,0.12));
            border: 1px solid rgba(0,212,255,0.15);
            border-radius: 16px;
            padding: 36px 40px;
            margin-bottom: 28px;
            animation: fadeUp 0.4s ease both;
            position: relative;
            overflow: hidden;
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,212,255,0.08), transparent 70%);
        }

        .welcome-banner h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.4rem;
            letter-spacing: 2px;
            margin-bottom: 6px;
            position: relative;
        }
        .welcome-banner h1 span {
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .welcome-banner p { color: var(--muted); font-size: 0.9rem; position: relative; }

        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px;
            animation: fadeUp 0.5s ease both;
        }
        .card:nth-child(2) { animation-delay: 0.05s; }
        .card:nth-child(3) { animation-delay: 0.1s; }
        .card:nth-child(4) { animation-delay: 0.15s; }

        .card-label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .card-value {
            font-size: 1.05rem;
            font-weight: 500;
            color: var(--text);
            word-break: break-word;
        }

        .track-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 600;
            border: 1px solid;
        }

        .session-card {
            background: var(--panel2);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px 28px;
            animation: fadeUp 0.5s ease 0.2s both;
        }

        .session-card h3 {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .key { color: var(--muted); }
        .info-row .val { color: var(--text); font-weight: 500; text-align: right; }

        .dot {
            display: inline-block;
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 8px var(--success);
            margin-right: 6px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        @media (max-width: 640px) {
            nav { padding: 0 16px; }
            .welcome-banner { padding: 24px 20px; }
            .welcome-banner h1 { font-size: 1.8rem; }
            .grid, .grid-3 { grid-template-columns: 1fr; }
            .card { padding: 20px; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-logo">MedLearn</div>
    <div class="nav-right">
        <div class="nav-user">Signed in as <span><?= $user_name ?></span></div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</nav>

<main>
    <div class="welcome-banner">
        <h1>Welcome back, <span><?= $user_name ?></span> <?= $role_icon ?></h1>
        <p>You're successfully logged in. Your session is active and secure.</p>
    </div>

    <div class="grid">
        <div class="card">
            <div class="card-label">Your Track</div>
            <div class="track-badge" style="color:<?= $badge[2] ?>; border-color:<?= $badge[2] ?>40; background:<?= $badge[2] ?>15;">
                <?= $badge[0] ?> <?= $badge[1] ?>
            </div>
        </div>
        <div class="card">
            <div class="card-label">Role</div>
            <div class="card-value"><?= $role_icon ?> <?= ucfirst($user['role'] ?? '') ?></div>
        </div>
    </div>

    <div class="grid-3">
        <div class="card">
            <div class="card-label">Email</div>
            <div class="card-value" style="font-size:0.9rem;"><?= $user_email ?></div>
        </div>
        <div class="card">
            <div class="card-label">Start Date</div>
            <div class="card-value"><?= date('M d, Y', strtotime($user['start_date'] ?? 'now')) ?></div>
        </div>
        <div class="card">
            <div class="card-label">Member Since</div>
            <div class="card-value"><?= date('M Y', strtotime($user['created_at'] ?? 'now')) ?></div>
        </div>
    </div>

    <div class="session-card">
        <h3>Session & Cookie Info</h3>
        <div class="info-row">
            <span class="key">Session Status</span>
            <span class="val"><span class="dot"></span>Active</span>
        </div>
        <div class="info-row">
            <span class="key">Session ID</span>
            <span class="val" style="font-size:0.8rem;font-family:monospace;"><?= substr(session_id(), 0, 20) ?>...</span>
        </div>
        <div class="info-row">
            <span class="key">Last Login (Cookie)</span>
            <span class="val"><?= $last_login ?></span>
        </div>
        <div class="info-row">
            <span class="key">Email Cookie</span>
            <span class="val"><?= isset($_COOKIE['user_email']) ? '✓ Saved' : '✗ Not saved' ?></span>
        </div>
        <?php if (!empty($user['notes'])): ?>
        <div class="info-row">
            <span class="key">Notes</span>
            <span class="val"><?= htmlspecialchars($user['notes']) ?></span>
        </div>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
