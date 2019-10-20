<?php

require_once __DIR__ . '/functions.php';

session_start();

// Reset errors
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Data filtering
    $name = htmlspecialchars($_POST['name']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password_confirmation'];

    if ($_POST['name'] && !empty($_POST['name'])) {

        $_SESSION['name'] = $name;

        if ($_POST['email'] && !empty($_POST['email'])) {

            $dbh = new PDO('mysql:host=localhost;dbname=markup', 'root', '');
            $sth = $dbh->prepare('SELECT email FROM users WHERE email = :email');
            $sth->execute([':email' => $email]);
            $emailFromDb = $sth->fetch(PDO::FETCH_ASSOC);

            if (!$emailFromDb['email']) {

                $_SESSION['email'] = $email;

                if ($_POST['password'] && !empty($_POST['password'])) {

                    $_SESSION['password'] = $passwordHash;

                    if ($passwordConfirm && !empty($passwordConfirm)) {

                        if ($password === $passwordConfirm) {

                            if (mb_strlen($password) >= 6) {

                                $sth = $dbh->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
                                $sth->execute(
                                    [
                                        ':name' => $name,
                                        ':email' => $email,
                                        ':password' => $passwordHash,
                                    ]
                                );

                            } else {
                                $errors['password_confirmation'] = 'Пароль должен содержать не менее 6 символов!';
                            }

                        } else {
                            $errors['password_confirmation'] = 'Пароли не совпали!';
                        }

                    } else {
                        $errors['password'] = 'Подтвердите пароль!';
                    }

                } else {
                    $errors['password'] = 'Введите пароль!';
                }

            } else {
                $errors['name'] = 'Такой email уже существует!';
            }

        } else {
            $errors['name'] = 'Введите email!';
        }

    } else {
        $errors['name'] = 'Введите имя!';
    }
}

header("Location: /");



