// Searches for products in database
function loadContent() {
    //Extract the search text
    var search = document.getElementById("search_engine").value;
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function(){
        // Check HTTP status code
        if(request.status == 200) {
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("product_grid").innerHTML = responseData;
        }
        else
            // Output error
            alert("Error communicating with server: " + request.status);
    }
    // Set up request with HTTP method and URL 
    request.open("POST", "php/search.php");
    // Add HTTP header to the request
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Send request
    request.send("search=" + search);
    // Add the search keyword to the recommender
    recommender.addKeyword(search);
    showRecommendation();
}

// jQuery onlick function
$(document).on("change", ".order_sorting", function() {
    // Get the value
    var sortingMethod = $(this).val();
    // If the value equals low to high, then arrange the prices from low to high
    if(sortingMethod == 'l2h') {
        sortProductsPriceAscending();
    }
    // If the value equals hight to low, then arrange the prices from high to low
    else if(sortingMethod == 'h2l') {
        sortProductsPriceDescending();
    }
});  

// Sort prices from low to high
function sortProductsPriceAscending() {
    // Selecting HTML element
    var products = $('.product_item');
    // Formula to arranging the prices from low to high 
    products.sort(function(a, b) { 
        return $(a).data("price-item") - $(b).data("price-item")
    });
    // Display the result
    $("#product_grid").html(products);
}

// Sort prices from high to low
function sortProductsPriceDescending() {
    // Selecting HTML element
    var products = $('.product_item');
    // Formula to arranging the prices from low to high 
    products.sort(function(a, b) { 
        return $(b).data("price-item") - $(a).data("price-item")
    });
    // Display the result
    $("#product_grid").html(products);
}

// jQuery onclick function
$(document).on("change", ".order_sorting", function() {
    // Get the value
    var sortingMethod = $(this).val();
    // Print log message to dev console
    console.log(sortingMethod)
    // Get log message, and check alphabetical order
    if (sortingMethod == 'abc') {
        sortProductsAlphabetically();
    }
});

// Sort products in alphabetical order
function sortProductsAlphabetically() {
    // Select HTML element
    var products = $(".product_item");
    // Formula for serting the products in alphabetical order
    products = products.sort(function(a,b) {
        return a.querySelector('p').textContent.localeCompare(b.querySelector('p').textContent)
    });
    // Order products from a to z
    $("#product_grid")[0].innerHTML = "";
    // Display results
    $("#product_grid").append(products);
}

// jQuery onclick function
$(document).on("change", ".order_sorting", function() {
    // Get value
    var sortingMethod = $(this).val();
    // Print log message to dev console
    console.log(sortingMethod)
    // Get log message, and check relevance
    if (sortingMethod == 'rel') {
        // Refresh the page
        location.reload();
    }
});

function productDisplay() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status == 200) {
            var responseData = request.responseText;
            // Display result
            document.getElementById("product_grid").innerHTML = responseData;
        }
        else
            // Display error message
            alert("Error communicating with server: " + request.status);
    }
    // Set up request with HTTP method and URL 
    request.open("GET", "php/categories.php");
    // Send request
    request.send();
}

