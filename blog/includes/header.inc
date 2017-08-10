<?php
  if(!isset($_SESSION))
  {
      session_start();
  }

  include '../includes/constants.inc';
  include '../includes/db.inc';
  include '../includes/functions.inc';
?>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <script type="text/javascript" src='../js/jquery.js'></script>
    <script type="text/javascript" src='js/script.js'></script>
  </head>
  <body>
    <nav class='navbar navbar-red navbar-static-top'>
      <div class="container">
        <div class='navbar-header'>
        <a href="index.php" class='navbar-brand'>Blog</a>
        </div>
        <ul class='nav nav-pills navbar-nav navbar-right'>
          <li>
            <a href='index.php'>Home</a>
          </li>
          <li>
            <a href='categories.php'>Tags</a>
          </li>
          <li>
            <a href='trending.php'>Trending</a>
          </li>
        </ul>
      </div>
    </nav>
