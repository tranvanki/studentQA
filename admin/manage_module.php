<?php 
require "admin_function.php";


session_start();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ;
    $module_name = $_POST['module_name'] ;
    $module_id = $_POST['module_id'] ;

    if ($action) {
        manageModule($pdo, $action, $module_name, $module_id);
    }
}

// Fetch all modules for display
$modules = manageModule($pdo, 'view');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Modules</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f4f4f4; }
        form { display: inline; }
    </style>
</head>
<body>
    <h1>Manage Modules</h1>
    <?php if(isset($_SESSION['message'])): ?>
        <p><?= htmlspecialchars($_SESSION['message']) ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Module Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $module): ?>
                <tr>
                    <td><?= htmlspecialchars($module['id']) ?></td>
                    <td><?= htmlspecialchars($module['module_name']) ?></td>
                        <!-- Update Module Form -->
                        <form method="POST">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="module_id" value="<?= htmlspecialchars($module['id']) ?>">
                            <input type="text" name="module_name" value="<?= htmlspecialchars($module['module_name']) ?>">
                            <button type="submit">Update</button>
                        </form>
                        <!-- Delete Module Form -->
                        <form method="POST">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="module_id" value="<?= htmlspecialchars($module['id']) ?>">
                            <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2>Add New Module:</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="module_name" placeholder="Enter module name" required>
        <button type="submit">Add Module</button>
    </form>
</body>
</html>
