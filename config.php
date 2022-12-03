<?php
ob_start();
session_start();

/// Config database
$db_host = 'localhost';
$db_user = 'database_username';
$db_pass = 'database_password';
$db_name = 'database_name';

////////////////////////////////
/// Pricing details -> USD
$price_decode = 2.9;
////////////////////////////////

try {
    //create PDO connection 
    $db = new PDO("mysql:host=".$db_host.";port=8889;dbname=".$db_name, $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('SET NAMES utf8mb4');

} catch(PDOException $e) {
    //show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}

    
    