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
$creator_email = $_SESSION['email'];

// Delete query
$sql = "DELETE FROM blogs WHERE blog_id='$blog_id' AND creator_email='$creator_email';";

if ($conn->query($sql) === TRUE) 
{
    echo "Blog deleted successfully. <br/> <a href='logged-in.php'>My profile</a>";
} 
else 
{
    echo "Error deleting blog: " . $conn->error;
}
  $conn->close();
?>