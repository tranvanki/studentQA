<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="css/add_post.css">
</head>
<body>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea name="content" class="form-control" rows="4" required></textarea>
        </div>
        
        <?php if (isset($_SESSION['username'])): ?>
            <input type="hidden" name="username" value="<?= htmlspecialchars($_SESSION['username']) ?>">
            <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>! You can now add a post.</p>
        <?php else: ?>
            <p>Please log in to add a post.</p>
        <?php endif; ?>

        <div class="form-group">
            <label for="module_id">Module:</label>
            <select name="module_id" class="form-control" required>
                <?php if (!empty($modules)): ?>
                    <?php foreach ($modules as $module): ?>
                        <option value="<?= htmlspecialchars($module['id']) ?>">
                            <?= htmlspecialchars($module['module_name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option disabled>No modules available</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</body>
</html>
