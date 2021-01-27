<br>
<div class="container">
    <form action="index.php?page=register" method="post">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Beschriftung</th>
                    <th scope="col">Feld</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"><label for="anrede">Anrede:</label></th>
                    <td><input type="text" class="form-control" placeholder="Frau" id="anrede" name="anrede" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="fname">Vorname:</label></th>
                    <td><input type="text" class="form-control" placeholder="Max" id="fname" name="fname" required></td>
                </tr>

                <tr>
                    <th scope="row"><label for="name">Name:</label></th>
                    <td><input type="text" class="form-control" placeholder="Mueller" id="name" name="name" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="adress">Adresse:</label></th>
                    <td><input type="text" class="form-control" placeholder="Die Straße 99/1" id="adress" name="adress" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="plz">Postleitzahl:</label></th>
                    <td><input type="number" class="form-control" placeholder="1220" id="plz" name="plz" Min="1000" Max="9999" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ort">Ort:</label></th>
                    <td><input type="text" class="form-control" placeholder="Wien" id="ort" name="ort" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="username">Username</label></th>
                    <td><input type="text" class="form-control" placeholder="paul5" id="username" name="username" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="password">Passwort (nur Buchstaben und Zahlen):</label></th>
                    <td><input type="password" class="form-control" id="password" name="password"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="passwordRepeat">Passwort (wiederholen):</label></th>
                    <td><input type="password" class="form-control" id="passwordRepeat" name="passwordRepeat"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="email">E-Mail:</label></th>
                    <td><input type="email" class="form-control" placeholder="max.mueller@gmail.com" id="email" name="email" required></td>
                </tr>
                <tr>
                    <th scope="row"><div class="input-group">
                            <input type="reset" value="Abbrechen">
                        </div></th>
                    <td><div class="input-group">
                            <input type="submit" name="registerNewUser" value="Abschicken">
                        </div></td>
                </tr>
            </tbody>
        </table>
    </form> 

    <?php
    $page = $_SERVER['PHP_SELF'];

    require_once "utility/classes.php";
    $db = include "config/dbaccess.php";

    $error_counter = 0;
    $registerBtn_clicked = isset($_POST["registerNewUser"]);

    if ($registerBtn_clicked) {
        $anrede = $db->checkInput($_POST["anrede"]);
        $fname = $db->checkInput($_POST["fname"]);
        $adress = $db->escape($_POST["adress"]);
        $name = $db->checkInput($_POST["name"]);
        $plz = $db->checkInput($_POST["plz"]);
        $ort = $db->checkInput($_POST["ort"]);
        $username = $db->checkInput($_POST["username"]);
        $password = $db->checkInput($_POST["password"]);
        $passwordRepeat = $db->checkInput($_POST["passwordRepeat"]);
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

        if ($password === $passwordRepeat) {
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number = preg_match('@[0-9]@', $password);

            if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
                Message::printMessage('', 'Das Passwort muss mindestens 8 Zeichen lang sein und sollte mindestens einen Großbuchstaben und Kleinbuchstaben sowie eine Zahl enthalten!');
            } else {
                $password = crypt($password, '$2a$07$usesomesillystringforsalt$');
                $userToRegister = new User(0, $anrede, $fname, $name, $adress, $plz, $ort, $username, $password, $email);
                $result = $db->registerUser($userToRegister);

                if ($result) {
                    echo "<br>";
                    Message::printMessage("success", "User wurde registriert!");

                    echo("<script>location.href = '{$page}';</script>");
                } else {
                    echo "<br>";
                    Message::printMessage("", "User existiert bereits");
                }
            }
        } else {
            echo "<br>";
            Message::printMessage("", "Passwort stimmt nicht überein!");
        }
    }
    ?>
</div>