<?php
require_once 'db.php';
session_start();

$_SESSION['user_id'] = $row['user_id'];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit;
}

$uname = trim($_POST['uname'] ?? '');
$psw   = $_POST['psw'] ?? '';

if ($uname === '' || $psw === '') {
    echo "error";
    exit;
}

$sql = "SELECT user_id, username, email, password
        FROM users
        WHERE username=? OR email=?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $uname, $uname);
$stmt->execute();

$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {

    if (password_verify($psw, $user['password'])) {

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        echo "ok";
        exit;
    }
}

echo "invalid";