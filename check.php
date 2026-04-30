<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

if($username=="RoGuE" && $password=="password")
{
    $_SESSION['user'] = $username;
    header("Location: dashboard.php");
    exit(); 
}
else
{
    echo "<h3>Invalid Login</h3>";
    echo "<a href='login.php'>Try Again</a>";
}
?>