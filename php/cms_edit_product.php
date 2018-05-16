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

// Find all of the customers that match this criteria
$cursor = $db->products->find($findCriteria);

// Output the results
foreach ($cursor as $cust) {
    echo '<h1 class="product_title">' . $cust['name'] . '</h1>';
    echo '<label class="product_label">Image:<br>';
    echo '<input id="image" name="image" value="' . $cust['image'] . '"  type="text">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="product_label">Product Name:<br>';
    echo '<input id="name" name="name" value="' . $cust['name'] . '" type="text">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="product_label">Accessories:<br>';
    echo '<input id="accessories" name="accessories" value="' . $cust['accessories'] . '" type="text">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="product_label">Brand:<br>';
    echo '<input id="brand" name="brand" value="' . $cust['brand'] . '" type="text">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="product_label">Color:<br>';
    echo '<input id="color" name="color" value="' . $cust['color'] . '" type="text">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="product_label">Price<br>';
    echo '<input id="price" name="price" value="' . $cust['price'] . '" type="text">';
    echo '</label>';
    echo '<input class="save_button" onclick="editProduct()" type="submit" value="SAVE">';
    echo '<p id="edit_product_message"></p>';
    echo '<br>';
}
    
// Close the connection
$mongoClient->close();
?>