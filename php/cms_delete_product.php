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

// Delete the product document
$returnVal = $db->products->remove($remCriteria);

// Output the results
echo 'Successfully deleted!';

// Close the connection
$mongoClient->close();
?>