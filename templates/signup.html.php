<!DOCTYPE html>
<html>
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="css/login&signup.css"> 
        <title>SIGN UP PAGE</title>
    </head>
    <body>
        <div class="container">
            <div class="signup-form"> 
                <h3> Create an Account</h3> 

                <!-- Error Message Display -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); // Clear the error message after displaying it ?>
                <?php endif; ?>
<>
                <form action="signup.php" method="post">  
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                    <input type="submit" class="btn btn-primary" name="signup" value="Sign Up"> 
                </form>
            </div>
        </div>
    </body>
</html>
