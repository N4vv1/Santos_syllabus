<?php
require_once 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $uname = trim($_POST['uname'] ?? '');
    $psw   = $_POST['psw'] ?? '';

    if ($uname === '' || $psw === '') {
        echo "error";
        exit;
    }

    // search by username OR email
    $sql = "SELECT user_id, username, email, password 
            FROM users 
            WHERE username=? OR email=? 
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $uname, $uname);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // verify hashed password
        if (password_verify($psw, $user['password'])) {

            // store session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            echo "ok";
        } else {
            echo "invalid";
        }

    } else {
        echo "invalid";
    }

    $stmt->close();
    $conn->close();
}
?>