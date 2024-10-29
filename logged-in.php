<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog ABCD</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>My Profile</h1>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="create_blog.php">Create Blog Post</a></li>
    </ul>
</nav>

<h1>My Blogs</h1>
<div>
    <a href="logged-in.php?sort=event_date">Sort by Event Date</a> | 
    <a href="logged-in.php?sort=creation_date">Sort by Creation Post Date</a>
</div>

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

  // Sort blogs by event_date or creation_date
  $sortOrder = isset($_GET['sort']) && $_GET['sort'] === 'event_date' ? "ORDER BY event_date DESC" : "ORDER BY creation_date DESC";
  $sql = "SELECT blog_id, title, description, event_date, privacy_filter, creation_date 
          FROM blogs 
          WHERE creator_email = '$user_mail' $sortOrder;";
          
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h2>" . htmlspecialchars($row['title']) ."</h2>";
        echo "<p>Event Date: " . htmlspecialchars($row['event_date']) . "</p>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "<p>Posted on: " . htmlspecialchars($row['creation_date']) . "</p>";
        echo "<p>Privacy: " . htmlspecialchars($row['privacy_filter']) . "</p>";

        echo "<form action='edit.php' method='POST' style='display:inline;'>";
        echo "<input type='hidden' name='blog_id' value='" . htmlspecialchars($row['blog_id']) . "'>";
        echo "<button type='submit'>Edit</button>";
        echo "</form>";
        echo "</div><hr>";
    }
  } else {
    echo "<p>No blogs yet!</p>";
  }
?>

<script src="script.js"></script>
</body>
</html>