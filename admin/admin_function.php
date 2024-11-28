<?php
include "../includes/db.php"; 

// Constants
define('TEMPLATES_PATH', __DIR__ . '/templates/');

if (!function_exists('query')) {
    function query($pdo, $sql, $parameters = []) {
        $query = $pdo->prepare($sql);
        $query->execute($parameters);
        return $query;
    }
}
function getAllUsers( PDO $pdo){
    try{
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }catch(PDOException  $e){
        error_log("Error fetching use" . $e->getMessage());
        return [];
    }
}
function addUser($pdo, $id){
    try{
        $sql = "INSERT INTO users (username, password,email,role) VALUES ( :username, :password, :email, :role)";
        $parameters = [':username'=>$username, ':password'=>$password];
    }catch (Exception $e)
}
// Delete User
function deleteUser($pdo, $id) {
    try {
        // Prepare the SQL statement to delete the user
        $sql = "DELETE FROM users WHERE id = :id";
        $parameters = [':id' => $id];
        query($pdo, $sql, $parameters);
        $_SESSION['message'] = "User successfully deleted.";
        return true;
    } catch (PDOException $e) {
        error_log("Error deleting user: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred while deleting the user.";
        return false;
    }
}

function editUser($pdo, $id, $data) {
    try {
        // Validate the user ID
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception("Invalid user ID.");
        }

        // Build SQL dynamically based on the fields provided in $data
        $fields = [];
        $parameters = [':id' => $id];

        // Sanitize and prepare the fields to update
        if (isset($data['username'])) {
            $fields[] = "username = :username";
            $parameters[':username'] = trim(htmlspecialchars($data['username']));
        }

        if (isset($data['email'])) {
            $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
            if ($email === false) {
                throw new Exception("Invalid email format.");
            }
            $fields[] = "email = :email";
            $parameters[':email'] = $email;
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = :password";
            $parameters[':password'] = password_hash(trim($data['password']), PASSWORD_BCRYPT);
        }

        if (isset($data['role'])) {
            $fields[] = "role = :role";
            $parameters[':role'] = htmlspecialchars($data['role']);
        }

        // Check if there are fields to update
        if (empty($fields)) {
            throw new Exception("No fields to update.");
        }

        // Prepare and execute the SQL statement
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($parameters);

        $_SESSION['message'] = "User  successfully updated.";
        return true;
    } catch (PDOException $e) {
        error_log("Error editing user: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred while editing the user.";
        return false;
    } catch (Exception $e) {
        // Handle other exceptions
        $_SESSION['error'] = $e->getMessage();
        return false;
    }
}


//  manage modules ()
function manageModule($pdo, $action, $module_name = null, $module_id = null) {
    try {
        $sql = "";
        $parameters = [];

        switch ($action) {
            case 'view':
                $sql = "SELECT * FROM modules ORDER BY id ASC"; // Fetch all modules
                break;
            case 'add':
                $sql = "INSERT INTO modules (module_name) VALUES (:module_name)";
                $parameters = [':module_name' => $module_name];
                break;
            case 'update':
                if (!$module_id) {
                    throw new Exception("Module ID is required for updating.");
                }
                $sql = "UPDATE modules SET module_name = :module_name WHERE id = :module_id";
                $parameters = [
                    ':module_name' => $module_name,
                    ':module_id' => $module_id
                ];
                break;
            case 'delete':
                if (!$module_id) {
                    throw new Exception("Module ID is required for deletion.");
                }
                $sql = "DELETE FROM modules WHERE id = :module_id";
                $parameters = [':module_id' => $module_id];
                break;
            default:
                throw new Exception("Invalid action. Use 'view', 'add', 'update', or 'delete'.");
        }

        if ($action === 'view') {
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return records
        } else {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($parameters);
            $_SESSION['message'] = ucfirst($action) . " operation successful.";
            return ['success' => true];
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        return ['success' => false];
    }
}

