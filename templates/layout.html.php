<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Q&A</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/layout.css">
</head>
<body>
    <div class="container">
        <header class="flaming-header">
            <h1 class="burning-text">Welcome to Flaming Student Q&A Platform</h1>
            <nav class="mb-4">
                <a href="index.php" class="btn btn-flaming">Home</a>
                <a href="add_post.php" class="btn btn-flaming-secondary">Add Post</a>
                <a href="contact.php" class="btn btn-flaming-contact">Contact Admin</a>
                <a href="logout.php" class="btn btn-flaming-danger">Log Out</a>
            </nav>
        </header>
        <main class="content">
            <?= $output ?>
        </main>
        <footer class="text-center mt-5">
            <small>
                <p>Copyright © . All Rights Reserved.</p>
                <p>Author name: Nguyen Kieen</p>
                <p>Email: trungkieen@gmail.com</p>
            </small>
        </footer>
    </div>
</body>
</html>
