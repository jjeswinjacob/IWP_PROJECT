<?php
    class Account {
        private $con;
        private $errorArray = array();

        public function __construct($con) {
            $this->con = $con;
        }

        public function register($fn, $ln, $un, $pnum, $em, $em2, $pw, $pw2,$c) {
            $this -> validateFirstName($fn);
            $this -> validateLastName($ln);
            $this -> validateUsername($un);
            $this -> validateNumber($pnum);
            $this -> validateEmails($em, $em2);
            $this -> validatePasswords($pw, $pw2);
            $this -> validateCaptcha($c);


            if(empty($this -> errorArray)) {
                return $this -> insertUserDetails($fn, $ln, $un, $pnum, $em, $pw);
            }

            return false;
        }

        public function login($un, $pw) {
            $pw = hash("sha512", $pw);

            $query = $this -> con -> prepare("SELECT * FROM users WHERE username = :un AND password = :pw");
            $query -> bindValue(":un", $un);
            $query -> bindValue(":pw", $pw);
            $query -> execute();

            if($query -> rowCount() == 1) {
                return true;
            }
            array_push($this -> errorArray, Constants::$loginFailed);
            return false;
        }

        private function insertUserDetails($fn, $ln, $un, $pnum, $em, $pw) {
            $pw = hash("sha512", $pw);
            $query = $this -> con -> prepare("INSERT INTO users (firstName, lastName, username, phone, email, password)
                                                VALUES (:fn, :ln, :un, :pnum, :em, :pw)");
            $query -> bindValue(":fn", $fn);
            $query -> bindValue(":ln", $ln);
            $query -> bindValue(":un", $un);
            $query -> bindValue(":pnum", $pnum);
            $query -> bindValue(":em", $em);
            $query -> bindValue(":pw", $pw);

            return $query -> execute();
        }

        // We are only calling these 2 functions from within the class
        private function validateFirstName($fn) {
            if(strlen($fn) < 2 || strlen($fn) > 25) {
                array_push($this -> errorArray, Constants::$firstNameCharacters);
            }
        }

        private function validateLastName($ln) {
            if(strlen($ln) < 2 || strlen($ln) > 25) {
                array_push($this -> errorArray, Constants::$lastNameCharacters);
            }
        }

        private function validateUsername($un) {
            if(strlen($un) < 2 || strlen($un) > 25) {
                array_push($this -> errorArray, Constants::$usernameCharacters);
                return;
            }

            // A prepared statement - Preparing SQL statement - and underneath we're binding
            // the parameter value to un. More secure - Less at risk to SQL Injection
            // Where values injected to queries
            $query = $this -> con -> prepare("SELECT * FROM users WHERE username = :un");
            $query -> bindValue(":un", $un);

            $query -> execute();
            if($query -> rowCount() != 0) {
                array_push($this -> errorArray, Constants::$usernameTaken);
            }
        }

        private function validateNumber($pnum) {
            if(strlen($pnum) != 10) {
                array_push($this -> errorArray, Constants::$numberCharacters);
                return;
            }

            $query = $this -> con -> prepare("SELECT * FROM users WHERE phone = :pnum");
            $query -> bindValue(":pnum", $pnum);

            $query -> execute();
            if($query -> rowCount() != 0) {
                array_push($this -> errorArray, Constants::$numberTaken);
            }
        }

        private function validateEmails($em, $em2) {
            if($em != $em2) {
                array_push($this -> errorArray, Constants::$emailsDontMatch);
                return;
            }

            if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                array_push($this -> errorArray, Constants::$emailInvalid);
                return;
            }

            $query = $this -> con -> prepare("SELECT * FROM users WHERE email = :em");
            $query -> bindValue(":em", $em);

            $query -> execute();
            if($query -> rowCount() != 0) {
                array_push($this -> errorArray, Constants::$emailTaken);
            }
        }

        private function validatePasswords($pw, $pw2) {
            if($pw != $pw2) {
                array_push($this -> errorArray, Constants::$passwordsDontMatch);
                return;
            }

            if(strlen($pw) < 5 || strlen($pw) > 25) {
                array_push($this -> errorArray, Constants::$passwordLength);
            }
        }
        private function validateCaptcha($c){
          if ($c != $_COOKIE['captcha']){
            array_push($this -> errorArray, Constants:: $captchaFailed);
          }
        }

        public function getError($error) {
            if(in_array($error, $this -> errorArray)) {
                return "<span class = 'errorMessage'> $error </span>";
            }
        }
    }
?>
