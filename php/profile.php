<?php
require_once '../vendor/autoload.php'; 

$databaseConnection = new MongoDB\Client; 
$myDatabase = $databaseConnection->mydatabase;

$userCollection = $myDatabase->users;

// Retrieve form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$dob = $_POST['dob'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';

// Prepare data to insert into the database
$data = array(
    "name" => $name,
    "email" => $email,
    "dob" => $dob,
    "phone" => $phone,
    "address" => $address
);

// Insert data into MongoDB users collection
$insert = $userCollection->insertOne($data);

// Check if insertion was successful
if ($insert->getInsertedCount() > 0) {
    echo "Profile updated successfully";
} else {
    echo "Failed to update profile";
}
?>
