<?php

require_once __DIR__ . '/functions.php';

session_start();

// Reset errors
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Data filtering
    $name = htmlspecialchars($_POST['name']);
    $message = htmlspecialchars($_POST['text']);

    if ($name && !empty($name)) {

        $_SESSION['name'] = $name;

        if ($message && !empty($message)) {

            // check for avatar
            if (empty($_POST['avatar'])) {
                $avatar = 'no-user.jpg';
            } else {
                $avatar = $_POST['avatar'];
            }

            $dbh = new PDO('mysql:host=localhost;dbname=markup;charset=utf8', 'root', '');
            $sql = 'INSERT INTO comments (author, avatar, message) VALUES (:author, :avatar, :message)';
            $sth = $dbh->prepare($sql);
            $sth->execute(
                [
                    'author' => $name,
                    'avatar' => $avatar,
                    'message' => $message
                ]
            );
            // Show flash-message at index.php
            $_SESSION['comment_added'] = true;

        } else {
            $errors['text'] = 'Введите сообщение!';
        }
    } else {
        $errors['name'] = 'Введите имя!';
    }
}

// if isset errors, write to session from show at form (at index page)
if ($errors) {
    $_SESSION['errors'] = $errors;
}

header("Location: /");
