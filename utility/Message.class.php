<?php
class Message {
    public static function printMessage($type, $message) {
        if ($type == "success") {
            echo '<br>';
            echo '<div class="alert alert-success" role="alert">';
            echo $message;
            echo '</div>';
        } else {
            echo '<br>';
            echo '<div class="alert alert-danger" role="alert">';
            echo $message;
            echo '</div>';
        }
    }
}