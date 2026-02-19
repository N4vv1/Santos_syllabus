<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$fullname = $_POST['fullname'];
$nickname = $_POST['nname'];
$address = $_POST['address'];
$birthday = $_POST['bdate'];
$phone = $_POST['phone'];
$username = $_POST['uname'];
$email = $_POST['email'];
$password = $_POST['psw'];

$hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (fullname, nickname, address, birthday, phone, username, email, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssss",
    $fullname,
    $nickname,
    $address,
    $birthday,
    $phone,
    $username,
    $email,
    $hashed
);

if ($stmt->execute()) {
    echo "User registered successfully!";
} else {
    echo "Error: " . $stmt-> error;
}

$stmt->close();
$conn->close();

}
?>