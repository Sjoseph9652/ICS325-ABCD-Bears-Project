<?php
require 'db_configuration.php';
$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination variables
$blogsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $blogsPerPage;

// Filter by letter
$filterLetter = isset($_GET['letter']) ? $conn->real_escape_string($_GET['letter']) : '';

// Base query
$sql = "SELECT blog_id, title, description FROM blogs";

// Apply letter filter
if ($filterLetter) {
    $sql .= " WHERE title LIKE '$filterLetter%'";
}

// Pagination
$sql .= " LIMIT $blogsPerPage OFFSET $offset";
$result = $conn->query($sql);

// Count total blogs for pagination
$countSql = "SELECT COUNT(*) AS total FROM blogs";
if ($filterLetter) {
    $countSql .= " WHERE title LIKE '$filterLetter%'";
}
$totalResult = $conn->query($countSql);
$totalBlogs = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalBlogs / $blogsPerPage);

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Alphabet Book</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
</head>
<body>
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="create_blog.php">Create Blog Post</a></li>
        <li><a href="view_alphabet_books.php">View Alphabet Book</a></li>
    </ul>
</nav>

<h2>Create Alphabet Book</h2>

<body>
    <h1>Create Your Alphabet Book</h1>

    <!-- Alphabet Navigation -->
    <nav>
        <?php foreach (range('A', 'Z') as $letter): ?>
            <a href="?letter=<?php echo $letter; ?>"><?php echo $letter; ?></a>
        <?php endforeach; ?>
    </nav>

    <!-- Blog Selection Table -->
    <form method="POST" action="create_alphabet_book.php">
        <label for="title">Alphabet Book Title:</label>
        <input type="text" id="title" name="title" required>
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Title</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_blogs[]" value="<?php echo $row['blog_id']; ?>">
                            </td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No blogs found for this letter.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div>
            <?php if ($page > 1): ?>
                <a href="?letter=<?php echo $filterLetter; ?>&page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?letter=<?php echo $filterLetter; ?>&page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>

        <button type="submit">Create Alphabet Book</button>
    </form>
</body>

<script>
    $(document).ready(function() {
        $('#blogSelectionTable').DataTable();
    });
</script>
</body>
</html>
