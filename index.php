<?php
ob_start();
session_start();

require 'includes/db.php';
require 'includes/databasefunction.php';
include 'search.html.php';
$posts = fetch_post($pdo);
// // handle search 
// $searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : null;
// $posts = fetch_post($pdo, $searchQuery);
// Handle delete request
if (isset($_GET['delete'])) {
    $postId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->bindValue(':id', $postId, PDO::PARAM_INT);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Failed to delete post.";
    }
}
?>
<div class="homepage">
    <h1>Homepage</h1>
  
        <input type="text" id="searchInput" class="form-control" placeholder="Search posts...">
        <button id="searchButton" class="btn btn-primary mt-2">Search</button>
    </div>
    <div id="searchResults"></div>

    <?php if (isset($_SESSION['role'])): ?>
        <?php if ($_SESSION['role'] == 1): ?>
            <p>You are logged in as an admin.</p>
        <?php else: ?>
            <a class="btn btn-info" href="login.php">Login</a>
            <a class="btn btn-secondary" href="signup.php">Signup</a>
        <?php endif; ?>
    <?php else: ?>
        <a class="btn btn-info" href="login.php">Login</a>
        <a class="btn btn-secondary" href="signup.php">Signup</a>
    <?php endif; ?>

    <div class="container mt-5">
    <span>Total posts: <?= count($posts) ?></span><br>
      
    <?php 
        $postData = displayPosts($posts);
        if (!empty($postData)): ?>
            <table class="flaming-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Image</th>
                        <th>Module</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php renderPostsTable($postData); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
<script src="js/flaming.js"></script>
</div>
</div>
<?php
$output = ob_get_clean();
include "templates/layout.html.php";
?>
