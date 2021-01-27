<nav class="navbar navbar-expand-lg navbar-light"
     style="background-color: #e3f2fd;">
    <a class="navbar-brand" href="index.php?page=posts">SocialNet</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <?php

            // Home von jedem zu jederzeit sichtbar
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="?page=posts">Posts</a>';
            echo '</li>';

            $db = include "config/dbaccess.php";
            $usernameSet = isset($_SESSION['username']);

            if ($usernameSet) {
                $username = $_SESSION['username'];

                if ($db->checkAdmin($username)) {
                    // Wenn der User Admin ist
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="?page=ua">User Administration</a>';
                    echo '</li>';

                }
                    // Wenn der User normal und eingeloggt ist
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="?page=editdata">Profildaten</a>';
                    echo '</li>';

                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="?page=friends">Freunde</a>';
                    echo '</li>';

                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="?page=chat">Chat</a>';
                    echo '</li>';

                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="?page=logout"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-door-closed-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 1a1 1 0 0 0-1 1v13H1.5a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2a1 1 0 0 0-1-1H4zm2 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                        </svg> Logout</a>';
                    echo '</li>';

            } else {
                // Wenn der User nicht eingeloggt ist
                echo '<li class="nav-item">';
                echo '<a class="nav-link" href="?page=login">Login <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-door-open-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">';
                echo '<path fill-rule="evenodd" d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15H1.5zM11 2v13h1V2.5a.5.5 0 0 0-.5-.5H11zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>';
                echo '</svg></a>';
                echo '</li>';

                echo '<li class="nav-item">';
                echo '<a class="nav-link" href="?page=register">Register</a>';
                echo '</li>';
            }

            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="index.php?page=impressum">Impressum</a>';
            echo '</li>';

            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="index.php?page=help">Hilfe Seite</a>';
            echo '</li>';
            ?>
        </ul>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
  