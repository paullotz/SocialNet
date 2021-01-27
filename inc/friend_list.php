<?php
$db = include "config/dbaccess.php";
$username = $_SESSION["username"];
?>
    <br>
    <div class="card border-dark mb-3" style="margin-bottom: 2em;">
        <h5 class="card-header">Freundschaftsanfrage verschicken</h5>
        <div class="card-body">
            <form method="post" action="index.php?page=friends">
                <div class="form-group">
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"
                              id="basic-addon1">@</span>
                    </div>
                    <input type="text" class="form-control" name="friendName"
                           placeholder="Benutzername"
                           aria-describedby="basic-addon1" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary"
                                name="requestFriend" type="submit">Anfragen
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php
$friendsArray = $db->getFriends($username);
if (!empty($friendsArray)) {
    ?>
    <div class="card border-dark mb-3" style="margin-bottom: 2em;">
        <h5 class="card-header">Meine Freunde</h5>
        <div class="card-body">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Vorname</th>
                    <th scope="col">Nachname</th>
                    <th scope="col">Benutzername</th>
                    <th scope="col">Entfernen</th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach ($friendsArray as $friend_accepted) {
                    $friend_fname = $db->getFNameByUsernames($friend_accepted->friend);
                    $friend_name = $db->getNameByUsernames($friend_accepted->friend);

                    echo "<tr>";
                    echo '<td>' . $friend_fname . '</td>';
                    echo '<td>' . $friend_name . '</td>';
                    echo '<td>' . $friend_accepted->friend . '</td>';
                    echo "<td><a style='color: red;' href='?page=friends&rm=$friend_accepted->friend'><svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-person-x-fill' viewBox='0 0 16 16'>
  <path fill-rule='evenodd' d='M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6.146-2.854a.5.5 0 0 1 .708 0L14 6.293l1.146-1.147a.5.5 0 0 1 .708.708L14.707 7l1.147 1.146a.5.5 0 0 1-.708.708L14 7.707l-1.146 1.147a.5.5 0 0 1-.708-.708L13.293 7l-1.147-1.146a.5.5 0 0 1 0-.708z'/>
</svg>
</a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>

        </div>
    </div>
<?php
} ?>

<?php
$friendRequestArray = $db->getFriendRequests($username);
if (!empty($friendRequestArray)) {
    ?>
    <div class="card border-dark mb-3" style="margin-bottom: 2em;">
        <h5 class="card-header">Meine Freundschaftsanfragen</h5>
        <div class="card-body">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Vorname</th>
                    <th scope="col">Nachname</th>
                    <th scope="col">Benutzername</th>
                    <th scope="col">Akzeptieren</th>
                    <th scope="col">Ablehnen</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $username = $_SESSION["username"];

                $friendsArray = $db->getFriendRequests($username);
                foreach ($friendsArray as $friendreq) {
                    $friend_fname = $db->getFNameByUsernames($friendreq->from_user);
                    $friend_name = $db->getNameByUsernames($friendreq->from_user);

                    echo "<tr>";
                    echo '<td>' . $friend_fname . '</td>';
                    echo '<td>' . $friend_name . '</td>';
                    echo '<td>' . $friendreq->from_user . '</td>';
                    echo "<td><a style='color: green;' href='?page=friends&acc=$friendreq->from_user'><svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-person-plus-fill' viewBox='0 0 16 16'>
  <path d='M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'/>
  <path fill-rule='evenodd' d='M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z'/>
</svg>
</a></td>";
                    echo "<td><a style='color: #ff0000;' href='?page=friends&rj=$friendreq->to_friend'><svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-person-dash-fill' viewBox='0 0 16 16'>
  <path fill-rule='evenodd' d='M11 7.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z'/>
  <path d='M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'/>
</svg>
</a></td>";
                    echo "</tr>";

                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
$page = $_SERVER['PHP_SELF'];

$setRemoveFriend = isset($_GET["rm"]);
if ($setRemoveFriend) {
    $friendToRemove = $_GET["rm"];

    $db->removeFriend($friendToRemove, $username);

    //echo("<script>location.href = '{$page}?page=friends';</script>");
}

$setAcceptFriend = isset($_GET["acc"]);
if ($setAcceptFriend) {
    $friendname = $_GET["acc"];

    $db->acceptFriend($username, $friendname);

    echo("<script>location.href = '{$page}?page=friends';</script>");
}


$setRequestFriend = isset($_POST["requestFriend"]);
if ($setRequestFriend) {
    $friendName = $_POST["friendName"];

    if ($db->checkUsernameExits($friendName)) {
        // Request Friend
        if ($db->checkRequestSent($username, $friendName)) {
            Message::printMessage("", "User wurde bereits angefragt!");
        } else {
            if ($db->requestFriend($username, $friendName)) {
                echo("<script>location.href = '{$page}?page=friends';</script>");
            } else {
                Message::printMessage("", "User konnte nicht gefunden werden!");
            }
        }
    } else {
        Message::printMessage("", "User konnte nicht gefunden werden!");
    }
}

$setRejectFriend = isset($_GET["rj"]);
if ($setRejectFriend) {
    $friendName = $_GET["rj"];

    if ($db->rejectFriendRequest($username, $friendName)) {
        echo("<script>location.href = '{$page}?page=friends';</script>");
    } else {
        Message::printMessage("", "User konnte nicht entfernt werden!");
    }
}