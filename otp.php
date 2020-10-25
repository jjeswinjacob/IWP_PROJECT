<?php
  require_once("includes/classes/otpget.php");
  require_once("includes/config.php");
  require_once("includes/textlocal.class.php");
  echo "OTP sent sucessfully to the registered mobile number";
  if (isset($_POST["otpsub"])){
    if ($_COOKIE["otp"] == $_POST["otpver"]){
        header("Location: index.php");
    }
    else{
      echo "OTP is incorrect";
    }
  }
  if (isset($_POST["reotp"])){
    $reget = new otpget($con,$_SESSION["userLoggedIn"]);
    $address = $reget->getcred();
    $number = array($address);
    $otp = $reget->generate_otp();
    setcookie('otp',$otp,time() + 120);
    $reget->send($number, $otp);
    echo "OTP resend successfull";
    }
 ?>

<!DOCTYPE HTML>
<html>
<link rel = "stylesheet" type = "text/css" href = "assets/style/style.css">
<body>
  <div class = "signInContainer">
      <div class = "column">
          <div class = "header">
  <form method ="POST" action="">
  <h1 class="otpheader"> ENTER YOUR OTP </h1>
  <input type="text" name="otpver"><br>
  <input  type="submit" name="otpsub" value="Submit" class="sendotpbutton">
  <input type="submit" name="reotp" value="Resend" class="reotpbutton">
  </form>
</div>
</div>
</div>
