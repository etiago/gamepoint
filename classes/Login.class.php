<?php

//error_reporting(E_ALL);

//ini_set('display_errors','1');

require_once '../vendor/propel/propel1/runtime/lib/Propel.php';

// Initialize Propel with the runtime configuration
Propel::init("../build/conf/gamepoint-conf.php");

// Add the generated 'classes' directory to the include path
set_include_path("../build/classes" . PATH_SEPARATOR . get_include_path());

class Login {
    public static function parsePOST($login) {
        if ($login['action'] == "register") {
            return Login::registerUser($login['username'],$login['password']);
        } else if ($login['action'] == "login") {
            return Login::loginUser($login['username'],$login['password']);
        }
    }
    
    private static function isUsernameSane($username) {
        $username_length = strlen($username);

        if ($username_length < 3 || $username_length > 15) {
            return false;
        }

        if (!preg_match("/^[a-zA-Z0-9]+$/",$username)) {
            return false;
        } 

        return true;
    }

    private static function isPasswordSane($password) {
        $pw_length = strlen($password);

        if ($pw_length < 6 || $pw_length > 100) {
            return false;
        }

        if (!preg_match("/[^a-zA-Z0-9_]+/",$password)) {
            return false;
        }

        return true;
    }

    public static function registerUser($username, $password) {
        if (!Login::isUsernameSane($username)
             || !Login::isPasswordSane($password)) {
            $retVal = array('result'=>'failure');

            return json_encode($retVal);
        }
        
        $user = new User();
        $user->setUsername($username);
        $user->setSalt(bin2hex(openssl_random_pseudo_bytes(6)));
        $user->setPassword(hash('sha256',$password.$user->getSalt()));
        try {
            $user->save();
        } catch (Exception $ex) {
            $retVal = array('result'=>'failure');
            return json_encode($retVal);
        }
        
        $retVal = array('result'=>'success','username'=>$username);
        return json_encode($retVal);
    }

    public static function loginUser($username, $password) {
        $user = UserQuery::create()->findOneByUsername($username);

        if (!$user) {
            $retVal = array('result'=>'failure');

            return json_encode($retVal);
        }

        $loginPassword = hash('sha256',$password.$user->getSalt());

        if ($loginPassword == $user->getPassword()) {
            $retVal = array('result'=>'success','username'=>$username);

            return json_encode($retVal);
        } else {
            $retVal = array('result'=>'failure');

            return json_encode($retVal);
        }
    }
}

