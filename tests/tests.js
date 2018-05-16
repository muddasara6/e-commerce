// Test 1
QUnit.test("Test Add Keyword", function(assert) {
    // Create recommender
    var recommender = new Recommender();
    // Ensure that recommender is in empty state
    recommender.keywords = {};
    // Add some keywords
    recommender.addKeyword("Headset");
    recommender.addKeyword("Headset");
    recommender.addKeyword("Speaker");
    recommender.addKeyword("Speaker");
    recommender.addKeyword("Headset");
    // Check that we get the correct recommendation
    assert.strictEqual(JSON.parse(localStorage.recommenderKeywords), recommender.keywords);
});

// Test 2
QUnit.test("Test Get Top Keyword", function(assert) {
    // Create recommender
    var recommender = new Recommender();
    // Ensure that recommender is in empty state
    recommender.keywords = {};
    // Check that we get the correct recommendation
    assert.strictEqual(recommender.getTopKeyword(), "Headset");
    // Add another two keywords
    recommender.addKeyword("Speaker");
    recommender.addKeyword("Speaker");
    // Check that we get a different top keyword
    assert.strictEqual(recommender.getTopKeyword(), "Speaker");
});

// Test 3
QUnit.test("Test Save", function(assert) {
    // Create recommender and initialize to known state
    var recommender = new Recommender();
    recommender.keywords = {word1: "speaker", word2: "headset"};
    // Save state
    recommender.save();
    // Check that the recommender's state has been saved
    assert.deepEqual(JSON.parse(localStorage.recommenderKeywords), recommender.keywords);
});

// Test 4
QUnit.test("Test Load", function(assert) {
    // Create recommender
    var recommender = new Recommender();
    // Ensure that recommender is in empty state
    recommender.keywords = {};
    // load state
    recommender.load();
    // Check that the recommender's state has been saved
    assert.deepEqual(JSON.parse(localStorage.recommenderKeywords), recommender.keywords);
});

// Test 5
QUnit.test("Test Delete Old Keywords", function(assert) {
    // Create recommender
    var recommender = new Recommender();
    // Ensure that recommender is in empty state
    recommender.keywords = {};
    // Add extra keywords
    recommender.addKeyword("Speaker");
    recommender.addKeyword("Headset");
    // Check that old keywords have been deleted
    assert.strictEqual(recommender.deleteOldKeywords(), "Speaker");
});