<?php
// Include SimpleTest code
require_once('../../simpletest/autorun.php');
// Include utility functions to help use with the test
require_once('db_tools.php');
// Include the file that we are testing
require_once('db_interface.php');

class SearchCustomerTest extends UnitTestCase {
    function testSearchCustomer() {
        $username = "test";
        // Check that customer has been added
        $this->assertTrue(customerExists($username));
    }
}
?>