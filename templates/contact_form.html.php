<?php

ob_start();
?>
<!DOCTYPE html>
<html>
    <head> <link rel="stylesheet" href="css/contact.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <h2>Message To Admin</h2>
    
<form id ="myform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return showPopup()">  
    <p><span class="error">* required field</span></p>

    <label>Name: </label>
    <input type="text" name="name" value="<?php echo $name;?>">
    <span class="error">* <?php echo $nameErr;?></span>
    <br><br>

    <label>E-mail:</label> 
    <input type="text" name="email" value="<?php echo $email;?>">
    <span class="error">* <?php echo $emailErr;?></span>
    <br><br>

    <label>Message:</label>
    <textarea name="message" rows="5" cols="40"><?php echo $message;?></textarea>
    <br><br>

    <label>Gender:</label>
    <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
    <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value = "male">Male 
    <br><br>

    <button type="submit">Submit</button>
</form>


<?php 
$output = ob_get_clean();

include "templates/layout.html.php"; ?>
    <script>
    function showPopup() {
        alert('Form submitted successfully!');
        return true; 
    }
    </script>
</html>