<?php
    $db_server = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'checkout_db';

    //Connect to Database
    $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
    // Check connection
    if($conn === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>