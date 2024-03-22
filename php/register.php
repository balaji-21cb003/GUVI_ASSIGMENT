<?php
require '../vendor/autoload.php';

use Predis\Client;

session_start();

$conn = mysqli_connect("localhost", "root", "", "test");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$name = $_POST["name"];
$email = $_POST["email"];
$password1 = $_POST["password1"];
$password2 = $_POST["password2"];

if (empty($name) || empty($email) || empty($password1)) {
    echo "Please Fill Out The Form!";
    exit;
}
if ($password1 != $password2) {
    echo "Passwords do not match";
    exit;
}

// Prepare SQL statements to prevent SQL injection
$stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE name = ?");
mysqli_stmt_bind_param($stmt, "s", $name);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    echo "Username is already taken";
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    echo "Email is already taken";
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO user (name, email, password1, password2) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $password1, $password2);
if (mysqli_stmt_execute($stmt)) {
    echo "Registration Successful";

    // Create a new Predis client instance
    $client = new Client([
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => 6379,
    ]);

    // Store user data in Redis
    $userData = [
        'name' => $name,
        'email' => $email,
        'password1' => $password1,
        'password2' => $password2,
    ];
    $client->hmset("user:$email", $userData);
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
