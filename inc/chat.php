<link rel="stylesheet" href="res/css/chat_styles.css">

<script>
    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
    }
</script>

<?php
$db = include "config/dbaccess.php";
$username = $_SESSION["username"];

if($_POST['message'] ?? false && checkIfUsersAreFriends($username, $_GET['toname'])){

    $db->sendMessage($username, $_GET['toname'], $_POST['message']);
}
?>

<script>
    // Funktion um character für die Post form zu zählen
    function countChar(val) {
        const len = val.value.length;
        if (len >= 255) {
            val.value = val.value.substring(0, 255);
        } else {
            $('#charNum').text(len);
        }
    }
</script>

<div class="container">
    <div class="card grey lighten-3 chat-room">
        <div class="card-body">
            <div class="row px-lg-2 px-2">
                <div class="col-md-12 col-lg-6 ">
                    <h3>Postfach</h3>
                    <div class="white z-depth-1 px-3 pt-3 pb-0">
                        <ul class="list-unstyled friend-list">

                            <?php
                            $chatArray = [];
                            $friendsArray = $db->getFriends($username);
                            if (!empty($friendsArray)) {
                                foreach ($friendsArray as $friendInstance) {
                                    $friend_fname = $db->getFNameByUsernames($friendInstance->friend);

                                    $chatArray = $db->getOpenMessages($friendInstance->friend, $username);
                                    if ($chatArray != NULL) {
                                        ?>
                                        <li class="p-2">
                                            <?php
                                            echo "<a href='index.php?page=chat&toname=$friendInstance->friend'  class='d-flex justify-content-between'>" ?>
                                                <div class="text-small">
                                                    <?php
                                                    echo "<strong>" . $friend_fname . "</strong>";
                                                    echo "<p class='last-message text-muted'>" . $chatArray->message . "</p>"
                                                    ?>
                                                </div>
                                                <div class="chat-footer">
                                                    <?php
                                                    echo "<p class='text-smaller text-muted mb-0'>" . $chatArray->timestamp . "</p>"
                                                    ?>
                                                    <span class="text-muted float-right"><i
                                                                class="fas fa-mail-reply"
                                                                aria-hidden="true"></i></span>
                                                </div>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                            } else {
                                echo "<h5>Solange du keine Freunde hast kannst du die Chat Funktion nicht benutzen!</h5>";
                            }
                            ?>

                            <br>
                            <?php
                            $chatArray = [];
                            $friendsArray = $db->getFriends($username);
                            if (!empty($friendsArray)) {
                                echo "<h3>Meine Freunde</h3>";

                                foreach ($friendsArray as $friendInstance) {
                                    $friend_fname = $db->getFNameByUsernames($friendInstance->friend);

                                    ?>
                                    <li class="p-2">
                                        <?php
                                        echo "<a href='index.php?page=chat&toname=$friendInstance->friend'  class='d-flex justify-content-between'>" ?>
                                        <div class="text-small">
                                            <?php
                                            echo "<strong>" . $friend_fname . "</strong>";
                                            echo "<p class='last-message text-muted'>Zum chatten clicken!</p>"
                                            ?>
                                        </div>
                                        <div class="chat-footer">
                                                <span class="text-muted float-right"><i
                                                            class="fas fa-mail-reply"
                                                            aria-hidden="true"></i></span>
                                        </div>
                                        </a>
                                    </li>

                                    <?php
                                }
                            } else {
                                echo "<h5>Solange du keine Freunde hast kannst du die Chat Funktion nicht benutzen!</h5>";
                            }
                            ?>
                        </ul>
                    </div>

                </div>

                <?php
                $setToName = isset($_GET["toname"]);
                if ($setToName) {
                    $toName = $_GET["toname"];

                    $messages = [];
                    $messages = $db->getAllMessages($toName, $username);
                ?>
                <div class="col-md-12 col-lg-6">
                    <div class="chat-message">

                        <iframe src="index.php?page=messages&toname=<?= $toName?>" frameborder="0" width="100%" scrolling="no" onload="resizeIframe(this)"></iframe>
                            <form method="post" action="index.php?page=chat&toname=<?= $_GET['toname']?>">
                                <div class="form-group basic-textarea">
                                    <textarea class="form-control"
                                              name="message"
                                              rows="4"
                                              placeholder="..." maxlength="255"
                                              onkeyup="countChar(this)">
                                </textarea>

                                    <div>
                                        Aktuelle Zeichen:
                                        <span id="charNum">0</span>
                                        <span id="maximum">/ 255</span>
                                    </div>
                                </div>

                                <?php
                                $username = $_SESSION["username"];

                                echo '<input type="hidden" name="fromUser" value="' . $username . '">';
                                echo '<input type="hidden" name="toUser" value="' . $_GET["toname"] . '">';
                                ?>
                                <button type="submit" name="sendMessage"
                                        class="btn btn-success float-right">
                                    Nachricht senden
                                </button>
                            </form>

                    </div>
                </div>

                <?php
                } else {
                    echo "<h3>Wähle einen Freund aus, um chatten zu können</h3>";
                } ?>
            </div>
        </div>
    </div>
</div>

