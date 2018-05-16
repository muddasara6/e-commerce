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

// Find all of the customers that match  this criteria
$cursor = $db->customers->find($findCriteria);

// Output the results
foreach ($cursor as $cust) {
    echo '<div id="edit_top_border">';
    echo '<img class="edit_user_icon" src="img/icon/sign_in_user_icon.png" alt="User">';
    echo '<h1 class="edit_header">Update Account</h1>';
    echo '<p class="search_info">Please fill in the data you want to change from your <b>Account</b> and click on <b>Update</b> to submit the data.</p>';
    echo '</div>';
    echo '<div id="edit_bottom_border">';
    echo '<br>';
    echo '<label class="register_label">Name<span class="error"> *</span>';                      
    echo '<br>';                      
    echo '<input id="name" name="name" type="text" value="' . $cust['name'] . '">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="register_label">Delivery Address<span class="error"> *</span>';
    echo '<br>';
    echo '<input id="deliveryaddress" name="deliveryaddress" type="text" value="' . $cust['deliveryaddress'] . '">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="register_label">Postcode<span class="error"> *</span>'; 
    echo '<br>';
    echo '<input id="postcode" name="postcode" type="text" value="' . $cust['postcode'] . '">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="register_label">Email Address<span class="error"> *</span>';
    echo '<br>';
    echo '<input id="emailaddress" name="emailaddress" type="email" value="' . $cust['emailaddress'] . '">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="register_label">Username<span class="error"> *</span>';
    echo '<br>';
    echo '<input id="username" name="username" type="text" value="' . $cust['username'] . '">';
    echo '</label>';
    echo '<br>';
    echo '<br>';
    echo '<label class="register_label">Password<span class="error"> *</span>';
    echo '<br>';
    echo '<input id="password" name="password" type="password" value="' . $cust['password'] . '">';
    echo '</label>';
    echo '<br>';
    echo '<input id="customer_data_button" onclick="updateData()" name="update" type="submit" value="UPDATE">';
    echo '<br>';
    echo '<p id="server_response"></p>'; 
    echo '</div>';
}

// Close the connection
$mongoClient->close();
?>