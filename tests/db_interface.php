<?php
// Adding customer into the database
function addCustomer($name, $username, $password) {
    // Connect to database
    $mongoClient = new MongoClient();

    // Select a database
    $db = $mongoClient->ecommerce;

    // Select a collection 
    $collection = $db->customers;
    
    // Convert to PHP array
    $dataArray = [
        "name" => $name,
        "username" => $username, 
        "password" => $password
    ];
    
    // Add the customer to the database
    $returnVal = $collection->insert($dataArray);
    
    // Close the connection
    $mongoClient->close();
    
    // Echo result back to user
    if($returnVal['ok'] == 1) {
        return 'ok';
    }
    return 'Error adding customer';
}

function editCustomer($name, $username, $password) {
    // Connect to database
    $mongoClient = new MongoClient();

    // Select a database
    $db = $mongoClient->ecommerce;

    // Select a collection 
    $collection = $db->customers;
    
    // Convert to PHP array
    $saveArray = [
        "name" => $name,
        "username" => $username, 
        "password" => $password
    ];
    
    // Add the customer to the database
    $returnVal = $collection->save($saveArray);
    
    // Close the connection
    $mongoClient->close();
    
    // Echo result back to user
    if($returnVal['ok'] == 1) {
        return 'ok';
    }
    return 'Error adding customer';
}

// List all customers in the database
function listCustomer($name, $username, $password) {
    // Connect to database
    $mongoClient = new MongoClient();

    // Select a database
    $db = $mongoClient->ecommerce;
    
    // Create a PHP array with our search criteria
    $listCriteria = [
        "name" => $name,
        "username" => $username, 
        "password" => $password
    ];
    
    // Find all of the customers that match this criteria
    $cursor = $db->customers->find($listCriteria);
    
    // Close the connection
    $mongoClient->close();
    
    // Return true if we have found a customer
    if($cursor->count() > 0) {
        return true;
    }
    return false;
}
?>