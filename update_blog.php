
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog ABCD</title>
    <link rel="stylesheet" href="styles.css">
    <style>

    </style>
</head>


<body>
<main>
<header>
    <a href="index.php">
        <img src="images/abcd.png" alt="ABCD Blog Logo">
    </a>
</header>

<body>
<nav>
    <ul>
        <li><a href="index.php">Home/Log-Out</a></li>
        <li><a href="create_blog.php">Create Blog Post</a></li>
    </ul>
</nav>



    <?php
  require 'db_configuration.php';
  session_start();

  if (!isset($_SESSION["email"])) {
    echo "You need to be logged in to update blogs.";
    exit;
  }
$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

// Escape inputs to prevent SQL injection
$blog_id = $conn->escape_string($_POST['blog_id']);
$title = $conn->escape_string($_POST['title']);
$description = $conn->escape_string($_POST['description']);
$event_date = $conn->escape_string($_POST['event_date']);
$privacy_filter = $conn->escape_string($_POST['privacy_filter']);
$creator_email = $_SESSION['email'];

// Update query
$sql = "UPDATE blogs SET title='$title', description='$description', event_date='$event_date', privacy_filter='$privacy_filter' WHERE blogs . blog_id='$blog_id';";
echo $sql;

if ($conn->query($sql) === TRUE) 
{
  echo "<div style='text-align:center;'>Blog updated successfully. <br/> <a href='logged-in.php'>My profile</a></div>";
} 
else 
{
    echo "Error updating blog: " . $conn->error;
}
$conn->close();
?>



  </main>
    <script src="script.js"></script>
    <footer>
        <p>© 2024 ABCD Blog. All rights reserved.</p>
    </footer>
</body>
</html>
