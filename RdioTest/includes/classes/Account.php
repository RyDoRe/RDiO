<?php
  class Account{

    private $con;
    private $errorArray;

    public function __construct($con){
      $this -> con = $con;
      $this -> errorArray = array();
    }

    public function login($username,$password){

      $password = md5($password);

      $query = mysqli_query($this->con,"SELECT * FROM users WHERE username='$username' AND password='$password'");

      if(mysqli_num_rows($query) == 1) {
        return true;
      }else{
        array_push($this->errorArray, Constants::$loginFailed);
      }
    }

    public function register($registerUserName, $firstName, $lastName, $email, $emailConfirmation, $registerPassword, $passwordConfirmation){
      $this -> validateUsername($registerUserName);
      $this -> validateFirstName($firstName);
      $this -> validateLastName($lastName);
      $this -> validateEmails($email, $emailConfirmation);
      $this -> validatePasswords($registerPassword, $passwordConfirmation);

      if(empty($this->errorArray)){

        return $this->insertUserDetails($registerUserName, $firstName, $lastName, $email, $registerPassword);
        //Insert into DB
      }else{
        return false;
      }
    }

    public function getError($error){
      if(!in_array($error, $this->errorArray)){
        $error = "";
      }
      return "<span class='errorMessage'>$error</span>";
    }

    private function insertUserDetails($un, $fn, $ln, $em, $pw){
      $encryptedPw = md5($pw);
      $profilePic = "assets/images/profile-pics/default-user-image.png";
      $date = date("Y-m-d");

      $result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");

      return $result;
    }

    private function validateUsername($username){

      if(strlen($username) > 25 || strlen($username) < 5){
        array_push($this->errorArray, Constants::$userNameCharacters);
        return;
      }

      $checkUsernameQuery =mysqli_query($this->con, "SELECT username FROM users WHERE username='$username'");
      if(mysqli_num_rows($checkUsernameQuery) != 0){
        array_push($this->errorArray, Constants::$userNameTaken);
        return;
      }
    }

    private function validateFirstName($firstName){
      if(strlen($firstName) > 25 || strlen($firstName) < 2){
        array_push($this->errorArray, Constants::$firstNameCharacters);
        return;
      }
    }

    private function validateLastName($lastName){
      if(strlen($lastName) > 25 || strlen($lastName) < 2){
        array_push($this->errorArray, Constants::$lastNameCharacters);
        return;
      }
    }

    private function validateEmails($email, $confirmEmail){
      if($email != $confirmEmail){
        array_push($this->errorArray, Constants::$emailsDoNotMatch);
        return;
      }
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        array_push($this->errorArray, Constants::$emailInvalid);
        return;
      }

      $checkEmailQuery =mysqli_query($this->con, "SELECT email FROM users WHERE email='$email'");
      if(mysqli_num_rows($checkEmailQuery) != 0){
        array_push($this->errorArray, Constants::$emailTaken);
        return;
      }
    }

    private function validatePasswords($password, $confirmPassword){

      if($password != $confirmPassword){
        array_push($this->errorArray, Constants::$passwordsDoNotMatch);
        return;
      }

      if(preg_match('/[^A-Za-z0-9]/', $password)){
        array_push($this->errorArray, Constants::$passwordNotAlphaNumeric);
        return;
      }

      if(strlen($password) > 30 || strlen($password) < 5){
        array_push($this->errorArray, Constants::$passwordCharacters);
        return;
      }

    }
  }


 ?>
