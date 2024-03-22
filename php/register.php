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
    echo "Wrong password";
    exit;
}
$user = mysqli_query($conn, "SELECT * FROM userdata WHERE name = '$name'");
if (mysqli_num_rows($user) > 0) {
    echo "username has already taken";
    exit;
}
$user = mysqli_query($conn, "SELECT * FROM userdata WHERE email = '$email'");
if (mysqli_num_rows($user) > 0) {
    echo "email has already taken";
    exit;
}

$query = "INSERT INTO userdata (name, email, password1, password2) VALUES ('$name', '$email', '$password1', '$password2')";
if (mysqli_query($conn, $query)) {
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
} 
// else {
//     echo "Error: " . $query . "<br>" . mysqli_error($conn);
// }

mysqli_close($conn);

// LOGIN
?>
