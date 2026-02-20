<?php
session_start();
if (isset($_SESSION['user_id'])) {
    echo "ok";
} else {
    echo "no";
}
?>