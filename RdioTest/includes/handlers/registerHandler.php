<?php


function sanitizeFormUsername($inputText) {
  $inputText = strip_tags($inputText);
  $inputText = str_replace(" ", "", $inputText);
  return $inputText;
}

function sanitizeFormString($inputText) {
  $inputText = strip_tags($inputText);
  $inputText = str_replace(" ", "", $inputText);
  $inputText = ucfirst(strtolower($inputText));
  return $inputText;
}

function sanitizeFormPassword($inputText) {
  $inputText = strip_tags($inputText);
  return $inputText;
}



if(isset($_POST['registerButton'])){
  //Register Button was Pressed
  $registerUserName = sanitizeFormUsername($_POST['registerUserName']);
  $firstName = sanitizeFormString($_POST['firstName']);
  $lastName = sanitizeFormString($_POST['lastName']);
  $email = sanitizeFormString($_POST['email']);
  $emailConfirmation = sanitizeFormString($_POST['emailConfirmation']);
  $registerPassword = sanitizeFormPassword($_POST['registerPassword']);
  $passwordConfirmation = sanitizeFormPassword($_POST['passwordConfirmation']);

  $wasSuccessful = $account -> register($registerUserName, $firstName, $lastName, $email, $emailConfirmation, $registerPassword, $passwordConfirmation);

  if($wasSuccessful){
    $_SESSION['userLoggedIn'] = $registerUserName;
    header("Location: index.php");
  }







}

 ?>
