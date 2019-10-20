<?php

require_once __DIR__ . '/functions.php';

session_start();

$dbh = new PDO('mysql:host=localhost;dbname=markup;charset=utf8', 'root', '');
$sql = 'SELECT * FROM comments ORDER BY id DESC';
$sth = $dbh->prepare($sql);
$sth->execute();
$comments = $sth->fetchAll(PDO::FETCH_ASSOC);

if ($_SESSION['errors']) {
    $errors = $_SESSION['errors'];
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comments</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    Project
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                            <li class="nav-item">
                                <a class="nav-link" href="login.html">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.html">Register</a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h3>Комментарии</h3></div>

                            <div class="card-body">
                            <?php if ($_SESSION['flashMessage']): ?>
                              <div class="alert alert-success" role="alert">
                                Комментарий успешно добавлен
                              </div>
                            <?php endif; ?>

                                <?php foreach ($comments as $comment): ?>

                                <div class="media">
                                    <img src="img/<?= $comment['avatar']; ?>" class="mr-3" alt="..." width="64" height="64">
                                    <div class="media-body">
                                        <h5 class="mt-0"><?= $comment['author']; ?></h5>
                                        <span><small><?= $comment['created_at']; ?></small></span>
                                        <p><?= $comment['message']; ?></p>
                                    </div>
                                </div>

                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-12" style="margin-top: 20px;">
                        <div class="card">
                            <div class="card-header"><h3>Оставить комментарий</h3></div>

                            <div class="card-body">
                                <form action="/store.php" method="post">
                                    <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Имя</label>
                                    <input name="name" class="form-control" id="exampleFormControlTextarea1" />
                                  </div>
                                  <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Сообщение</label>
                                    <textarea name="text" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                  </div>
                                  <button type="submit" class="btn btn-success">Отправить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
