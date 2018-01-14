<?php
include_once 'connectDB.php';
include_once 'functions.php';

sec_session_start();
 
if (isset($_POST['username'], $_POST['pass'])) {
    $user = $_POST['username'];
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
    echo login($user, $pass,$mysqli);
}else {
    echo 'posterror';
}

function login($user, $pass,$mysqli) {
    if ($sql = $mysqli->prepare("SELECT user_id, username, password, salt FROM users WHERE username = ? LIMIT 1")) {
        //Prepared SQL queries prevent SQL injections
        $sql->bind_param('s', $user);
        $sql->execute();   
        $sql->store_result();
        $sql->bind_result($user_id, $username, $realpass, $salt);//Retrieve results
        $sql->fetch();
        $pass = hash('sha512', $pass . $salt); //Hash our password using the retrieved salt
        if ($sql->num_rows == 1) {
            if ($realpass == $pass) { //If password matches
                // Obtén el agente de usuario del usuario.
                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                //  Protección XSS ya que podríamos imprimir este valor.
                $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                $_SESSION['user_id'] = $user_id;
                // Protección XSS ya que podríamos imprimir este valor.
                $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                            "", 
                                                            $username);
                $_SESSION['username'] = $username;
                $_SESSION['login_string'] = hash('sha512', 
                            $pass . $user_browser);
                return "success"; //Logged in
            }else {
                return "wrong"; //Wrong password
            }
        }else {
            return "wrong"; //User does not exist
        }
    }else{
        return "error";
    }
}