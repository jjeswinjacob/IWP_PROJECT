<?php
require_once("includes/textlocal.class.php");
class otpget{
  private $con,$username;

  public function __construct($con, $username){
    $this -> con = $con;
    $this -> username = $username;
  }

  public function getcred(){
    $sql = "SELECT * FROM users WHERE username = :uname";
    $query = $this-> con -> prepare($sql);
    $query->bindvalue(":uname", $this-> username);
    $query -> execute();
    while($result = $query -> fetch(PDO:: FETCH_ASSOC)){
      return $result["phone"];
    }
  }

  public function generate_otp(){
    $rno = rand(1000,9999);
    return $rno;
  }

  public function send($number,$otp){
    $textlocal = new TextLocal(false,false,'420ms57osDk-JNNHCNlXMzHlznelYFYCypfDwfWLZv');
    $message = "Hello your OTP for Infinity is: " . $otp ." Thank you!";
    $sender = "TXTLCL";
    try{
      $send = $textlocal->sendSms($number,$message,$sender);
      echo "OTP sucessfully sent to the registered mobile number...";
    } catch (Exception $e){
      die("Error :". $e->getMessage());
    }
  }
}
?>
