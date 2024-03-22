<?php
session_start();

require_once 'vendor/autoload.php';

use Predis\Client;

$conn = mysqli_connect("localhost", "root", "", "test");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$name = $_POST["name"];
$password1 = $_POST["password1"];

if (empty($name) || empty($password1)) {
    echo "Please Fill Out The Form!";
    exit;
}

$user = mysqli_query($conn, "SELECT * FROM user WHERE name = '$name' LIMIT 1");

if (mysqli_num_rows($user) > 0) {
    $row = mysqli_fetch_assoc($user);

    if ($password1 == $row['password1']) {
        // Redis configuration
        $redis = new Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
        ]);

        // Set session data in Redis
        $sessionId = session_id();
        $redisKey = "session:$sessionId";
        $redisData = [
            'login' => true,
            'id' => $row["id"],
            'name' => $name
        ];
        $redis->hmset($redisKey, $redisData);

        // Expire session data in Redis after 1 hour (adjust as needed)
        $redis->expire($redisKey, 3600);

        // Set session data in PHP
        $_SESSION["login"] = true;
        $_SESSION["id"] = $row["id"];
        $_SESSION["name"] = $name;

        echo "Login Successful";
        exit();
    } else {
        echo "Wrong Password";
        exit;
    }
} else {
    echo "User not registered";
    exit;
}

mysqli_close($conn);
?>
