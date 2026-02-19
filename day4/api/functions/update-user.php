<?php
require "db.php";

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
WHERE id=?";

$stmt=$conn->prepare($sql);
$stmt->bind_param("sssssssi",
$fullname,$nickname,$address,$birthday,$phone,$username,$email,$id);
$stmt->execute();

/* PASSWORD OPTIONAL */
if(!empty($_POST['password'])){
    $pw=password_hash($_POST['password'],PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$pw' WHERE id=$id");
}

echo "Account updated successfully";