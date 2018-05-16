<?php
// Connect to MongoDB
$mongoClient = new MongoClient();

// Select a database
$db = $mongoClient->ecommerce;

// Extract name from POST data
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

// Build PHP array with remove criteria 
$remCriteria = [
    "name" => $name
];

// Delete the customer document
$returnVal = $db->customers->remove($remCriteria);

// Output message
echo 'Successfully deleted';

// Close the connection
$mongoClient->close();
?>