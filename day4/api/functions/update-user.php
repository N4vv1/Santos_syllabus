<?php
require "db.php";
session_start();

if(!isset($_SESSION['user_id'])){
    exit("Not logged in");
}

$user_id=$_SESSION['user_id'];

$fullname=$_POST['fullname'];
$nickname=$_POST['nickname'];
$address=$_POST['address'];
$birthday=$_POST['birthday'];
$phone=$_POST['phone'];
$username=$_POST['username'];
$email=$_POST['email'];

$sql="UPDATE users SET
fullname=?,
nickname=?,
address=?,
birthday=?,
phone=?,
username=?,
email=?
WHERE user_id=?";

$stmt=$conn->prepare($sql);
$stmt->bind_param("sssssssi",
$fullname,$nickname,$address,$birthday,$phone,$username,$email,$user_id);
$stmt->execute();

if(!empty($_POST['password'])){

    if(isset($_POST['confirm_password']) &&
       $_POST['password'] !== $_POST['confirm_password']){
        exit("Passwords do not match");
    }

    $psw = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt2 = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
    $stmt2->bind_param("si",$psw,$user_id);
    $stmt2->execute();
}
echo "Account updated successfully";
?>