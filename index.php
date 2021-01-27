<?php
session_start();

require_once "utility/classes.php";
$issetPage = isset($_GET['page']);
?>

<!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="res/css/styles.css">

    <link rel="shortcut icon" href="res/img/favicon.png" />

    <title>SocialNet</title>
</head>

<?php
if ($issetPage) {
    $page = $_GET["page"];
    if ($page != "messages") {
        include 'inc/navigation.php';
    }
} else {
    include 'inc/navigation.php';
}
?>

<body>
    <main>
        <div class="container">
        <?php
        $db = include "config/dbaccess.php";
        $username = "";
        $admin = false;

        if ($issetPage) {
            $currentPage = $_GET['page'];
            $issetUsername = isset($_SESSION['username']);

            if ($issetUsername) {
                $username = $_SESSION["username"];

                if ($db->checkAdmin($username)) {
                    $admin = true;
                }
            }

            switch($currentPage) {
                case "register":
                    include "inc/register_form.php";
                    break;

                case "ua":
                    if ($admin) {
                        include 'inc/user_administration.php';
                    } else {
                        Message::printMessage("", "Du hast keinen Zugriff auf diese Seite");
                    }
                    break;

                case 'logout':
                    if ($issetUsername) {
                        unset($_SESSION['username']);
                        header('Location: index.php?page=login');
                    } else {
                        Message::printMessage("", "Du hast keinen Zugriff auf diese Seite");
                    }
                    break;

                case 'editdata':
                    if ($issetUsername) {
                        $user = $db->getUser($username);
                        $userToEdit = $user->id;
                        $edit_anrede = $user->anrede;
                        $edit_fname = $user->fname;
                        $edit_name = $user->name;
                        $edit_adress = $user->adress;
                        $edit_plz = $user->plz;
                        $edit_ort = $user->ort;
                        $edit_username = $user->username;
                        $edit_email = $user->email;

                        include 'inc/edit_form.php';
                    } else {
                        Message::printMessage("", "Du hast keinen Zugriff auf diese Seite");
                    }
                    break;

                case 'impressum':
                    include 'inc/impressum.php';
                    break;

                case 'chat':
                    if ($issetUsername) {
                        include 'inc/chat.php';
                        
                    } else {
                        Message::printMessage("", "Du hast keinen Zugriff auf diese Seite!");
                    }
                    break;

                case 'messages':
                    if ($issetUsername) {
                        include 'inc/messages.php';
                    } else {
                        Message::printMessage("", "Du hast keinen Zugriff auf diese Seite!");
                    }
                    break;

                case 'login':
                    
                    if ($issetUsername) {
                        Message::printMessage("", "Du bist bereits eingeloggt!");
                    } else {
                        include 'inc/login.php';
                        
                    }
                    break;

                case 'posts':
                    include 'inc/posts.php';
                    break;

                case 'help':
                    include 'inc/help.php';
                    break;

                case 'friends':
                    
                    if ($issetUsername) {
                        include 'inc/friend_list.php';
                        
                    } else {
                        Message::printMessage("","Du hast keinen Zugriff auf diese Seite!");
                    }
                    break;

                default:
                    Message::printMessage("", "Diese Seite existiert leider nicht!");
                    break;
            }
        } else {
            include 'inc/posts.php';
        }
        ?>
        </div>
    </main>
</body>

</html>