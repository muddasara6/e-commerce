<?php
// Extract the product IDs that were sent to the server
$prodIDs= $_POST['prodIDs'];

// Convert JSON string to PHP array 
$productArray = json_decode($prodIDs, true);

// Connect to MongoDB
$mongoClient = new MongoClient();

// Select database
$db = $mongoClient->ecommerce;

// Find all orders
$collection = $db->orders;

// Output to page
echo '<!DOCTYPE>
    <html lang="en"> 
    <head>    
    <title>Super Tech | Basket</title>                 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="../img/icon/tab_logo.ico"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script type="text/javascript" src="../js/main.js"></script>
    <script type="text/javascript">
        function checkoutCustomer() {
            document.getElementById("confirmation_email").innerHTML = "Orders are now being processed. Email confirmation will be sent shortly.";
        }
    </script>
    </head>
    <body>
    <div id="top_nav">
    <a href="../index.html">
    <img class="supertech_logo" src="../img/icon/supertech_logo.png">
    </a>
    <input id="search_engine" type="text" name="search" placeholder="Search on the Categories page..." disabled>
    <button id="search_item" onclick="loadContent()">
    <img class="search_icon" src="../img/icon/search_icon.png">
    </button>
    <a href="../register.html">
    <button class="register_button register_button_hover">Register</button>
    </a>
    <a href="../basket.html">
    <button class="add_to_basket add_to_basket_hover">Basket</button>
    </a>
    </div>       
    <div id="nav">
    <ul>       
    <li>
    <a href="../index.html">Home</a>
    </li>
    <li>|</li>         
    <li>
    <a href="../categories.html">Categories</a>
    </li>
    <li>|</li>           
    <li>
    <a href="../contact.html">Contact</a>
    </li>
    <li>|</li>          
    <li>
    <a href="../about.html">About</a>
    </li>
    </ul>        
    </div>
    <h1 id="basket_header">- My Order -</h1>
    <table id="basket_list">
    <tr><th>Product Image</th><th>Product Name</th><th>Price</th><th>Count</th></tr>';
// Output the IDs of the products that the customer has ordered
for($i=0; $i<count($productArray); $i++) {
    echo '<tr>';
    echo '<td class="image_column"><img class="basket_img" src="' . $productArray[$i]['image'] . '"></td>';
    echo '<td>' . $productArray[$i]['name'] . '</td>';
    echo '<td class="totalPrice">£' . $productArray[$i]['price'] . '</td>';
    echo '<td>' . $productArray[$i]['count'] . '</td>';
    echo '</tr>';
    // Convert to PHP array
    $orderData = [
        "image" => $productArray[$i]["image"],  
        "name" => $productArray[$i]["name"], 
        "price" => $productArray[$i]["price"], 
        "count" => 1
    ];
    // Add the order to the database
    $returnVal = $collection->insert($orderData);
}
echo '</table>
        <button class="confirm_button" onclick="checkoutCustomer()">Confirm</button>
        <div id="confirmation_email"></div>
        <button onclick="backToTop()" id="back_to_top" title="Back to top">Back to top</button>       
        <div id="supertech_footer">   
        <span class="contact_footer">Contact Information</span>
        <span class="contact_tour">Tour</span> 
        <span class="contact_help">Help</span>      
        <span class="contact_chat">Chat</span>    
        <span class="contact_contact">Contact</span>     
        <span class="contact_feedback">Feedback</span>     
        <span class="contact_mobile">Mobile</span>     
        <span class="company_footer">Company</span>      
        <span class="company_super_tech">Super Tech</span>     
        <span class="company_super_tech_business">Super Tech Business</span>  
        <span class="company_jobs">Job</span>    
        <span class="company_about">About</span>      
        <span class="company_privacy_policy">Privacy Policy</span>     
        <span class="company_legal">Legal</span>  
        <span class="network_footer">Super Tech Network</span>    
        <span class="network_technology">Technology</span>
        <span class="network_phone">Phone</span>   
        <span class="network_service">Service</span>    
        <span class="network_sim">SIM</span>       
        <span class="network_communication">Communication</span>    
        <span class="social_media_blog">Blog</span>     
        <span class="hifun">-</span>   
        <span class="social_media_facebook">Facebook</span>     
        <span class="hifun_two">-</span>    
        <span class="social_media_twitter">Twitter</span>     
        <span class="hifun_three">-</span>
        <span class="social_media_linkedin">LinkedIn</span>     
        <span class="information_footer">site design / logo © 2018 SuperTech Inc; <br> user contributions licensed under cc by-sa 3.0 <br> with attribution required. rev 2018.1.23.28521</span>    
        </div>';

// Close the connection
$mongoClient->close();
?>