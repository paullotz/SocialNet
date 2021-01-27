<?php
include_once 'utility/classes.php';
$db = include  "config/dbaccess.php";

if (isset($_SESSION['username']));
$username = $_SESSION["username"];
?>

<br>
<h3>Profilbild ändern</h3>
<hr>
<form class="form-inline" enctype="multipart/form-data" method="post" action="index.php?page=editdata&upload">
    <div class="form-group mx-sm-3 mb-2">
        <input type="file" class="btn" name="controlFile" accept=".png, .jpg, .jpeg">
        <input type="submit" value="Profilbild hochladen" name="uploadFile"/>
    </div>
</form>

<?php if ($db->checkProfilePicture($username)) {
    // Profile picture is set
    $path = "/SocialNet/user_dirs/" . $username . "/ppicture.png";
    echo '<img class="rounded-circle" width="100" src=' . $path . ' alt="User Profile Picture">';
} else {
    $defaultPath = "/SocialNet/res/img/default_user.png";
    echo '<img width="100" src=' . $defaultPath . ' alt="Default User Profile Picture">';
}
?>

<br><br>
<form action="index.php?page=editdata" method="post">
    <div class="form-group mx-sm-3 mb-2">
        <input type="submit" name="deleteProfilePicture" value="Profilbild löschen">
    </div>
</form>

<br><br>
<h3>User-Daten editieren</h3>
<form action="index.php?page=editdata" method="post">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Beschriftung</th>
                <th scope="col">Feld</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row"><label for="anrede">ID:</label></th>
                <?php
                echo "<td><input type='text' class='form-control' id='id' name='id' value='{$userToEdit}' readonly></td>";
                ?>
            </tr>
            <tr>
                <th scope="row"><label for="anrede">Anrede:</label></th>
                <?php
                echo "<td><input type='text' class='form-control' id='anrede' name='anrede' value='{$edit_anrede}'></td>";
                ?>
            </tr>
            <tr>
                <th scope="row"><label for="fname">Vorname:</label></th>
                <?php
                echo "<td><input type='text' class='form-control' id='fname' name='fname' required value='{$edit_fname}'></td>";
                ?>
            </tr>

            <tr>
                <th scope="row"><label for="name">Name:</label></th>
                <?php
                echo "<td><input type='text' class='form-control' id='name' name='name' required value='{$edit_name}'></td>";
                ?>
            </tr>
            <tr>
                <th scope="row"><label for="adress">Adresse:</label></th>
                <?php
                echo "<td><input type='text' class='form-control' id='adress' name='adress' required value='{$edit_adress}'></td>";
                ?>
            </tr>
            <tr>
                <th scope="row"><label for="plz">Postleitzahl:</label></th>
                <?php
                echo "<td><input type='number' class='form-control' id='plz' name='plz' Min=1000 Max=9999 required value='{$edit_plz}'></td>";
                ?>
            </tr>
            <tr>
                <th scope="row"><label for="ort">Ort:</label></th>
                <?php
                echo "<td><input type='text' class='form-control' id='ort' name='ort' required value='{$edit_ort}'></td>";
                ?>         
            </tr>
            <tr>
                <th scope="row"><label for="username">Username</label></th>
                <?php
                echo "<td><input type='text' class='form-control' id='username' name='username' readonly  value='{$edit_username}'></td>";
                ?>     
            </tr>
            <tr>
                <th scope="row"><label for="email">E-Mail:</label></th>
                <?php
                echo "<td><input type='text' class='form-control' id='email' name='email' required value='{$edit_email}'></td>";
                ?>       
            </tr>
            <tr>
                <th scope="row"><div class="input-group">
                        <input type="reset" value="Abbrechen">
                    </div></th>
                <td><div class="input-group">
                        <input type="submit" name="editUser" value="Edit">
                    </div></td>
            </tr>
        </tbody>
    </table>
</form>

<br>
<h3>Passwort ändern</h3>

