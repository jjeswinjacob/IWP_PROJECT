<?php
    // File for connecting with database
    ob_start(); // Turns on output buffering
    session_start(); 
    /* Helps us use sessions - Use variables and values even after 
    page has been closed - Here to use as isUserLoggedIn? */

    date_default_timezone_set("Asia/Calcutta");

    try {
        // Connection variable
        $con = new PDO("mysql:dbname=infinity;host = localhost", "root", ""); // For creating new connection to database
        // Connecting to infinity database with root as username and empty password

        // Static property in PDO called Attribute Error Mode
        // Setting error reporting property of infinity database
        $con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    catch(PDOException $e) {
        exit("Connection failed: " . $e -> getMessage());
    }
?>