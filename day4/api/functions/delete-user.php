<?php
require "db.php";

if(!isset($_SESSION['user_id'])){
    exit;
}

$id=$_SESSION['user_id'];

$conn->query("DELETE FROM users WHERE id=$id");

session_destroy();

echo "deleted";