<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Module Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Edit User</h2>
    <form action="edit_user.php" method="POST">
        <label for="userid">User ID:</label>
        <input type="text" id="userid" name="userId" required><br> 

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br> 

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br> 

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br> 

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select><br> 

        <button type="submit">Edit User</button>
    </form>
</body>
</html>
