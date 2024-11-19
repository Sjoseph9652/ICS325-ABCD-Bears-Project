<?php
  require 'db_configuration.php';
  $status = session_status();
  if ($status == PHP_SESSION_NONE) {
    session_start();
  }

  $conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
  $user_mail = $_SESSION["email"];

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  ?>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="create_blog.php">Create Blog Post</a></li>
        <li><a href="alphabet_book.php"> View Alphabet Book</a></li>
    </ul>
</nav>

<!-- Create a new Alphabet Book -->
<form action="create_alphabet_book.php" method="POST">
    <input type="text" name="title" placeholder="New Book Title" required>
    <button type="submit">Create New Book</button>
</form>
