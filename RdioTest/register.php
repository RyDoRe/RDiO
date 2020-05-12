<?php
  include("includes/config.php");
  include("includes/classes/Account.php");
  include("includes/classes/Constants.php");

  $account = new Account($con);


  include("includes/handlers/registerHandler.php");
  include("includes/handlers/loginHandler.php");

  function getInputValue($name){
    if(isset($_POST[$name])){
      echo $_POST[$name];
    }
  }

 ?>
<html>
<head>
  <title>RDiO - Test</title>

  <link rel="stylesheet" href="assets/css/register.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript" src="assets/js/register.js"></script>
</head>
<body>

  <?php

    if(isset($_POST['registerButton'])){
      echo '<script type="text/javascript">
              $(document).ready(function(){
                $("#loginForm").hide();
                $("#registerForm").show();
              });
            </script>';
    }else {
      echo '<script type="text/javascript">
              $(document).ready(function(){
                $("#loginForm").show();
                $("#registerForm").hide();
              });
            </script>';
    }


   ?>


  <div id="background">
    <div id="loginContainer">
      <div id = "inputContainer">
        <form id="loginForm" action="register.php" method="POST">
          <h2>Login to your account!</h2>
          <p>
            <?php echo $account->getError(Constants::$loginFailed); ?>
            <label for="loginUserName">Username</label>
            <input id= "loginUserName" name= "loginUserName" type="text" placeholder="e.g. Tom Sawyer" value="<?php getInputValue('loginUserName');?>" required>
          </p>
          <p>
            <label for="loginPassword">Password</label>
            <input id= "loginPassword" name= "loginPassword" type="password" required>
          </p>

          <button type="submit" name="loginButton">Login</button>

          <div class="hasAccountText">
            <span id="hideLogin">Don't have an account yet? Signup here.</span>
          </div>
        </form>


        <form id="registerForm" action="register.php" method="POST">
          <h2>Create your account!</h2>

          <p>
            <?php echo $account->getError(Constants::$userNameCharacters); ?>
            <?php echo $account->getError(Constants::$userNameTaken); ?>
            <label for="registerUserName">Username</label>
            <input id= "registerUserName" name= "registerUserName" type="text" placeholder="e.g. Tom Sawyer" value="<?php getInputValue('registerUserName');?>" required>
          </p>

          <p>
            <?php echo $account->getError(Constants::$firstNameCharacters); ?>
            <label for="firstName">First Name</label>
            <input id= "firstName" name= "firstName" type="text" placeholder="e.g." value="<?php getInputValue('firstName');?>" required>
          </p>

          <p>
            <?php echo $account->getError("Your last name must be between 2 and 25 characters"); ?>
            <label for="lastName">Last Name</label>
            <input id= "lastName" name= "lastName" type="text" placeholder="e.g. Sawyer" value="<?php getInputValue('lastName');?>"  required>
          </p>

          <p>
            <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
            <?php echo $account->getError(Constants::$emailInvalid); ?>
            <?php echo $account->getError(Constants::$emailTaken); ?>
            <label for="email">Email</label>
            <input id= "email" name= "email" type="email" placeholder="e.g. tom.sawyer@yahoo.com" value="<?php getInputValue('email');?>" required>
          </p>

          <p>
            <label for="emailConfirmation">Confirm Email</label>
            <input id= "emailConfirmation" name= "emailConfirmation" type="email" placeholder="e.g. tom.sawyer@yahoo.com" value="<?php getInputValue('emailConfirmation');?>" required>
          </p>





          <p>
            <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
            <?php echo $account->getError(Constants::$passwordNotAlphaNumeric); ?>
            <?php echo $account->getError(Constants::$passwordCharacters); ?>
            <label for="registerPassword">Password</label>
            <input id= "registerPassword" name= "registerPassword" type="password" placeholder="Your password" required>
          </p>

          <p>
            <label for="passwordConfirmation">Confirm Password</label>
            <input id= "passwordConfirmation" name= "passwordConfirmation" type="password" placeholder="Confirm your password!"required>
          </p>

          <button type="submit" name="registerButton">Sign Up</button>

          <div class="hasAccountText">
            <span id="hideRegister">Already have an account? Log in here.</span>
          </div>
        </form>
      </div>

      <div id="loginText">
        <h1>Get great music, right now</h1>
        <h2>Listen to loads of songs for free.</h2>
        <ul>
          <li>Discover music you'll fall in love with</li>
          <li>Create your own playlists</li>
          <li>Follow artists to keep up to date</li>
        </ul>
      </div>

    </div>
  </div>
</body>
</html>
