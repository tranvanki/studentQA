<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link view="stylesheet" href="css/update_post.css">
    <title>Update Post</title>
</head>
<body>
    <h2>Update Post</h2>
    <!-- Display error message if it exists -->
    <?php if (isset($error)): ?>
        <div class="error" style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data"> <!-- Added enctype for file uploads -->
        <!-- CSRF token  -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="post_id" value="<?= isset($post['id']) ? htmlspecialchars($post['id']) : '' ?>">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?= isset($post['title']) ? htmlspecialchars($post['title']) : '' ?>" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>
        
        <label>Module:</label><br>
<select name="module_id" required>
    <?php foreach ($modules as $module): ?>
        <option value="<?= htmlspecialchars($module['id']) ?>" <?= ($module['id'] == ($post['module_id'] ?? '')) ? 'selected' : '' ?>>
            <?= htmlspecialchars($module['module_name']) ?>
        </option>
    <?php endforeach; ?>
</select><br><br>

<div class ="form-group">
        <label>Image:</label><br>
        <input type="file" name="image"><br><br> 
        <button type="submit">Update</button>
</div>
       
    </form>
</body>
</html>