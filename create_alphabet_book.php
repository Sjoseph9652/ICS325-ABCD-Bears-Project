<?php
require 'db_configuration.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['email'])) {
        die("Error: You must be logged in to create an alphabet book.");
    }

    $user_email = $_SESSION['email'];
    $title = trim($_POST['title']);

    if (empty($title)) {
        die("Error: Book title cannot be empty.");
    }

    // Connect to the database
    $conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the new alphabet book into the alphabet_book table
    $stmt = $conn->prepare("INSERT INTO alphabet_book (user_email, title, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $user_email, $title);

    if ($stmt->execute()) {
        // Get the new book's ID
        $book_id = $conn->insert_id;

        // Sample blog IDs (ensure these exist in your blogs table)
        $blog_ids = [1, 2, 3]; // Replace these with valid blog IDs

        // Check if blog IDs exist in the blogs table
        foreach ($blog_ids as $blog_id) {
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM blogs WHERE blog_id = ?");
            $check_stmt->bind_param("i", $blog_id);
            $check_stmt->execute();
            
            // Consume the result set to avoid out of sync error
            $check_stmt->store_result();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();

            // Only insert into alphabet_book_blogs if the blog exists
            if ($count > 0) {
                // Insert blog association into the alphabet_book_blogs table
                $assoc_stmt = $conn->prepare("INSERT INTO alphabet_book_blogs (book_id, blog_id) VALUES (?, ?)");
                $assoc_stmt->bind_param("ii", $book_id, $blog_id);
                $assoc_stmt->execute();
                $assoc_stmt->close();
            } else {
                echo "Blog with ID $blog_id does not exist. Skipping association.<br>";
            }

            // Close the check statement to avoid out of sync errors
            $check_stmt->close();
        }

        echo "New Alphabet Book created successfully with blogs associated.<br>";
        header("Location: alphabet_book.php"); // Redirect after success
        exit;
    } else {
        die("Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
?>
