<?php
// Connect to MongoDB and select database
$mongoClient = new MongoClient();

$db = $mongoClient->ecommerce;

// Find all products
$products = $db->products->find();

// Output results onto table
echo '<table id="product_list">';
echo '<tr><th>Product ID</th><th>Image</th><th>Product Name</th><th>Accessories</th><th>Brand</th><th>Color</th><th>Price</th></tr>';
foreach ($products as $document) {
    echo '<tr>';
    echo '<td>' . $document["_id"] . '</td>';
    echo '<td class="img_column"><img class="cms_img" src="' . $document['image'] . '"></td>';
    echo '<td>' . $document["name"] . '</td>';
    echo '<td>' . $document["accessories"] . '</td>';
    echo '<td>' . $document["brand"] . '</td>';
    echo '<td>' . $document["color"] . '</td>';
    echo '<td> £' . $document["price"] . '</td>';
    echo '</tr>';
}
echo '</table>';
echo '</div>';

// Close the connection
$mongoClient->close();
?>