// Displays basket in page.
function loadBasket() {
    // Get basket from local storage or create one if it does not exist
    var basketArray;
    if(sessionStorage.basket === undefined || sessionStorage.basket === "") {
        // Store as an array
        basketArray = [];
    }
    else {
        // Parse the data as an object
        basketArray = JSON.parse(sessionStorage.basket);
    }
    // Build string with basket HTML
    var htmlStr = "<form action='php/checkout.php' method='post'>";
    // Get table body
    var tableBody;
    // Display table headers
    var tableHeader = "<tr><th>Product Image</th><th>Product Name</th><th>Price</th></tr>\n";
    // Store IDs as array
    var prodIDs = [];
    // For loop to display more products on the table
    for(var i=0; i<basketArray.length; ++i) {
        tableBody += "<tr><td class='image_column'>" + "<img class='basket_img' src='" + basketArray[i].image + "'>" + "</td><td>" + basketArray[i].name + "</td><td>£" + basketArray[i].price + "</td></tr>";
        // Push data to insert into the database
        prodIDs.push({
            image: basketArray[i].image, 
            name: basketArray[i].name, 
            price: basketArray[i].price, 
            count: 1
        });
    }
    // Add hidden field to form that contains stringified version of product ids.
    htmlStr += "<input type='hidden' name='prodIDs' value='" + JSON.stringify(prodIDs) + "'>"; 
    // Display the number of items in the basket
    htmlStr += "<p class='basket_items'>Number of items in basket: " + "<span style='color:red'>" + basketArray.length + "</span>" + "</p>"; 
    // Add checkout and empty basket buttons
    htmlStr += "<button class='empty_basket' onclick='emptyBasket()'>Empty Basket</button>";
    htmlStr += "<input class='checkout_button' type='submit' value='Checkout'></form>";
    // Display number of products in basket
    document.getElementById("basketDiv").innerHTML = htmlStr;
    // Display table
    document.getElementById("basket_list").innerHTML = tableHeader + tableBody;
}

// Deletes all products from basket
function emptyBasket() {
    sessionStorage.clear();
    loadBasket();
}

// Adds an item to the basket
function addToBasket(prodID, prodImage, prodName, prodPrice){
    // Get basket from local storage or create one if it does not exist
    var basketArray;
    if(sessionStorage.basket === undefined || sessionStorage.basket === "") {
        basketArray = [];
    }
    else {
        basketArray = JSON.parse(sessionStorage.basket);
    }
    // Add product to basket
    basketArray.push({id: prodID, image: prodImage, name: prodName, price: prodPrice});
    // Store in local storage
    sessionStorage.basket = JSON.stringify(basketArray);
    // Open basket page
    window.open('basket.html', '_self', false);
}

// Constructor for the recommender object
function Recommender() {
    this.keywords = {}; // Holds the keywords
    this.timeWindow = 10000; // Keywords older than this window will be deleted
    this.load(); // Load into page
}

// Adds a keyword to the recommender
Recommender.prototype.addKeyword = function(word) {
    // Increase count of keyword
    if(this.keywords[word] === undefined) {
        this.keywords[word] = {
            // Output the count and date
            count: 1, 
            date: new Date().getTime()
        };
    }
    else {
        // Increment count and date
        this.keywords[word].count++;
        this.keywords[word].date = new Date().getTime();
    }
    // Display recommender into the console log
    console.log(JSON.stringify(this.keywords));
    // Save state of recommender
    this.save();
};

/* Returns the most popular keyword */
Recommender.prototype.getTopKeyword = function() {
    var search = document.getElementById("search_engine").value;
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function(){
        // Check HTTP status code
        if(request.status == 200) {
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("display_recommend_img").innerHTML = responseData;
        }
        else
            // Display error message
            alert("Error communicating with server: " + request.status);
    }
    // Set up request with HTTP method and URL 
    request.open("POST", "php/recommendation.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Send request
    request.send("search=" + search);
    // Clean up old keywords
    this.deleteOldKeywords();
    // Return word with highest count
    var maxCount = 0;
    var maxKeyword = "";
    for(var word in this.keywords){
        if(this.keywords[word].count > maxCount) {
            maxCount = this.keywords[word].count;
            maxKeyword = word;
        }
    }
    return maxKeyword;
};

/* Saves state of recommender. Currently this uses local storage, but it could easily be changed to save on the server */
Recommender.prototype.save = function() {
    localStorage.recommenderKeywords = JSON.stringify(this.keywords);
};

// Loads state of recommender
Recommender.prototype.load = function() {
    // Check if data is empty or not
    if(localStorage.recommenderKeywords === undefined)
        this.keywords = {};
    else
        // Store data into local storage
        this.keywords = JSON.parse(localStorage.recommenderKeywords);
    // Clean up keywords by deleting old ones
    this.deleteOldKeywords();
};

// Removes keywords that are older than the time window
Recommender.prototype.deleteOldKeywords = function() {
    var currentTimeMillis = new Date().getTime();
    for(var word in this.keywords) {
        if(currentTimeMillis - this.keywords[word].date > this.timeWindow) {
            delete this.keywords[word];
        }
    }
};

// User registers their account
function regCustomer() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status === 200) {
            // Get data from server
            var responseData = request.responseText;

            // Add data to page
            document.getElementById("server_response").innerHTML = responseData;
        }
        else
            // Display error message
            alert("Error communicating with server: " + request.status);
    };
    // Set up request with HTTP method and URL 
    request.open("POST", "php/register.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Extract registration data
    var regName = document.getElementById("name").value;
    var regAddress = document.getElementById("deliveryaddress").value;
    var regPostcode = document.getElementById("postcode").value;
    var regEmail = document.getElementById("emailaddress").value;
    var regUsername = document.getElementById("username").value;
    var regPassword = document.getElementById("password").value;
    // Send request
    request.send("name=" + regName + "&deliveryaddress=" + regAddress + "&postcode=" + regPostcode + "&emailaddress=" + regEmail + "&username=" + regUsername + "&password=" + regPassword);
}

