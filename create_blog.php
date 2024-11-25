<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog ABCD</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<main>
<header>    
    <a href="index.php"><h1>ABCD BLOG</h1></a>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="sign-in.php">Sign In</a></li>
        <li><a href="create-account.php">Create Account</a></li>
        <li><a href="create_blog.php">Create Blog Post</a></li>
    </ul>
</nav>

    <div id="create-blog-form">
    <h2>Create a New Blog Post</h2>
        <form action="save_blog.php" method="POST" enctype="multipart/form-data">
            <label for="creator">Creator Email:</label>
            <input type="email" id="creator" name="creator" required><br><br>

            <label for="title">Blog Title:</label>
            <input type="text" id="title" name="title" pattern="^[A-Za-z0-9].*" title="Title must start with a letter or number" required><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

            <label for="photos">Upload Photos:</label>
            <input type="file" id="photos" name="photos[]" multiple><br><br>

            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" required><br><br>

            <label for="privacy">Privacy Setting:</label>
            <select id="privacy" name="privacy">
                <option value="private" selected>Private</option>
                <option value="public">Public</option>
            </select><br><br>

            <button type="submit">Create Blog</button>
        </form>
    </div>
</main>
<footer>
    <p>© 2024 ABCD Blog. All rights reserved.</p>
</footer>
</body>
</html>




