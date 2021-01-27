<?php

$db = include "config/dbaccess.php";
$username = $_SESSION["username"];
?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="refresh" content="10">
    </head>
<?php
$setToName = isset($_GET["toname"]);
if ($setToName) {
    $toName = $_GET["toname"];

    $messages = [];
    $messages = $db->getAllMessages($toName, $username);
    ?>

    <ul class="list-unstyled chat">
        <?php
        foreach ($messages as $message) {
            ?>
            <li class="d-flex justify-content-between mb-4">
                <div class="chat-body white p-3 z-depth-1">
                    <div class="header">
                        <?php
                        echo '<strong class="primary-font">' . $message->sendername . '</strong>';
                        echo "<br>";
                        echo '<small class="pull-right text-muted">' . $message->timestamp . '</small>'
                        ?>
                    </div>
                    <hr class="w-100">
                    <?php
                    echo '<p class="mb-0">' . $message->message . '</p>' ?>
                </div>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php
} ?>