<?php
// Include SimpleTest code
require_once('../../simpletest/autorun.php');
// Include utility functions to help use with the test
require_once('db_tools.php');
// Include the file that we are testing
require_once('db_interface.php');

class ListCustomerTest extends UnitTestCase {
    function testListCustomer() {
        $username = "test";
        // Call function to list all customers with this username
        listCustomer("Andre", $username, "123");
        // Check if all customers are listed
        $this->assertTrue(listCustomer($username));
    }
}
?>