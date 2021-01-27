<?php
include_once "utility/DB.class.php";
include_once "utility/Message.class.php";
include_once "model/User.class.php";

$db = include "config/dbaccess.php";
?>
<br>
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Anrede</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Adresse</th>
                <th>Postleitzahl</th>
                <th>Ort</th>
                <th>Username</th>
                <th>E-Mail</th>
                <th>User Löschen</th>
                <th>Userdaten Bearbeiten</th>
                <th>Passwort zurücksetzen</th>
                <th>Beiträge anzeigen lassen</th>
                <th>Benutzer Status Ändern</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $usersArray = array();

            $usersArray = $db->getUserList();

            foreach ($usersArray as $user) {
                $status = $db->checkStatus($user->username);

                echo "<tr>";
                echo '<td>' . $user->id . '</td>';
                echo '<td>' . $user->anrede . '</td>';
                echo '<td>' . $user->fname . '</td>';
                echo '<td>' . $user->name . '</td>';
                echo '<td>' . $user->adress . '</td>';
                echo '<td>' . $user->plz . '</td>';
                echo '<td>' . $user->ort . '</td>';
                echo '<td>' . $user->username . '</td>';
                echo '<td>' . $user->email . '</td>';
                echo '<td><a href="index.php?page=ua&delete=' . $user->id . '">Löschen</a></td>';
                echo '<td><a href="index.php?page=ua&edit=' . $user->id . '">Bearbeiten</a></td>';
                echo '<td><a href="index.php?page=ua&rpassword=' . $user->email . '">Zurücksetzen</a></td>';
                echo '<td><a href="index.php?page=ua&sposts=' . $user->id . '">Anzeigen</a></td>';
                if ($status == "active") {
                    echo '<td><a href="index.php?page=ua&setinactive=' . $user->username . '">Aktiv</a></td>';
                } else {
                    echo '<td><a href="index.php?page=ua&setactive=' . $user->username . '">Inaktiv</a></td>';
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<br>

<?php
$setShowUserPosts = isset($_GET["sposts"]);
if ($setShowUserPosts) {
    $userId = $_GET["sposts"];
    $postsArray = array();

    $postsArray = $db->showUsersPosts($userId);

    if (empty($postsArray)) {
        Message::printMessage("","Der ausgewählte User hat bis jetzt noch nichts gepostet!");
    } else {

    ?>
<br>
<div class="container">
    <table class="table">
        <thead>
        <tr>
            <th>Post-Id</th>
            <th>User-Id</th>
            <th>Content</th>
            <th>Bildpfad</th>
            <th>Tag</th>
            <th>Freigabe</th>
            <th>Zeit</th>
            <th>Beitrag löschen</th>
            <th>Beitrag bearbeiten</th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($postsArray as $post) {
            echo "<tr>";
            echo '<td>' . $post->postId. '</td>';
            echo '<td>' . $post->userId . '</td>';
            echo '<td>' . $post->content . '</td>';
            echo '<td>' . $post->picture . '</td>';
            echo '<td>' . $post->hashtag . '</td>';
            echo '<td>' . $post->freigabe . '</td>';
            echo '<td>' . $post->timestamp . '</td>';
            echo '<td><a href="index.php?page=ua&pdel=' . $post->postId . '">Löschen</a></td>';
            echo '<td><a href="index.php?page=ua&pedit=' . $post->postId . '">Bearbeiten</a></td>';
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php
    }
}

$page = $_SERVER['PHP_SELF'];
$page = $page . "?page=ua";

$setInactive = isset($_GET["setinactive"]);
if ($setInactive) {
    $username = $_GET["setinactive"];

    if ($db->setInactive($username)) {
        echo("<script>location.href = '{$page}';</script>");
    } else {
        Message::printMessage("", "User konnte nicht auf inaktiv gesetzt werden!");
    }
}

$setActive = isset($_GET["setactive"]);
if ($setActive) {
    $username = $_GET["setactive"];

    if ($db->setActive($username)) {
        echo("<script>location.href = '{$page}';</script>");
    } else {
        Message::printMessage("", "User konnte nicht auf aktiv gesetzt werden!");
    }
}

$setResetPassword = isset($_GET["rpassword"]);
if ($setResetPassword) {
    $email = $_GET["rpassword"];

    $generatePassword = $db->generatePassword();

    $to_email = $email;
    $subject = 'Neues Passwort';
    $message = 'Neues Passwort: ' . $generatePassword;
    $headers = 'From: SocialNet';
    mail($to_email, $subject, $message, $headers);

    $db->insertPasswordByEmail($generatePassword, $email);

    echo '<br>';
    echo '<div class="alert alert-success" role="alert">';
    echo 'Neues Passwort wurde dem User per E-Mail verschickt!';
    echo '<br>';
    echo 'Neues Passwort: ' . $generatePassword;
    echo '</div>';

}

if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $result = $db->deleteUser($id);

    if ($result) {
        echo("<script>location.href = '{$page}';</script>");
    } else {
        Message::printMessage("","User wurde nicht gelöscht!");
    }
}

if (isset($_GET["edit"])) {
    $userToEdit = $_GET["edit"];

    $sql = "SELECT * FROM users WHERE id = $userToEdit";
    $result = $db->conn->query($sql);

    if ($result) {
        while ($user = $result->fetch_object()) {
            $edit_anrede = $user->anrede;
            $edit_fname = $user->fname;
            $edit_name = $user->name;
            $edit_adress = $user->adress;
            $edit_plz = $user->plz;
            $edit_ort = $user->ort;
            $edit_username = $user->username;
            $edit_password = $user->password;
            $edit_email = $user->email;
        }

        include "inc/edit_form.php";
    } else {
        Message::printMessage("","User kann nicht editiert werden!");
    }
}

if (isset($_POST["editUser"])) {
    $id = $_POST["id"];
    $anrede = $db->checkInput($_POST["anrede"]);
    $fname = $db->checkInput($_POST["fname"]);
    $adress = $db->escape($_POST["adress"]);
    $name = $db->checkInput($_POST["name"]);
    $plz = $db->checkInput($_POST["plz"]);
    $ort = $db->checkInput($_POST["ort"]);
    $username = $db->checkInput($_POST["username"]);
    $password = $db->checkInput($_POST["password"]);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

    $editUser = new User($id, $anrede, $fname, $name, $adress, $plz, $ort, $username, $password, $email);

    $result = $db->updateUser($editUser);

    if ($result) {
        echo("<script>location.href = '{$page}';</script>");
    } else {
        Message::printMessage("", "Userdaten wurden nicht geändert!");
    }
}
