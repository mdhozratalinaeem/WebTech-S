<?php
session_start();

if(!isset($_SESSION['user']))
{
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>
body{
    font-family: Arial;
    margin:0;
    background:#f4f6f9;
}

.navbar{
    background:#007BFF;
    color:white;
    padding:15px;
}

.container{
    padding:30px;
}

.card{
    background:white;
    padding:20px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
    border-radius:8px;
}

.logout{
    float:right;
    color:white;
    text-decoration:none;
}
</style>

</head>
<body>

<div class="navbar">
Welcome <?php echo $user; ?>
<a class="logout" href="logout.php">Logout</a>
</div>

<div class="container">
<div class="card">
<h2>Dashboard</h2>
<p>You are logged in successfully using PHP session.</p>
<p>This page is protected and requires login.</p>
</div>
</div>

</body>
</html>