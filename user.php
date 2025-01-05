<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    $query = "INSERT INTO user (username, email, pass) VALUES (:username, :email, :pass)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pass', $password);

    try {
        $stmt->execute();
        header("Location: register.php?status=success&message=Add User successfully!");
        exit();
    } catch (PDOException $e) {
        header("Location: register.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }   
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $query = "UPDATE user SET username = :username, email = :email WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':user_id', $user_id);

    try {
        $stmt->execute();
        header("Location: register.php?status=success&message=User updated successfully!");
        exit();
    } catch (PDOException $e) {
        header("Location: register.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $user_id = $_POST['user_id'];

    $query = "DELETE FROM user WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);

    try {
        $stmt->execute();
        header("Location: register.php?status=success&message=User deleted successfully!");
        exit();
    } catch (PDOException $e) {
        header("Location: register.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
