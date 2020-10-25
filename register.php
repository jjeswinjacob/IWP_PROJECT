<?php
    require_once("includes/classes/Account.php");
    require_once("includes/classes/FormSanitizer.php");
    require_once("includes/classes/Constants.php");
    require_once("includes/config.php");


    $account = new Account($con);
    if (isset($_POST['submitButton'])){
      $account = new Account($con);
      $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
      $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
      $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
      $number = FormSanitizer::sanitizeFormNumber($_POST["number"]);
      $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
      $email2 = FormSanitizer::sanitizeFormEmail($_POST["email2"]);
      $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
      $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);
      $response = $_POST['captcha'];

      $success = $account -> register($firstName, $lastName, $username, $number, $email, $email2, $password, $password2,$response);
      if($success) {
          $_SESSION["userLoggedIn"] = $username;
        	header("Location: index.php");
      }
    }

    function getInputValue($name) {
        if(isset($_POST[$name])) {
            echo $_POST[$name];
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Welcome to Infinity</title>
        <link rel = "stylesheet" type = "text/css" href = "assets/style/style.css">
    </head>
    <body>
        <div class = "signInContainer">
            <div class = "column">
                <div class = "header">
                    <img src = "assets/images/logo.png" title = "Logo" alt = "Site Logo"/>
                    <h3> Sign Up </h3>
                    <span> to continue to Infinity </span>
                </div>

                <form method = "POST">

                    <?php echo $account -> getError(Constants::$firstNameCharacters); ?>
                    <input type = "text" id = "firstName" name = "firstName" placeholder = "First Name" value = "<?php getInputValue("firstName");?>" required>

                    <?php echo $account -> getError(Constants::$lastNameCharacters); ?>
                    <input type = "text" id = "lastName" name = "lastName" placeholder = "Last Name" value = "<?php getInputValue("lastName");?>" required>

                    <?php echo $account -> getError(Constants::$usernameCharacters); ?>
                    <?php	echo $account -> getError(Constants::$usernameTaken); ?>
                    <input type = "text" id = "username" name = "username" placeholder = "Username" value = "<?php getInputValue("username");?>" required>

                    <?php echo $account -> getError(Constants::$numberCharacters); ?>
                    <?php echo $account -> getError(Constants::$numberTaken); ?>
                    <input type = "text" id = "number" name ="number" placeholder = "Mobile Number" value = "<?php getInputValue("number");?>" required>

                    <?php echo $account -> getError(Constants::$emailsDontMatch); ?>
                    <?php echo $account -> getError(Constants::$emailInvalid); ?>
                    <?php echo $account -> getError(Constants::$emailTaken); ?>
                    <input type = "email" id = "email" name = "email" placeholder = "Email" value = "<?php getInputValue("email");?>" required>
                    <input type = "email" id = "email2" name = "email2" placeholder = "Confirm Email" value = "<?php getInputValue("email2");?>" required>

                    <?php echo $account -> getError(Constants::$passwordsDontMatch); ?>
                    <?php echo $account -> getError(Constants::$passwordLength); ?>
                    <input type = "password" id = "password" name = "password" placeholder = "Password" required>
                    <input type = "password" id = "password2" name = "password2" placeholder = "Confirm Password" required>

                    <?php echo $account -> getError(Constants::$captchaFailed); ?>
                    <input class = "captcha" type = "text" name = "captcha" placeholder = "Captcha"><img id="captcha" src="captcha.php" class = "imgcaptcha" />
                        <button class = "refresh" id="refcaptcha" onclick = "refreshcaptcha()">Refresh</button>
                    <input type = "submit" name = "submitButton" value = "SUBMIT">
                </form>


                <a href = "login.php" class = "signInMessage"> Already have an account? Sign in here! </a>
            </div>
        </div>
    </body>
    <script>
    function refreshcaptcha(){
      var req = new XMLHttpRequest();
      req.open("GET","captcha.php?t=" + Math.random(),true);
      req.setRequestHeader("Content-type","application/x-ww-form-urlencoded");
      req.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200){
          var image = document.getElementById("captcha");
          console.log(this.responseText);
          image.src = 'captcha.php?id=' + Math.random();
        }
      };
      req.send();
    }
    </script>
</html>
