<?php
// Include SimpleTest code
require_once('../../simpletest/autorun.php');
// Include utility functions to help use with the test
require_once('db_tools.php');
// Include the file that we are testing
require_once('db_interface.php');

class AddCustomerTest extends UnitTestCase {
    function testAddCustomer() {
        $username = "test";
        // Delete all customers with this username
        while(customerExists($username)) {
            deleteCustomer($username);
        }
        // Call function to add customer
        addCustomer("Andre", $username, "123");
        // Check that customer has been added
        $this->assertTrue(customerExists($username));
        // Call function to remove test customer from database
        deleteCustomer($username);
    }
}
?>