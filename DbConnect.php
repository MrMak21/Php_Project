<?php


// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("mysql:dbname=passunit_makrisdb;host=passunite.com", "passunit_makris", "test1234");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    print("Connected");
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}
?>
