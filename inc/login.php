<br>
<div class="container">
    <form action="index.php?page=login" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="input-group">
            <button type="submit" name="login">Login</button>
        </div>
        <br>
        <div class="input-group">
            <button type="submit" name="forgot_pw">Passwort vergessen?</button>
        </div>
    </form>
</div>

<?php
include_once 'utility/Verzeichnis.class.php';
include_once 'utility/DB.class.php';
include_once 'model/User.class.php';
include_once 'utility/Message.class.php';

$folderops = new Verzeichnis('user_dirs', '*');
$db = include "config/dbaccess.php";

$issetForgotPW = isset($_POST['forgot_pw']);
if ($issetForgotPW) {
    include "forgot_pw.php";
}

$issetNewPW = isset($_POST['new_password']);
if ($issetNewPW) {
    $email = $_POST['email'];
    $result = $db->checkEmailExists($email);

    if ($result) {
        // Send Password
        $generatePassword = $db->generatePassword();

        $to_email = $email;
        $subject = 'Neues Passwort';
        $message = 'Neues Passwort: ' . $generatePassword;
        $headers = 'From: Paul Lotz';
        mail($to_email, $subject, $message, $headers);

        $db->insertPasswordByEmail($generatePassword, $email);

        echo '<br>';
        echo '<div class="alert alert-success" role="alert">';
        echo 'Neues Passwort wurde per E-Mail verschickt!';
        echo '<br>';
        echo 'Neues Passwort: ' . $generatePassword;
        echo '</div>';
    } else {
        Message::printMessage('', 'E-Mail existiert nicht!');
    }
}

$issetRegister = isset($_POST['register']);
if ($issetRegister) {
    header('Location: index.php?page=register');
}

$issetLogin = isset($_POST['login']);
if ($issetLogin) {
    $username = $db->escape($_POST['username']);
    $password = $db->escape($_POST['password']);

    $status = $db->checkStatus($username);
    if ($status == "active") {
        $result = $db->loginUser($username, $password);

        if ($result) {
            $_SESSION['username'] = $username;

            // Folder erstellen für File System
            $createFolder = $folderops->createFolder($username);

            header('Location: index.php?page=posts');
        } else {
            Message::printMessage('',
                'Username oder Passwort ist falsch bitte erneut probieren!');
            unset($_SESSION['username']); // login not successful
        }
    } else {
        Message::printMessage("", "Dein Account wurde von einem Administrator auf inaktiv gesetzt!");
    }


}
?>