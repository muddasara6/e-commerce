<?php  
// Start session management
session_start();

// Get name and address strings - need to filter input to reduce chances of SQL injection etc.
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);    

// Connect to MongoDB and select database
$mongoClient = new MongoClient();
$db = $mongoClient->ecommerce;

// Create a PHP array with our search criteria
$findCriteria = [
    "username" => $username,
    "password" => $password
];
    
// Find all of the customers that match  this criteria
$cursor = $db->customers->find($findCriteria);

if ($cursor->count() > 0) { 
    // Start session for this user
    $_SESSION['loggedInUser'] = $username;
    // Inform web page that login is successful
    echo 'Successfully logged in!';
}
// If there is identical data, and error message will be displayed
else if($cursor->count() > 1){
    echo 'Database error: Multiple customers have the same details.';
}
else {
    // Error message
    echo 'Incorrect details. Try again.';
}
    
// Close the connection
$mongoClient->close();
?>   