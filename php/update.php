<?php
require_once 'vendor/autoload.php'; 

use MongoDB\Client as MongoDBClient;
use Predis\Client as PredisClient;

// Connect to MongoDB
$databaseConnection = new MongoDBClient; 
$myDatabase = $databaseConnection->mydatabase;

$userCollection = $myDatabase->users;

// Create a Predis client instance
$predisClient = new PredisClient([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379,
]);

// Retrieve form data
$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$dob = $_POST['dob'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';

// Prepare update data
$updateData = [
    '$set' => [
        "name" => $name,
        "email" => $email,
        "dob" => $dob,
        "phone" => $phone,
        "address" => $address
    ]
];

// Update data in MongoDB users collection
$updateResult = $userCollection->updateOne(['_id' => new MongoDB\BSON\ObjectID($id)], $updateData);

// Check if update was successful
if ($updateResult->getModifiedCount() > 0) {
    echo "Profile updated successfully";
    
    // Store user data in Redis
    $userData = [
        'name' => $name,
        'email' => $email,
        'dob' => $dob,
        'phone' => $phone,
        'address' => $address,
    ];
    $predisClient->hmset("user:$id", $userData);
} else {
    echo "Failed to update profile";
}
?>
