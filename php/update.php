<?php
// Connect to database
$mongoClient = new MongoClient();

// Select a database
$db = $mongoClient->ecommerce;

// Extract the customer details 
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$deliveryaddress = filter_input(INPUT_POST, 'deliveryaddress', FILTER_SANITIZE_STRING);
$postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);
$emailaddress = filter_input(INPUT_POST, 'emailaddress', FILTER_SANITIZE_STRING);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// If statement to validate if a data is missing, and output the result
if($name != "" && $deliveryaddress != "" && $postcode != "" && $emailaddress != "" && $username != "" && $password != "") { 
    // Construct PHP array with data
    $customerData = [
        "name" => $name,  
        "deliveryaddress" => $deliveryaddress, 
        "postcode" => $postcode, 
        "emailaddress" => $emailaddress, 
        "username" => $username, 
        "password" => $password
    ];
    // Save the product in the database - it will overwrite the data for the product with this ID
    $returnVal = $db->customers->save($customerData);
    // Success message
    echo "Successfully updated!";
}
else {
    // Error message
    echo 'Please fill in all required fields.';
}
    
// Close the connection
$mongoClient->close();
?>