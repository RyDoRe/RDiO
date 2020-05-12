<?php
  if(isset($_POST['loginButton'])){
    //login was pressed
    $username = $_POST['loginUserName'];
    $password = $_POST['loginPassword'];

    $result = $account->login($username,$password);

    if($result){
      $_SESSION['userLoggedIn'] = $username;
      header("Location: index.php");
    }

  }

 ?>
