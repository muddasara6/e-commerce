<?php
// Connect to database
$mongoClient = new MongoClient();

// Select a database
$db = $mongoClient->ecommerce;

// Select a collection 
$collection = $db->products;

// Extract the product data 
$image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$accessories = filter_input(INPUT_POST, 'accessories', FILTER_SANITIZE_STRING);
$brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_STRING);
$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
$color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);

// If statement to validate if a data is missing, and output the result
if($image != "" && $name != "" && $accessories != "" && $brand != "" && $price != "" && $color != "") { 
    // Convert to PHP array
    $productData = [
        "image" => $image,  
        "name" => $name, 
        "accessories" => $accessories, 
        "brand" => $brand, 
        "price" => $price,
        "color" => $color
    ];
    // Add the product to the database
    $returnVal = $collection->insert($productData);
    // Output message confirming registration
    echo "Product successfully added!";
}
else {
    // Error message
    echo 'Please fill in all fields.';
}

// Close the connection
$mongoClient->close();
?>