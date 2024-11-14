<?php
require 'db_configuration.php';
session_start();

if (!isset($_SESSION["email"])) {
    echo "You need to be logged in to update blogs.";
    exit;
}

$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if blog_id is provided
if (!isset($_POST['blog_id']) || empty($_POST['blog_id'])) {
    echo "No blog ID provided.";
    exit;
}

$blog_id = $_POST['blog_id'];
$creator_email = $_SESSION['email'];

// Use prepared statements to prevent SQL injection
$sql = $conn->prepare("DELETE FROM blogs WHERE blog_id = ? AND creator_email = ?");
$sql->bind_param("is", $blog_id, $creator_email);

if ($sql->execute()) {
    echo "Blog deleted successfully. <br/> <a href='logged-in.php'>My profile</a>";
} else {
    echo "Error deleting blog: " . $conn->error;
}

$sql->close();
$conn->close();
?>
