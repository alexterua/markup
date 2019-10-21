<?php

require_once __DIR__ . '/functions.php';

session_start();

// Reset errors
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = htmlspecialchars($_POST['password']);

    if ($_POST['email'] && !empty($_POST['email'])) {

        $_SESSION['email'] = $email;

        if ($_POST['password'] && !empty($_POST['password'])) {

            $dbh = new PDO('mysql:host=localhost;dbname=markup;charset=utf8', 'root', '');
            $sql = 'SELECT email, password FROM users WHERE email=:email';
            $sth = $dbh->prepare($sql);
            $sth->execute([':email' => $email]);
            $dataFromDb = $sth->fetchAll(PDO::FETCH_ASSOC);

            if ($dataFromDb[0]['email']) {

                if (password_verify($password, $dataFromDb[0]['password'])) {

                    if (isset($_POST['remember']) && $_POST['remember'] === '1') {
                        setcookie('password', $password, time() + 60 * 60 * 24);
                        setcookie('email', $dataFromDb[0]['email'], time() + 60 * 60 * 24);
                    }

                    if ($_POST['remember'] === '') {
                        // password in cookies - for educational purposes only
                        setcookie('password', $dataFromDb[0]['password'], time() - 1);
                        setcookie('email', $dataFromDb[0]['email'], time() - 1);
                    }

                    header("Location: /");
                    die;

                } else {
                    $errors['password'] = 'Неверный пароль!';
                }
            } else {
                $errors['email'] = 'Такой пользователь не найден!';
            }
        } else {
            $errors['password'] = 'Введите пароль!';
        }

    } else {
        $errors['email'] = 'Введите email!';
    }

}

// if isset errors, write to session from show at form (at index page)
if ($errors) {
    $_SESSION['errors'] = $errors;
}

header("Location: /login.php");
