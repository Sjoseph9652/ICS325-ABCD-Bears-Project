<?PHP
require 'db_configuration.php';
$status = session_status();
if ($status == PHP_SESSION_NONE) {
  session_start();
}

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$usermail = $_POST['usermail'];
$password = $_POST['password'];
$hash = sha1($password);

$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check to see if this email is already in the database.
$sql = "SELECT * FROM users WHERE Email = '$usermail';";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // I am empty body for error messages...
    // Put failure message out there.
    $failure = true;
} else {
    // Add the new user to the database
    $sql = "INSERT INTO users (email, first_name, last_name, password, Active, Role, created_time, modified_time, reset_token, token_expiration, token_created_time)
        VALUES ('$usermail', '$firstname', '$lastname', '$hash', 'yes', 'student', SYSDATE(), SYSDATE(), NULL, NULL, NULL);";
    $conn->query($sql);
    // Set session variables for the user
    $_SESSION['email'] = $usermail;
    $_SESSION['first_name'] = $firstname;
    $_SESSION['role'] = 'student';
    $_SESSION['User_Id'] = mysqli_insert_id($conn);
    // Send the user to the registration page.
    header('Location: index.php');
}
?>
