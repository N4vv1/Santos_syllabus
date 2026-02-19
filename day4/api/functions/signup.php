<?php 
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$fullname = $_POST['fullname'];
$nickname = $_POST['nname'];
$address = $_POST['address'];
$birthday = $_POST['bdate'];
$phone = $_POST['tel'];
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
    echo "ok";
} else {
    echo "error";
}

$stmt->close();
$conn->close();

}
?>