<form action="index.php?page=editdata" method="post">
<?php echo "<td><input type='hidden' class='form-control' id='id' name='id' value='{$userToEdit}' readonly></td>"; ?>
    <table class="table">
        <tbody>
            <tr>
                <th scope="row"><label for="oldpass">Altes Passwort:</label></th>
                <th scope="row"><input type='text' class='form-control' id='oldpass' name='oldpass' required></th>
            </tr>

            <tr>
                <th scope="row"><label for="newpass">Neues Passwort:</label></th>
                <th scope="row"><input type='password' class='form-control' id='newpass' name='newpass' required></th>
            </tr>
            <tr>
                <th scope="row"><label for="newpassrepeat">Neues Passwort (wiederholen):</label></th>
                <th scope="row"><input type='password' class='form-control' id='newpassrepeat' name='newpassrepeat' required></th>
            </tr>
            <tr>
                <th scope="row"><div class="input-group">
                        <input type="reset" value="Abbrechen">
                    </div></th>
                <td><div class="input-group">
                        <input type="submit" name="changePassword" value="Passwort ändern">
                    </div></td>
            </tr>
        </tbody>
    </table>
</form> 

<?php
$page = $_SERVER['PHP_SELF'];
$page = $page . "?page=editdata";
$setChangePW = isset($_POST['changePassword']);
$setdelPic = isset($_POST["deleteProfilePicture"]);
$setUpload = isset($_GET['upload']);

$username = $_SESSION["username"];
$path = 'user_dirs/' . $username . '/';
$vz = new Verzeichnis($path);

if ($setUpload) {
    $uploadFile = $vz->uploadProfilePicture($_FILES);

    if ($uploadFile) {
        if ($db->setProfilePicture($username)) {
            Message::printMessage("success", "Profilbild wurde geuploadet!");
            header("Refresh:0; url=index.php?page=editdata");
        } else {
            Message::printMessage("", "Profilbild wurde nicht geuploadet!");
        }
    } else {
        Message::printMessage("", "Profilbild wurde nicht geuploadet!");
    }

    echo("<script>location.href = '{$page}'</script>");
}

if ($setdelPic) {
    if ($vz->deleteProfilePicture($path . "ppicture.png")) {
        if ($db->removeProfilePicture($username)) {
            Message::printMessage("success", "Profilbild wurde gelöscht.");
        } else {
            Message::printMessage("", "Profilbild konnte nicht gelöscht werden!");
        }
    } else {
        Message::printMessage("", "Profilbild konnte nicht gelöscht werden!");
    }

    echo("<script>location.href = '{$page}'?page=editdata;</script>");
}

if ($setChangePW) {
    $userId = $_POST['id'];
    $oldPass = $_POST['oldpass'];
    $newPass = $_POST['newpass'];
    $newPassRepeat = $_POST['newpassrepeat'];

    $boolCompare = $db->comparePassword($oldPass, $userId);

    if ($boolCompare) {
        // Old password input and stored password are the Same
        if ($newPass != $newPassRepeat) {
            Message::printMessage('', 'Die neuen Passwörter stimmen nicht überein!');
        } else {
            $uppercase = preg_match('@[A-Z]@', $newPass);
            $lowercase = preg_match('@[a-z]@', $newPass);
            $number = preg_match('@[0-9]@', $newPass);

            if (!$uppercase || !$lowercase || !$number || strlen($newPass) < 8) {
                Message::printMessage('', 'Neues Passwort muss mindestens 8 Zeichen lang sein und sollte mindestens einen Großbuchstaben und Kleinbuchstaben sowie eine Zahl enthalten!');
            } else {
                echo $newPass;
                $boolPassChanged = $db->insertPasswordById($newPass, $userId);
                if ($boolCompare) {
                    Message::printMessage("success", "Das Passwort erfüllt alle Kriterien und wurde geändert!");
                }
            }
        }
    } else {
        Message::printMessage('', 'Altes Passwort stimmt nicht mit dem gespeicherten überein.');
    }
}

$setEditUser = isset($_POST["editUser"]);
if ($setEditUser) {
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