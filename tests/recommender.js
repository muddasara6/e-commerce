// Constructor for the recommender object
function Recommender(){
    this.keywords = {}; // Holds the keywords
    this.timeWindow = 10000; // Keywords older than this window will be deleted
    this.load(); // Load into page
}

// Adds a keyword to the recommender
Recommender.prototype.addKeyword = function(word){
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
Recommender.prototype.getTopKeyword = function(){
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
        if(this.keywords[word].count > maxCount){
            maxCount = this.keywords[word].count;
            maxKeyword = word;
        }
    }
    return maxKeyword;
};

/* Saves state of recommender. Currently this uses local storage, but it could easily be changed to save on the server */
Recommender.prototype.save = function(){
    localStorage.recommenderKeywords = JSON.stringify(this.keywords);
};

// Loads state of recommender
Recommender.prototype.load = function(){
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
Recommender.prototype.deleteOldKeywords = function(){
    var currentTimeMillis = new Date().getTime();
    for(var word in this.keywords){
        if(currentTimeMillis - this.keywords[word].date > this.timeWindow){
            delete this.keywords[word];
        }
    }
};