<?php
// Connect to MongoDB and select database
$mongoClient = new MongoClient();

$db = $mongoClient->ecommerce;

// Find all products
$products = $db->products->find();

// Iterate over arrays to get the data from MongoDB and display on the page
foreach ($products as $document) {
    // Display price of the product
    echo '<div class="product_item" data-price-item="' . $document["price"] . '">';
    echo '<div id="cate_row1">';
    // Display the image of the product
    echo '<img class="categories_img" src="' . $document["image"] . '">';
    echo '<div id="position_bottom">';
    echo '<hr class="hor_line_categories">';
    // Display the product name
    echo '<p class="product_name">' . $document["name"] . '</p>';
    echo '<p class="price-item">£' . $document["price"] . '</p>';
    // Display the Product ID from the database
    echo '<button class="basket_item style_basket" onclick=\'addToBasket("' . $document["_id"] . '", "' . $document["image"] .  '", "' . $document["name"] .  '", "' . $document["price"] .  '", 1)\'>Add to Basket</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

// Close the connection
$mongoClient->close();
?>