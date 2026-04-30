<?php
$host = "localhost";
$user = "root";
$password = "";
$dbName = "hospital_management_db";

$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

mysqli_select_db($conn, $dbName);


$registrationTable = "CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'parent', 'teacher', 'professional') NOT NULL,
    track ENUM('creative-coding', 'ui-ux', 'ai-fundamentals', 'foundations') NOT NULL,
    start_date DATE NOT NULL,
    notes TEXT,
    terms_accepted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($registrationTable) !== TRUE) {
    die("Error creating registrations table: " . $conn->error);
}
?>
