<?php
require_once 'vendor/autoload.php'; 

use MongoDB\Client as MongoDBClient;

// Connect to MongoDB
$databaseConnection = new MongoDBClient; 
$myDatabase = $databaseConnection->mydatabase;

$userCollection = $myDatabase->users;

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
} else {
    echo "Failed to update profile";
}
?>
