<?php
  $status = session_status();
  if ($status == PHP_SESSION_NONE) {
    session_start();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog ABCD</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>ABCD BLOG Home Page</h1>


<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="sign-in.php">Sign In</a></li>
            <li><a href="create-account.php">Create Account</a></li>
        </ul>
    </nav>

  <h1> You have successfully logged in!!!</h1>


    
    <script src="script.js"></script>
</body>
</html>