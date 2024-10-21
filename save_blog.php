<?php
// Include database connection
include 'db_configuration.php';

// Create a new MySQLi connection
$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture the form data
$creator = $_POST['creator'];
$title = $_POST['title'];
$description = $_POST['description'];
$event_date = $_POST['event_date'];
$privacy = $_POST['privacy'];
$creation_date = date('Y-m-d H:i:s');
$modification_date = $creation_date;

// Validate that the title starts with a letter or number
if (!preg_match('/^[A-Za-z0-9]/', $title)) {
    die("Error: The title must start with a letter or number.");
}

// Handle file upload for photos
$photo_path = 'default.jpg'; // Default photo if no upload is made
if (!empty($_FILES['photos']['name'][0])) {
    $upload_dir = 'uploads/';
    $photo_name = basename($_FILES['photos']['name'][0]);
    $photo_path = $upload_dir . $photo_name;

    // Attempt to move the uploaded file
    if (!move_uploaded_file($_FILES['photos']['tmp_name'][0], $photo_path)) {
        die("Error: Failed to upload photo.");
    }
}

// Save the blog data to the database
$sql = "INSERT INTO blogs (creator_email, title, description, event_date, creation_date, modification_date, privacy_filter)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $creator, $title, $description, $event_date, $creation_date, $modification_date, $privacy);

if ($stmt->execute()) {
    echo "Blog created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>