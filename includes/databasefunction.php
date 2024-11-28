<?php 

// add session -> asign post to user bu $_SESSION()
// CHECK html& css, displayPosts()
// delete synchronous with upload file
include "db.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//query generic->query
if (!function_exists('query')) {
    function query($pdo, $sql, $parameters = []) {
        $query = $pdo->prepare($sql);
        $query->execute($parameters);
        return $query;
    }
}

// Fetch all posts
function fetch_post($pdo) {
    $sql = "SELECT posts.*, users.username, modules.module_name 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            JOIN modules ON posts.module_id = modules.id";
    $posts = query($pdo, $sql);
    return $posts;
}
// prevent XSS
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// Define the function to display posts 
function displayPosts($posts) {
    foreach ($posts as $post)
    {
        echo "<tr>";
        echo "<td>". htmlspecialchars($post['title'])."</td>";
        echo "<td>".htmlspecialchars($post['content'])."</td>";
        echo "<td>";
        if(!empty($post['image'])){
            echo "<img src='" . htmlspecialchars($post['image']) . "' alt='Post Image' width='100'>";
        }else{
            echo "No image";
        }
        echo "</td>";
        echo "<td>";
        echo "<a href='update_post.php?id=" . $post['id'] . "' class='btn btn-warning btn-sm'>Update</a>";
        echo " ";
        echo "<a href='index.php?delete=" . $post['id'] . "' class='btn btn-danger btn-sm' 
                  onclick=\"return confirm('Are you sure you want to delete this post?');\">Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
}
        
function totalPosts($pdo) {
    
    $sql = "SELECT COUNT(*) from posts";
    $result = query($pdo, $sql);
    return $result[0][0];
}
//search post
function searchPost(){
    void:
}

//addpost
function addpost($pdo, &$message = null) {
    // Fetch modules for the dropdown
    try {
        $modules = $pdo->query("SELECT id, module_name FROM modules")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $message = "Error fetching data: " . $e->getMessage();
        return ['modules' => []];
    }
    // Handle POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? null;
        $content = $_POST['content'] ?? null;
        $username = $_SESSION['username'] ?? null;
        $module_id = $_POST['module_id'] ?? null;
        $image = $_FILES['image'] ?? null;
        // Check if username is available in session
        if (!$username) {
            $message = "User not logged in.";
            return ['modules' => $modules];
        }
        // Fetch user ID based on username
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $message = "Invalid user.";
                return ['modules' => $modules];
            }

            $user_id = $user['id'];
        } catch (Exception $e) {
            $message = "Error fetching user: " . $e->getMessage();
            return ['modules' => $modules];
        }

        // Handle image upload
        $imagePath = null;
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $imagePath = 'includes/uploads/' . basename($image['name']);
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $message = "Error uploading image.";
                return ['modules' => $modules];
            }
        }

        // Insert post
        if ($title && $content && $user_id && $module_id) {
            try {
                $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, module_id, image) VALUES (:title, :content, :user_id, :module_id, :image)");
                $stmt->execute([
                    ':title' => $title,
                    ':content' => $content,
                    ':user_id' => $user_id,
                    ':module_id' => $module_id,
                    ':image' => $imagePath,
                ]);
                $message = 'POST added successfully';
                return ['modules' => $modules];
            } catch (Exception $e) {
                $message = "Error saving post: " . $e->getMessage();
            }
        } else {
            $message = "All fields are required.";
        }
    }

    // Return modules for the dropdown
    return ['modules' => $modules];
}


