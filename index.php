<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog ABCD</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>ABCD BLOG</h1>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="sign-in.php">Sign In</a></li>
        <li><a href="create-account.php">Create Account</a></li>
        <li><a href="create_blog.php">Create Blog Post</a></li>
    </ul>
</nav>

<!-- Sorting Form -->
<form method="GET" action="index.php">
    <button type="submit" name="sort" value="title">Sort Alphabetically</button>
    <button type="submit" name="sort" value="creation_date">Sort by Creation Date</button>
</form>

<!-- Search and Filter Form -->
<form method="GET" action="index.php">
    <label for="alpha">Alphabetical Search:</label>
    <select name="alpha" id="alpha">
        <option value="">All</option>
        <?php
        foreach (range('A', 'Z') as $letter) {
            echo "<option value='$letter'>$letter</option>";
        }
        ?>
    </select>
    <input type="text" name="search" placeholder="Search by title or description">
    <label for="start_date">Date Range:</label>
    <input type="date" name="start_date">
    <input type="date" name="end_date">
    <button type="submit">Filter</button>
</form>

<?php
  $status = session_status();
  if ($status == PHP_SESSION_NONE) {
    session_start();
  }

  require 'db_configuration.php';
  $conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Determine sorting order
  $order_by = "title";
  if (isset($_GET['sort']) && $_GET['sort'] == 'creation_date') {
    $order_by = "creation_date DESC";
  }

  // Construct SQL query with search and filter criteria
  $sql = "SELECT title, description, creator_email, event_date, creation_date 
          FROM blogs WHERE privacy_filter = 'public'";
  
  if (isset($_GET['search']) && $_GET['search'] !== '') {
      $search = $conn->real_escape_string($_GET['search']);
      $sql .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
  }
  if (isset($_GET['alpha']) && $_GET['alpha'] !== '') {
      $alpha = $conn->real_escape_string($_GET['alpha']);
      $sql .= " AND title LIKE '$alpha%'";
  }
  if (isset($_GET['start_date']) && $_GET['start_date'] !== '' && isset($_GET['end_date']) && $_GET['end_date'] !== '') {
      $start_date = $conn->real_escape_string($_GET['start_date']);
      $end_date = $conn->real_escape_string($_GET['end_date']);
      $sql .= " AND creation_date BETWEEN '$start_date' AND '$end_date'";
  }

  $sql .= " ORDER BY $order_by";
  $result = $conn->query($sql);

  if($result->num_rows > 0) {
      echo "<h1>Public Blogs</h1>";
      while ($row = $result->fetch_assoc()) {
          echo "<div>";
          echo "<h2>" . htmlspecialchars($row['title']) ."</h2>";
          echo "<p>By: " . htmlspecialchars($row['creator_email']) . "</p>";
          echo "<p>Event Date: " . htmlspecialchars($row['event_date']) . "</p>";
          echo "<p>" . htmlspecialchars($row['description']) . "</p>";
          echo "<p>Posted on: " . htmlspecialchars($row['creation_date']) . "</p>";
          echo "</div><hr>";
      }
  } else {
      echo "<h1>No Public Blogs Found</h1>";
  }

  $conn->close();
?>

<script src="script.js"></script>
</body>
</html>
