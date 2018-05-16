<?php
// Connect to MongoDB
$mongoClient = new MongoClient();

// Select a database
$db = $mongoClient->ecommerce;

// Extract the data that was sent to the server
$search_string = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);

// Create a PHP array with our search criteria
$findCriteria = [
    '$text' => ['$search' => $search_string] 
 ];

// Find all of the products that match this criteria
$cursor = $db->products->find($findCriteria);

// Output the results
foreach ($cursor as $cust) {
    echo '<div id="recommend_images">';
    echo '<img class="recommend_img" src="' . $cust['image'] . '">';
    echo '</div>';
}

// Close the connection
$mongoClient->close();
?>