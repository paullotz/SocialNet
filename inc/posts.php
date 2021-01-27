<script src="http://code.jquery.com/jquery-1.5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>

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

<br>
<?php
include_once "utility/classes.php";
$db = include "config/dbaccess.php";

$setUsername = isset($_SESSION["username"]);
if ($setUsername) { ?>
    <div class="card card border-info mb-3" style="margin-bottom: 2em;">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="posts-tab" data-toggle="tab"
                       href="#posts" role="tab" aria-controls="posts"
                       aria-selected="true">Text</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="images-tab" data-toggle="tab"
                       role="tab" aria-controls="images" aria-selected="false"
                       href="#images">Bilder</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="search-tab" data-toggle="tab"
                       role="tab" aria-controls="search" aria-selected="false"
                       href="#search">Beiträge durchsuchen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="sortposts-tab" data-toggle="tab"
                       role="tab" aria-controls="sort" aria-selected="false"
                       href="#sortposts">Beiträge sortieren</a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="posts"
                     role="tabpanel" aria-labelledby="posts-tab">
                    <div class="form-group">
                        <div class="card-body">
                            <form method="post" action="index.php?page=posts">
                                <div class="form-group">
                                <textarea class="form-control" name="message"
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

                                <div class="input-group mb-3">
                                    <input type="text" class="form-control"
                                           name="txtHashtag"
                                           placeholder="picoftheday"
                                           aria-describedby="basic-addon1"
                                            maxlength="32">
                                </div>

                                <div class="form-check">
                                    <input type="radio" name="shareWith"
                                           class="form-check-input" checked
                                           value="public">
                                    <label class="form-radio-label"
                                           for="shareWithPublic">Öffentlich</label>
                                    <br>
                                    <input type="radio" name="shareWith"
                                           class="form-check-input"
                                           value="friends">
                                    <label class="form-radio-label"
                                           for="shareWithPublic">Freunde</label>
                                </div>

                                <br>
                                <div class="btn-toolbar">
                                    <div class="btn-group">
                                        <button name="sharePost" type="submit"
                                                class="btn btn-primary"> Teilen
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="images" role="tabpanel"
                     aria-labelledby="images-tab">
                    <div class="form-group">
                        <form class="form" enctype="multipart/form-data"
                              method="post"
                              action="index.php?page=posts&uploadpp">
                            <input type="file" class="btn" name="controlFile"
                                   accept=".png, .jpg, .jpeg">
                            <input type="submit" value="Beitrag teilen"
                                   name="uploadFile"/>

                            <br>
                            <br>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"
                                       name="txtHashtag"
                                       placeholder="picoftheday"
                                       aria-describedby="basic-addon1"
                                       maxlength="32">
                            </div>

                            <div class="form-check">
                                <input type="radio" name="shareWith"
                                       class="form-check-input" checked
                                       value="public">
                                <label class="form-radio-label"
                                       for="shareWithPublic">Öffentlich</label>
                                <br>
                                <input type="radio" name="shareWith"
                                       class="form-check-input" value="friends">
                                <label class="form-radio-label"
                                       for="shareWithPublic">Freunde</label>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane fade" id="search" role="tabpanel"
                     aria-labelledby="search-tab">
                    <form method="post" action="index.php?page=posts">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Hier können Sie nach Tags filtern.." name="txtSearchPost" aria-describedby="button-addon2" maxlength="32" required>
                            <button class="btn btn-outline-secondary" type="submit" name="btnSearchPostTags" id="button-addon2">Suchen</button>
                        </div>
                    </form>

                    <form method="post" action="index.php?page=posts">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Hier können Sie nach Inhalten von Posts filtern.." name="txtSearchPost" aria-describedby="button-addon2" maxlength="32" required>
                            <button class="btn btn-outline-secondary" type="submit" name="btnSearchPostsContent" id="button-addon2">Suchen</button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="sortposts" role="tabpanel"
                     aria-labelledby="sortposts-tab">
                    <h3>Sortieren nach:</h3>
                    <form method="post" action="index.php?page=posts">
                        <button type="submit" name="setSortPostsByNoPicture" class="btn btn-primary">Posts ohne Bildern</button>
                        <button type="submit" name="sortByOldestCreationDate" class="btn btn-primary">Ältestes Erstellungsdatum</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- START VON DEN EIGENTLICHEN POSTS -->
    <?php
}
$postsArray = [];
$postsArray = $db->getPosts();

$setSortPostsByNoPicture = isset($_POST["setSortPostsByNoPicture"]);
if ($setSortPostsByNoPicture) {
    $postsArray = $db->sortPostsByNoPicture();
}

$setSortByOldestCerationDate = isset($_POST["sortByOldestCreationDate"]);
if ($setSortByOldestCerationDate) {
    $postsArray = $db->sortPostsByOldestDate();
}

$setSearchPostTags = isset($_POST["btnSearchPostTags"]);
if ($setSearchPostTags) {
    $searchTag = $_POST["txtSearchPost"];
    $postsArray = $db->searchPosts($searchTag);
}

$setSearchPostContent = isset($_POST["btnSearchPostsContent"]);
if ($setSearchPostContent) {
    $searchContent = $_POST["txtSearchPost"];
    $postsArray = $db->searchPostsContent($searchContent);
}

