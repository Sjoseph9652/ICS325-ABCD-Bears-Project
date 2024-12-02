<?php
require 'db_configuration.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Only admins can view this page.";
    exit;
}

$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users from the database
$sql = "SELECT email, first_name, last_name, role, created_time FROM users ORDER BY created_time DESC";
$result = $conn->query($sql);

if (!$result) {
    echo "Error retrieving users: " . $conn->error;
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin: User Management</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>All Users</h1>
        <table id="usersTable" class="display">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>Created Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                    <a href="user_blogs.php?email=<?php echo urlencode($row['email']); ?>">
                        <?php echo htmlspecialchars($row['email']); ?>
                    </a>
                        </td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_time']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php">Return to Home</a>

        <script>
            // Initialize DataTables
            $(document).ready(function() {
                $('#usersTable').DataTable({
                    "pageLength": 10, // Display 10 rows per page
                    "lengthMenu": [10, 25, 50, 100], // Options for rows per page
                    "order": [[4, "desc"]] // Default sort by "Created Time" in descending order
                });
            });
        </script>
    </body>
</html><!-- comment -->

<?php
$conn->close();
?>