// Attempts to log in user to server
function signin() {
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status === 200) {
            // Get data from server
            var responseData = request.responseText;
            // Output data
            document.getElementById("error_messages").innerHTML = responseData;
        }
        else
            // Display error message
            alert("Error communicating with server: " + request.status);
    };
    // Set up and send request
    request.open("POST", "php/sign_in.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Extract login data
    var logUsername = document.getElementById("username").value;
    var logPassword = document.getElementById("password").value;
    // Send request
    request.send("username=" + logUsername + "&password=" + logPassword);
}

function logout() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function(){
        // Check HTTP status code
        if(request.status == 200){
            // Display data
            document.getElementById("error_messages").innerHTML = "Successfully logged out!";
        }
        else
            // Display error message
            alert("Error communicating with server: " + request.status);
    }
    // Set up request with HTTP method and URL 
    request.open("GET", "php/log_out.php");
    // Send request
    request.send();
}

function pastOrders() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status == 200) {
            // Display data
            var responseData = request.responseText;
            document.getElementById("view_past_table").innerHTML = responseData;
        }
        else
            // Display error message
            alert("Error communicating with server: " + request.status);
    }
    // Set up request with HTTP method and URL 
    request.open("GET", "php/past_order.php");
    // Send request
    request.send();
}

function loadCustomer() {
    var search = document.getElementById("search_detail").value;
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status == 200) {
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("update_data").innerHTML = responseData;
        }
        else
            // Display error
            alert("Error communicating with server: " + request.status);
    }
    // Set up request with HTTP method and URL 
    request.open("POST", "php/search_customer.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Send request
    request.send("search=" + search);
}

function updateData() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status === 200) {
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("server_response").innerHTML = responseData;
        }
        else
            // Display error
            document.getElementById("server_response").innerHTML = "Error communicating with server";
    }
    // Set up request with HTTP method and URL
    request.open("POST", "php/update.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Extract data
    var updateName = document.getElementById("name").value;
    var updateAddress = document.getElementById("deliveryaddress").value;
    var updatePostcode = document.getElementById("postcode").value;
    var updateEmail = document.getElementById("emailaddress").value;
    var updateUsername = document.getElementById("username").value;
    var updatePassword = document.getElementById("password").value;
    // Send request
    request.send("name=" + updateName + "&deliveryaddress=" + updateAddress + "&postcode=" + updatePostcode + "&emailaddress=" + updateEmail + "&username=" + updateUsername + "&password=" + updatePassword);
}

function deleteCustomer() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function(){
        // Check HTTP status code
        if(request.status == 200){
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("server_response").innerHTML = responseData;
        }
        else
            // Display error
            alert("Error communicating with server: " + request.status);
    }
    // Set up request with HTTP method and URL 
    request.open("POST", "php/delete_customer.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Extract data
    var delName = document.getElementById("name").value;
    // Send request
    request.send("name=" + delName);
}
            
