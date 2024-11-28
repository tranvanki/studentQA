<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login&signup.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>LOGIN PAGE</title>
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h3> Welcome Back </h3>
            <form action="login.php" method="POST">
                <input type="text" class="form-control" name="username" placeholder="Username" id="username" required>
                <input type="password" class="form-control" name="password" placeholder="Password" id="password" required autocomplete="current-password">
                <input type="submit" class="btn btn-primary" name="login" value="Log In"> 
            </form>
            <?php if (isset($msg)) {
                echo '<p style="color:red;">' . htmlspecialchars($msg) . '</p>';
            } ?>
        </div>
    </div>
</body>
</html>


