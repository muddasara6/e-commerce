<?php
// Connect to database
$mongoClient = new MongoClient();

// Select a database
$db = $mongoClient->ecommerce;

// Extract the product details 
$image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$accessories = filter_input(INPUT_POST, 'accessories', FILTER_SANITIZE_STRING);
$brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_STRING);
$color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);
$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);

// Construct PHP array with data
$productData = [
    "image" => $image,  
    "name" => $name, 
    "accessories" => $accessories, 
    "brand" => $brand, 
    "color" => $color, 
    "price" => $price
];

// Save the product in the database
$returnVal = $db->products->save($productData);

// Success message
echo 'Successfully edited!';

// Close the connection
$mongoClient->close();
?>