
<?php
require 'db_configuration.php';
$status = session_status();
if ($status == PHP_SESSION_NONE) {
  session_start();
}

$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT title, description, creator_email, event_date, creation_date FROM blogs WHERE privacy_filter = 'public' ORDER BY title ASC";
$result = $conn->query($sql);

// if $result returned something
if($result->num_rows > 0)
{
    echo "<h1>Public Blogs</h1>";
    while ($row = $result->fetch_assoc()) 
    {
        echo "<div>";
        echo "<h2>" . htmlspecialchars($row['title']) ."</h2>";
        echo "<p>By: " . htmlspecialchars($row['creator_email']) . "</p>";
        echo "<p>Event Date: " . htmlspecialchars($row['event_date']) . "</p>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "<p>Posted on: " . htmlspecialchars($row['creation_date']) . "</p>";
        echo "</div><hr>";
    }
}
else
{
    echo "<h1>No Public Blogs Found</h1>";
}
$conn->close();
?>