function cmsLogin() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status === 200) {
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("error_messages").innerHTML = responseData;
        }
        else
            // Display error
            alert("Error communicating with server: " + request.status);
    };
    // Set up and send request
    request.open("POST", "php/cms_login.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Extract login data
    var cmsLogUsername = document.getElementById("username").value;
    var cmsLogPassword = document.getElementById("password").value; 
    // Send request
    request.send("username=" + cmsLogUsername + "&password=" + cmsLogPassword);
}

function displayTable() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function(){
        // Check HTTP status code
        if(request.status === 200) {
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("display_table").innerHTML = responseData;
        }
        else
            // Display error
            alert("Error communicating with server: " + request.status);
    };
    // Set up request with HTTP method and URL 
    request.open("GET", "php/cms_list_product.php");
    // Send request
    request.send();
}

function addProduct() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status === 200) {
            // Get data from server                        
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("add_product").innerHTML = responseData;
        }
        else 
            // Display error
            alert("Error communicating with server: " + request.status);
    };
    // Set up request with HTTP method and URL 
    request.open("POST", "php/cms_add_product.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Extract registration data
    var addImage = document.getElementById("image").value;
    var addName = document.getElementById("name").value;
    var addAccessories = document.getElementById("accessories").value;
    var addBrand = document.getElementById("brand").value;
    var addColor = document.getElementById("color").value;
    var addPrice = document.getElementById("price").value;
    // Send request
    request.send("&image=" + addImage + "&name=" + addName + "&accessories=" + addAccessories + "&brand=" + addBrand + "&color=" + addColor + "&price=" + addPrice);
}

function loadProduct() {
    // Extract data
    var search = document.getElementById("name").value;
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status == 200) {
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("input_area").innerHTML = responseData;
        }
        else
            // Display error
            alert("Error communicating with server: " + request.status);
    }
    // Set up request with HTTP method and URL 
    request.open("POST", "php/cms_edit_product.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Send request
    request.send("search=" + search);
}

// Downloads JSON description of products from server
function editProduct() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function() {
        // Check HTTP status code
        if(request.status === 200) {
            // Add data from server to page
            var responseData = request.responseText;
           // Add data to page
            document.getElementById("edit_product_message").innerHTML = responseData;
        }
        else
            // Display error
            alert("Error communicating with server: " + request.status);
    };
    // Set up request with HTTP method and URL 
    request.open("POST", "php/edit_product.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Extract data
    var cmsAddImage = document.getElementById("image").value;
    var cmsAddName = document.getElementById("name").value;
    var cmsAddAcces = document.getElementById("accessories").value;
    var cmsAddBrand = document.getElementById("brand").value;
    var cmsAddColor = document.getElementById("color").value;
    var cmsAddPrice = document.getElementById("price").value;
    // Send request
    request.send("&image=" + cmsAddImage + "&name=" + cmsAddName + "&accessories=" + cmsAddAcces + "&brand=" + cmsAddBrand + "&color=" + cmsAddColor + "&price=" + cmsAddPrice);
}

function deleteProduct() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function(){
        // Check HTTP status code
        if(request.status == 200){
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("product_response").innerHTML = responseData;
        }
        else
            // Display error
            alert("Error communicating with server: " + request.status);
    }
    // Set up request with HTTP method and URL 
    request.open("POST", "php/cms_delete_product.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Extract data
    var prodName = document.getElementById("name").value;
    // Send request
    request.send("name=" + prodName);
}

function displayPastOrder() {
    // Create request object 
    var request = new XMLHttpRequest();
    // Create event handler that specifies what should happen when server responds
    request.onload = function(){
        // Check HTTP status code
        if(request.status === 200) {
            // Get data from server
            var responseData = request.responseText;
            // Add data to page
            document.getElementById("display_table").innerHTML = responseData;
        }
        else
            // Display error
            alert("Error communicating with server: " + request.status);
    };
    // Set up request with HTTP method and URL 
    request.open("GET", "php/past_order.php");
    // Send request
    request.send();
}

// When the user clicks on the button, scroll to the top of the page
function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}