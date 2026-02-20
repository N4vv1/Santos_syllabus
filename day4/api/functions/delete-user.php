<?php
require "db.php";
session_start();

if(!isset($_SESSION['user_id'])){
    exit;
}

$user_id=$_SESSION['user_id'];

$conn->query("DELETE FROM users WHERE user_id=$user_id");

session_destroy();

echo "deleted";