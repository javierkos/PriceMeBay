<?php
include_once 'connectDB.php';
include_once 'constants.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['username'], $_POST['email'], $_POST['pass'])) {

    $user = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Check if email is correctly formed
        echo "invalidemail"; return;
    }
 
    $sqlPrep = "SELECT user_id FROM users WHERE email = ? LIMIT 1";
    $sql = $mysqli->prepare($sqlPrep);

    if ($sql) { //Check if email taken
        $sql->bind_param('s', $email);
        $sql->execute();
        $sql->store_result();
 
        if ($sql->num_rows == 1) {
            echo "emailrepeat"; return;
        }
        $sql->close();
    } else {
        echo mysqli_error($mysqli); return;
    }
 
    $sqlPrep = "SELECT user_id FROM users WHERE username = ? LIMIT 1";
    $sql = $mysqli->prepare($sqlPrep);
 
    if ($sql) { //Check if username taken 
        $sql->bind_param('s', $user);
        $sql->execute();
        $sql->store_result();

        if ($sql->num_rows == 1) {
            echo "userrepeat"; return;
        }
        $sql->close();
    } else {
        mysqli_error(); return;
    }
 
    // Crear una sal aleatoria.
    //$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE)); // Did not work
    $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

    // Crea una contraseña con sal. 
    $pass = hash('sha512', $pass . $random_salt);

    // Inserta el nuevo usuario a la base de datos.  
    if ($sqlInsert = $mysqli->prepare("INSERT INTO users (username, email, password, salt) VALUES (?, ?, ?, ?)")) {
        $sqlInsert->bind_param('ssss', $user, $email, $pass, $random_salt);
        // Ejecuta la consulta preparada.
        if (! $sqlInsert->execute()) {
            mysqli_error(); return;
        }else{
            echo "success"; return;
        }
    }
}else{
    echo "blops";
}