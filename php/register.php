<?php
// Connect to database
$connection = new MongoClient();

// Select a database
$db = $connection->ecommerce;

// Select a collection 
$collection = $db->customers;

// Extract the data that was sent to the server
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$deliveryaddress = filter_input(INPUT_POST, 'deliveryaddress', FILTER_SANITIZE_STRING);
$postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);
$emailaddress = filter_input(INPUT_POST, 'emailaddress', FILTER_SANITIZE_STRING);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// If statement to validate if a data is missing, and output the result
if($name != "" && $deliveryaddress != "" && $postcode != "" && $emailaddress != "" && $username != "" && $password != "") { 
    // Convert to PHP array
    $customerData = [
        "name" => $name,  
        "deliveryaddress" => $deliveryaddress, 
        "postcode" => $postcode, 
        "emailaddress" => $emailaddress, 
        "username" => $username, 
        "password" => $password
    ];
    // Add the customer to the database
    $returnVal = $collection->insert($customerData);
    // Output message confirming registration
    echo "Successfully registered! -> <a href=\"sign_in.html\">Sign in</a>";
}
else {
    // Error message
    echo 'Please fill in all required fields.';
}

// Close the connection
$connection->close();
?>