//updatepost
function updatePost($pdo) {
    $error = null;
    $post = null;
    $modules = [];
    $users = [];

    // Fetch modules for the dropdown
    try {
        $modules = $pdo->query("SELECT id, module_name FROM modules")->fetchAll(PDO::FETCH_ASSOC);
        $users = $pdo->query("SELECT id, username FROM users")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = "Error fetching data: " . $e->getMessage();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $post_id = $_POST['post_id'];
        $sql = "SELECT * FROM posts WHERE id = :id";
        $currentPost = query($pdo, $sql, ['id' => $post_id])->fetch(PDO::FETCH_ASSOC);

        if (!$currentPost) {
            $error = "Post not found.";
        } else {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $module_id = $_POST['module_id'];
            $imagePath = $currentPost['image'];

            // Check if a new image is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $image = $_FILES['image']['name'];
                $target = __DIR__ . '/../includes/uploads/' . basename($image); // Corrected path

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $imagePath = 'includes/uploads/' . basename($image); // Ensure consistency
                } else {
                    $error = "Failed to upload image.";
                }
            }

            if (!$error) {
                $sql = "UPDATE posts SET title = :title, content = :content, module_id = :module_id, image = :image WHERE id = :id";
                $parameters = ['title' => $title, 'content' => $content, 'module_id' => $module_id, 'image' => $imagePath, 'id' => $post_id];
                query($pdo, $sql, $parameters);
                $_SESSION['success'] = "Post updated successfully.";
                header("Location: index.php?id=" . $post_id);
                exit();
            }
        }
    } else {
        $post_id = $_GET['id'] ?? null;
        if ($post_id) {
            $sql = "SELECT * FROM posts WHERE id = :id";
            $post = query($pdo, $sql, ['id' => $post_id])->fetch(PDO::FETCH_ASSOC);
            if (!$post) {
                $error = "Post not found.";
            }
        } else {
            $error = "No post ID provided.";
        }
    }

    require 'templates/update_post.html.php';
}



// //deletepost
function deletePost($id) {
    global $pdo;
    try {
        // First, fetch the post to get the image filename
        $sql = "SELECT image FROM posts WHERE id = :id";
        $parameter = [':id' => $id];

        $stmt = query($pdo, $sql, $parameter);
        
        // Fetch the post
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if the post exists
        if ($post) {
            // Get the image filename
            $imageFile = $post['image'];
            // Prepare the SQL statement to delete the post
            $sql = "DELETE FROM posts WHERE id = :id";
            $parameter = [':id' => $id];
            query($pdo, $sql, $parameter);
            
            // Delete the image file
            $imagePath = __DIR__ . "/includes/uploads/" . $imageFile;
            if (file_exists($imagePath)) {
                if (unlink($imagePath)) {
                    // File successfully deleted
                    $_SESSION['success'] = "Post and image successfully deleted!";
                    return true; 
                } else {
                    // Could not delete the image
                    $_SESSION['warning'] = "Post deleted, but the image could not be deleted.";
                    return false;
                }
            } else {
                $_SESSION['warning'] = "Post deleted, but the image was not found.";
                return true;
            }
        } else {
            $_SESSION['error'] = "Post not found or already deleted.";
            return false;
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        return false;
    }
}


function contactAdmin($pdo) {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Input validation
        $errors = []; 
        $name = $email = $message = $gender = "";

        // Name validation
        if (empty($_POST["name"])) {
            $errors[] = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $errors[] = "Only letters and white space allowed in the name";
            }
        }

        // Email validation
        if (empty($_POST["email"])) {
            $errors[] = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }
        }

        // Message validation
        if (!empty($_POST["message"])) {
            $message = test_input($_POST["message"]);
        }

        // Gender validation
        if (empty($_POST["gender"])) {
            $errors[] = "Gender is required";
        } else {
            $gender = test_input($_POST["gender"]);
        }

        if (empty($errors)) {

            // Send email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';  // Replace with your SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = 'kienntgch230116@fpt.edu.vn';                     //SMTP username
                $mail->Password   = 'fewt ccaj vocd drlp';    // Replace with your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Recipients
                $mail->setFrom($email, $name); // From the user
                $mail->addAddress('kienntgch230116@fpt.edu.vn', 'Admin'); // Replace with admin email
                $mail->addReplyTo($email, $name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = "New Contact Form Submission";
                $mail->Body    = "<p><strong>Name:</strong> $name</p>
                                  <p><strong>Email:</strong> $email</p>
                                  <p><strong>Gender:</strong> $gender</p>
                                  <p><strong>Message:</strong><br>$message</p>";
                $mail->AltBody = "Name: $name\nEmail: $email\nGender: $gender\nMessage:\n$message";

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            // Display errors
            echo "There were errors in the form:";
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
        }
    }
}


// function search($pdo){

//     if (isset($_GET['search_query'])) {
//         $searchQuery = $_GET['search_query'];
//         $sql = "SELECT posts.*, users.username, modules.module_name 
//                 FROM posts 
//                 JOIN users ON posts.user_id = users.id 
//                 JOIN modules ON posts.module_id = modules.id
//                 WHERE posts.title LIKE :query OR posts.content LIKE :query";
        
//         //$smtm execute
//         exit();
//     }}

    