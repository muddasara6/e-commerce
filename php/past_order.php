<?php
// Connect to MongoDB and select database
$mongoClient = new MongoClient();

$db = $mongoClient->ecommerce;

// Find all orders
$orders = $db->orders->find();

// Output the data into table
echo '<table id="basket_list">
    <tr><th>Product Image</th><th>Product Name</th><th>Price</th><th>Count</th></tr>';
foreach ($orders as $item) {
    echo '<tr>';
    echo '<td class="image_column"><img class="basket_img" src="' . $item['image'] . '"></td>';
    echo '<td>' . $item['name'] . '</td>';
    echo '<td class="totalPrice">£' . $item['price'] . '</td>';
    echo '<td>' . $item['count'] . '</td>';
    echo '</tr>';
}
echo '</table>';

// Close the connection
$mongoClient->close();
?>


    
    