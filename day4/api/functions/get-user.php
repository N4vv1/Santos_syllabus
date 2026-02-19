<?php
require_once 'db.php';
session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(["error"=>"Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
SELECT user_id, fullname, nickname, address, birthday,
phone, username, email
FROM users WHERE user_id = ?
");

$stmt->bind_param("i",$user_id);
$stmt->execute();

$result = $stmt->get_result();

if($row = $result->fetch_assoc()){
    echo json_encode($row);
}else{
    echo json_encode(["error"=>"User not found"]);
}

$stmt->close();
$conn->close();
?>