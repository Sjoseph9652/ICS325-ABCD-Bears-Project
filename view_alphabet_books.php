<?php
require 'db_configuration.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: sign-in.php");
    exit;
}

// Get the book_id from the URL
if (!isset($_GET['book_id'])) {
    die("Error: No book ID provided.");
}

$book_id = intval($_GET['book_id']);

// Connect to the database
$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the book details
$stmt = $conn->prepare("SELECT title FROM alphabet_book WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    die("Error: Book not found.");
}

// Fetch blogs associated with the book
$stmt = $conn->prepare("SELECT id, title, description FROM blogs WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$blogs_result = $stmt->get_result();
?>

<h2>Alphabet Book: <?php echo htmlspecialchars($book['title']); ?></h2>

<?php if ($blogs_result->num_rows > 0): ?>
    <form method="post" action="process_book_action.php">
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Description</th>
            </tr>
            <?php while ($blog = $blogs_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($blog['title']); ?></td>
                    <td><?php echo htmlspecialchars($blog['description']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </form>
<?php else: ?>
    <p>No blogs found in this book.</p>
<?php endif; ?>

<?php
$stmt->close();
$conn->close();
?>
