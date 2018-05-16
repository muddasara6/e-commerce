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

// Find all of the products that match  this criteria
$cursor = $db->products->find($findCriteria);

// Output the results
if($cursor->count() > 0) {
    foreach ($cursor as $cust) {
        echo '<div class="product_item" data-price-item="18.99">';
        echo '<div id="cate_row1">';
        echo '<img class="categories_img" src="' . $cust['image'] . '">';
        echo '<hr class="hor_line_categories">';
        echo '<p class="product_name">' . $cust['name'] . '</p>';
        echo '<p class="price-item"> £' . $cust['price'] . '</p>';
        echo '<button class="basket_item style_basket">ADD TO BASKET</button>';
        echo '</div>';
        echo '</div>';
    }
}
else {
    // No products found
    echo '<p id="search_error">No search found.</p>';
}

// Close the connection
$mongoClient->close();
?>