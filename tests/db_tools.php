<?php
function customerExists($username) {
    // Connect to database
    $mongoClient = new MongoClient();

    // Select a database
    $db = $mongoClient->ecommerce;
    
    // Create a PHP array with our search criteria
    $findCriteria = [
        "username" => $username
    ];
    
    // Find all of the customers that match this criteria
    $cursor = $db->customers->find($findCriteria);
    
    // Close the connection
    $mongoClient->close();
    
    // Return true if we have found a customer
    if($cursor->count() > 0) {
        return true;
    }
    return false;
}

function deleteCustomer($username) {
    // Connect to database
    $mongoClient = new MongoClient();

    // Select a database
    $db = $mongoClient->ecommerce;
    
    // Create a PHP array with our search criteria
    $remCriteria = [
        "username" => $username
    ];
    
    // Delete the customer document
    $db->customers->remove($remCriteria);
    
    // Close the connection
    $mongoClient->close();
}
?>