foreach ($postsArray as $post) {
    $username = $db->getUsernameById($post->userId);

    if ($post->freigabe == "friends") {
        if (isset($_SESSION["username"])) {
            $sessionUsername = $_SESSION["username"];

            if ($username == $sessionUsername) {
                include "posts_user.php";
            } else {
                if ($db->checkIfUsersAreFriends($sessionUsername, $username)) {
                    include "posts_user.php";
                }
            }
        }
    } else {
        include "posts_user.php";
    }
}
/*
*   Post implementation
*/
$page = $_SERVER['PHP_SELF'];

$setUploadPicture = isset($_GET["uploadpp"]);
if ($setUploadPicture) {
    $username = $_SESSION["username"];
    $postHashtag = $_POST["txtHashtag"];

    $path = 'user_dirs/' . $username . '/';
    $vz = new Verzeichnis($path);

    $uploadFile = $vz->uploadPostPicture($_FILES);
    $userId = $db->getUserIdByName($username);

    $filePath = $path . $uploadFile;

    $shareWith = $_POST["shareWith"];
    if ($shareWith == "public") {
        if ($uploadFile != 0) {
            $db->addPost($userId, "", $filePath, $postHashtag, "public");
            echo("<script>location.href = '{$page}';</script>");
        } else {
            Message::printMessage("", "Bild konnte nicht hochgeladen werden");
        }
    } else {
        if ($uploadFile != 0) {
            $db->addPost($userId, "", $filePath, $postHashtag, "friends");
            echo("<script>location.href = '{$page}';</script>");
        } else {
            Message::printMessage("", "Bild konnte nicht hochgeladen werden");
        }
    }
}

$setSharePost = isset($_POST["sharePost"]);
if ($setSharePost) {
    $username = $_SESSION["username"];
    $shareWith = $_POST["shareWith"];
    $postHashtag = $_POST["txtHashtag"];

    $userId = $db->getUserIdByName($username);

    $postContent = $_POST["message"];
    $postPicture = "";

    if ($shareWith == "public") {
        $db->addPost($userId, $postContent, $postPicture, $postHashtag, "public");
    } else {
        $db->addPost($userId, $postContent, $postPicture, $postHashtag, "friends");
    }

    echo("<script>location.href = '{$page}';</script>");
}

$setDeletePost = isset($_GET["delp"]);
if ($setDeletePost) {
    $postId = $_GET["delp"];

    if ($db->checkPostHasPicture($postId)) {
        $username = $_SESSION["username"];
        $path = 'user_dirs/' . $username . '/';
        $vz = new Verzeichnis($path);

        $deletePath = $db->getPostPicturePath($postId);
        $splittedDeletePath = explode("/", $deletePath["picture"]);

        // Picture name: $splittedDeletePath[2];

        if ($vz->deleteFF($deletePath["picture"])) {
            $db->removePost($postId);
            echo("<script>location.href = '{$page}';</script>");
        } else {
            Message::printMessage("", "Post konnte nicht gelöscht werden.");
        }
    } else {
        // Just remove the post normally because it has no picture
        $db->removePost($postId);
        echo("<script>location.href = '{$page}';</script>");
    }
}

$setEditPost = isset($_POST["editPost"]);
if ($setEditPost) {
    $newMessage = $_POST["editMessage"];
    $postId = $_POST["postId"];
    if ($db->editPost($postId, $newMessage)) {
        echo("<script>location.href = '{$page}';</script>");
    } else {
        Message::printMessage("", "Post konnte nicht editiert werden.");
    }
}

$setLikePost = isset($_POST["likePost"]);
if ($setLikePost) {
    $postId = $_POST["postId"];
    $userId = $_POST["userId"];

    if ($db->alreadyLiked($postId, $userId)) {
        if ($db->removeLike($postId, $userId)) {
            echo("<script>location.href = '{$page}';</script>");
        } else {
            Message::printMessage("", "Post konnte nicht entliket werden.");
        }
    } else {
        if ($db->addLike($postId, $userId)) {
            echo("<script>location.href = '{$page}';</script>");
        } else {
            Message::printMessage("", "Post konnte nicht geliket werden.");
        }
    }
}

$dislikePost = isset($_POST["dislikePost"]);
if ($dislikePost) {
    $postId = $_POST["postId"];
    $userId = $_POST["userId"];

    if ($db->alreadyLiked($postId, $userId)) {
        if ($db->removeDislike($postId, $userId)) {
            echo("<script>location.href = '{$page}';</script>");
        } else {
            Message::printMessage("",
                "Post konnte nicht entdisliket werden.");
        }
    } else {
        if ($db->addDislike($postId, $userId)) {
            echo("<script>location.href = '{$page}';</script>");
        } else {
            Message::printMessage("",
                "Post konnte nicht gedisliket werden.");
        }
    }
}

$setCommentPost = isset($_POST["shareComment"]);
if ($setCommentPost) {
    $commentMessage = $_POST["message"];
    $postId = $_POST["postId"];
    $userId = $_POST["userId"];

    if (!$db->addComment($postId, $userId, $commentMessage)) {
        Message::printMessage("", "Beitrag konnte nicht kommentiert werden!");
    } else {
        echo("<script>location.href = '{$page}';</script>");
    }
}

?>

