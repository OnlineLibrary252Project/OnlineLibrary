<?php
session_start();
require_once 'config/app.php';
require_once 'logger/logger.php';
?>
<!DOCTYPE html>
<html lang=<?php echo $config['lang']; ?> dir=<?php echo $config['dir']; ?>>
  <head>
    <meta charset="utf-8">
    <title><?php echo $config['app_name'] . " | " .$title; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <style>
      .custom-card-image {

          height: 400px;
          background-size: cover;
          background-position: center;

      }
    </style>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="<?php echo $config['app_url'] ?>"><?php echo $config['app_name']; ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">

        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="<?php echo $config['app_url']; ?>">Books</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?php echo $config['app_url']."GoogleApi.php"; ?>">Google Books Api</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?php echo $config['app_url']."contact.php"?>">Contact Us</a>
          </li>

          </li>
        </ul>

        <?php if(!isset($_SESSION['logged_in'])): ?>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $config['app_url']."login.php"?>">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $config['app_url']."register.php"?>">Register</a>
          </li>
        </ul>
      <?php else: ?>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $config['app_url'] ?>"><?php echo 'Welcome '.$_SESSION["user_name"]; ?></a>
          </li>
          <?php if($_SESSION['user_role']=='user'){ ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $config['app_url']."requests.php"?>">Requests</a>
          </li>
          <?php } ?>
          <?php if($_SESSION['user_role']=='admin'){ ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $config['app_url']."admin"?>">Admin Page</a>
            </li>
          <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $config['app_url']."logout.php"?>">Logout</a>
          </li>
        </ul>
      <?php endif; ?>
      </div>
    </nav>

  <div class="container pt-5">
  <?php include 'messages.php'; ?>
