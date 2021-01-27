<?php

// Including models of different instances we need in this class
include "model/Comments.class.php";
include "model/User.class.php";
include "model/Post.class.php";
include "model/Friends.class.php";
include "model/FriendRequest.class.php";
include "model/Chat.class.php";

class DB
{

    public $db_host;

    public $db_name;

    public $db_user;

    public $db_password;

    public $conn;

    public function __construct($db_host, $db_name, $db_user, $db_password)
    {
        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_password = $db_password;

        $conn = new mysqli($this->db_host,
            $this->db_user,
            $this->db_password,
            $this->db_name);
        if ($conn->connect_error) {
            echo 'Failed to connect: ' . $conn->connect_error;

            return false;
        }

        $this->conn = $conn;
    }

    /*
     * General functions to communicate with database & dealing with SQL
    */
    public function closeConnection()
    {
        try {
            mysqli_close($this->conn);

            return true;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function checkInput($input): ?string
    {
        if (!empty($input)) {
            if (ctype_alnum($input)) {
                return $this->escape($input);
            } else {
                return NULL;
            }
        }
        return NULL;
    }

    public function escape($input)
    {
        return mysqli_escape_string($this->conn, $input);
    }

    /*
     * Essential User functions
    */
    public function getUserList()
    {
        $userArray = [];
        $sql = 'SELECT * FROM users';
        $result = $this->conn->query($sql);
        $x = 0;

        while ($userObject = $result->fetch_object()) {
            $user = new User(
                $userObject->id,
                $userObject->anrede,
                $userObject->fname,
                $userObject->name,
                $userObject->adress,
                $userObject->plz,
                $userObject->ort,
                $userObject->username,
                $userObject->password,
                $userObject->email
            );

            $userArray[$x] = $user;
            ++$x;
        }

        return $userArray;
    }

    public function getUser($username)
    {
        $userArray = [];
        $sql = "SELECT * FROM users WHERE username = '" . $username . "'";
        $result = $this->conn->query($sql);

        $userObject = $result->fetch_object();

        return new User(
            $userObject->id,
            $userObject->anrede,
            $userObject->fname,
            $userObject->name,
            $userObject->adress,
            $userObject->plz,
            $userObject->ort,
            $userObject->username,
            $userObject->password,
            $userObject->email
        );
    }

    public function getUserIdByName($username)
    {
        $userArray = [];
        $sql = "SELECT * FROM users WHERE username = '" . $username . "'";
        $result = $this->conn->query($sql);

        $userObject = $result->fetch_object();

        return $userObject->id;
    }

    public function getFNameByUsernames($username)
    {
        $userArray = [];
        $sql = "SELECT * FROM users WHERE username = '" . $username . "'";
        $result = $this->conn->query($sql);

        $userObject = $result->fetch_object();

        return $userObject->fname;
    }

    public function getNameByUsernames($username)
    {
        $userArray = [];
        $sql = "SELECT * FROM users WHERE username = '" . $username . "'";
        $result = $this->conn->query($sql);

        $userObject = $result->fetch_object();

        return $userObject->name;
    }

    public function getUsernameById($id)
    {
        $userArray = [];
        $sql = "SELECT * FROM users WHERE id = '" . $id . "'";
        $result = $this->conn->query($sql);

        $userObject = $result->fetch_object();

        return $userObject->username;
    }

    public function registerUser($userObject): bool
    {
        $sql = "INSERT INTO users (anrede, fname, name, adress, plz, ort, username, password, email)
                VALUES ('{$userObject->anrede}', '{$userObject->fname}', '{$userObject->name}', "
            . "'{$userObject->adress}', {$userObject->plz}, '{$userObject->ort}', '{$userObject->username}', '{$userObject->password}', '{$userObject->email}')";

        $result = $this->checkUserExists($userObject);
        if ($result) {
            return false;
        }

        try {
            $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function checkUserExists($userObject): bool
    {
        if ($this->checkEmailExists($userObject->email) && $this->checkUsernameExits($userObject->username)) {
            return true;
        }

        return false;
    }

    public function checkEmailExists($email): bool
    {
        $sql = "SELECT * FROM users WHERE email='" . $email . "'";
        $result = $this->conn->query($sql);
        $resultLength = $result->num_rows;

        if ($resultLength > 0) {
            return true;
        }

        return false;
    }

    public function checkUsernameExits($username): bool
    {
        $sql = "SELECT * FROM users WHERE username='" . $username . "'";
        $result = $this->conn->query($sql);
        $resultLength = $result->num_rows;

        if ($resultLength > 0) {
            return true;
        }

        return false;
    }

    public function checkProfilePicture($username): bool
    {
        $sql = "SELECT profilepicture FROM users WHERE username='{$username}'";
        $result = $this->conn->query($sql);
        $value = mysqli_fetch_assoc($result);

        if ($value["profilepicture"] != NULL) {
            return true;
        } else {
            return false;
        }
    }

    public function setProfilePicture($username): bool
    {
        $sql = "UPDATE users SET profilepicture=1 WHERE username='{$username}'";

        try {
            $result = $this->conn->query($sql);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function removeProfilePicture($username): bool
    {
        $sql = "UPDATE users SET profilepicture=NULL WHERE username='{$username}'";

        try {
            $result = $this->conn->query($sql);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function updateUser($userObject): bool
    {
        $sql = "UPDATE users SET anrede='{$userObject->anrede}', fname='{$userObject->fname}', name='{$userObject->name}', adress='{$userObject->adress}', plz={$userObject->plz}, ort='{$userObject->ort}', username='{$userObject->username}', password='{$userObject->password}', email='{$userObject->email}'
            WHERE id = {$userObject->id}";

        try {
            $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = {$id}";

        return $this->conn->query($sql);
    }

    public function generatePassword(): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; ++$i) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }

    public function comparePassword($oldpassword, $id): bool
    {
        $sql = 'SELECT * FROM users WHERE id=' . $id;
        $result = $this->conn->query($sql);
        $userObject = $result->fetch_object();

        if ($result->lengths > 0) {
            if (password_verify($oldpassword, $userObject->password)) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function insertPasswordById($password, $id): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password='" . $hashedPassword . "' WHERE id=" . $id;

        try {
            $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function insertPasswordByEmail($password, $email): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password='" . $hashedPassword . "' WHERE email='" . $email . "'";

        try {
            $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function loginUser($username, $password): bool
    {
        $sql = "SELECT * FROM users WHERE username='" . $username . "'";
        $result = $this->conn->query($sql);
        $userObject = $result->fetch_object();

        if ($result->lengths > 0) {
            if (password_verify($password, $userObject->password)) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function checkAdmin($username): bool
    {
        $sql = "SELECT * FROM users WHERE username='" . $username . "' AND type = 'admin'";
        $result = $this->conn->query($sql);
        $userObject = $result->fetch_object();

        if ($result->lengths > 0) {
            if ($username === $userObject->username) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkStatus($username): string
    {
        $sql = "SELECT status FROM users WHERE username='" . $username . "'";
        $result = $this->conn->query($sql);

        $value = mysqli_fetch_assoc($result);

        return $value["status"];
    }

    /*
     * Core Functionality of Posts
     * addPost, removePost, editPost, get Post
     * getLikes, getDislikes, addLike, addDislike, removeLike, remove Dislike
     * und dann checken ob man schon geliket hat ansonten removen
     * getComments, addComment, RemoveComment, editComment
     */
    public function alreadyLiked($postId, $userId): bool
    {
        $sql = "select count(likeId) from likes where postId=" . $postId . " AND userId=" . $userId;
        $result = $this->conn->query($sql);
        $amount = $result->fetch_assoc();

        if ($amount["count(likeId)"] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getLikes($postId)
    {
        $sql = "SELECT count(likeId) FROM likes WHERE postId=" . $postId . " AND type='like'";
        $result = $this->conn->query($sql);

        $amount = $result->fetch_assoc();
        return $amount["count(likeId)"];
    }

    public function getDislikes($postId)
    {
        $sql = "SELECT count(likeId) FROM likes WHERE postId=" . $postId . " AND type='dislike'";
        $result = $this->conn->query($sql);

        $amount = $result->fetch_assoc();
        return $amount["count(likeId)"];
    }

    public function addLike($postId, $userId): bool
    {
        $sql = "INSERT INTO likes (postId, userId, type) 
            VALUES ($postId, $userId , 'like')";

        try {
            $result = $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function addDislike($postId, $userId): bool
    {
        $sql = "INSERT INTO likes (postId, userId, type) 
            VALUES ($postId, $userId , 'dislike')";

        try {
            $result = $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function removeLike($postId, $userId): bool
    {
        $sql = "DELETE FROM likes where postId=" . $postId . " AND userId=" . $userId . " AND type='like'";

        return $this->conn->query($sql);
    }

    public function removeDislike($postId, $userId): bool
    {
        $sql = "DELETE FROM likes where postId=" . $postId . " AND userId=" . $userId . " AND type='dislike'";

        return $this->conn->query($sql);
    }

    public function addComment($postId, $userId, $comment): bool
    {
        $sql = "INSERT INTO comments (postId, userId, commentContent)
                VALUES ($postId, $userId, '{$comment}')";

        try {
            $result = $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getComments(): array
    {
        $commentObject = [];
        $sql = "SELECT * FROM comments ORDER BY postId DESC";
        $result = $this->conn->query($sql);

        $commentsArray = [];
        $x = 0;
        while ($commentObject = $result->fetch_object()) {
            $comment = new Comments(
                $commentObject->postId,
                $commentObject->userId,
                $commentObject->commentContent,
                $commentObject->timestamp,
            );

            $commentsArray[$x] = $comment;
            ++$x;
        }

        return $commentsArray;
    }

    public function addPost(
        $userId,
        $postContent,
        $postPicture,
        $hashtag,
        $freigabe
    ): bool{
        $sql = "";
        if (empty($postPicture)) {
            if (empty($hashtag)) {
                $sql = "INSERT INTO posts (userId, content, freigabe)
                VALUES ($userId, '{$postContent}', '{$freigabe}')";
            } else {
                $sql = "INSERT INTO posts (userId, content, picture, hashtag, freigabe) 
                VALUES ($userId, '{$postContent}', '{$postPicture}',  '{$hashtag}', '{$freigabe}')";
            }
        } else {
            if (empty($hashtag)) {
                $sql = "INSERT INTO posts (userId, content, picture, freigabe)
                VALUES ($userId, '{$postContent}', '{$postPicture}', '{$freigabe}')";
            } else {
                $sql = "INSERT INTO posts (userId, content, picture, hashtag, freigabe) 
                VALUES ($userId, '{$postContent}', '{$postPicture}',  '{$hashtag}', '{$freigabe}')";
            }
        }

        try {
            $result = $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function removePost($id): bool
    {
        $sql = "DELETE FROM likes WHERE postId = {$id}";
        $this->conn->query($sql);

        $sql = "DELETE FROM comments WHERE postId = {$id}";
        $this->conn->query($sql);

        $sql = "DELETE FROM posts WHERE postId = {$id}";
        return $this->conn->query($sql);
    }

    public function editPost($id, $postContent): bool
    {
        $sql = "UPDATE posts SET content='" . $postContent . "' WHERE postId='" . $id . "'";

        try {
            $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getPosts(): array
    {
        $postObject = [];
        $sql = "SELECT * FROM posts ORDER BY postId DESC";
        $result = $this->conn->query($sql);

        $postArray = [];
        $x = 0;
        while ($postObject = $result->fetch_object()) {
            $post = new Post(
                $postObject->postId,
                $postObject->userId,
                $postObject->content,
                $postObject->picture,
                $postObject->hashtag,
                $postObject->freigabe,
                $postObject->timestamp,
            );

            $postArray[$x] = $post;
            ++$x;
        }

        return $postArray;
    }

    public function searchPosts($tag): array
    {
        $postObject = [];
        $sql = "SELECT * FROM posts WHERE hashtag='" . $tag . "' ORDER BY postId DESC ";
        $result = $this->conn->query($sql);

        $postArray = [];
        $x = 0;
        while ($postObject = $result->fetch_object()) {
            $post = new Post(
                $postObject->postId,
                $postObject->userId,
                $postObject->content,
                $postObject->picture,
                $postObject->hashtag,
                $postObject->freigabe,
                $postObject->timestamp,
            );

            $postArray[$x] = $post;
            ++$x;
        }

        return $postArray;
    }

    public function searchPostsContent($content): array
    {
        $postObject = [];
        $sql = "SELECT * FROM posts WHERE content LIKE '%" . $content . "%' ORDER BY postId DESC";
        $result = $this->conn->query($sql);


        $postArray = [];
        $x = 0;
        while ($postObject = $result->fetch_object()) {
            $post = new Post(
                $postObject->postId,
                $postObject->userId,
                $postObject->content,
                $postObject->picture,
                $postObject->hashtag,
                $postObject->freigabe,
                $postObject->timestamp,
            );

            $postArray[$x] = $post;
            ++$x;
        }

        return $postArray;
    }

    public function showUsersPosts($userId): array
    {
        $postObject = [];
        $sql = "SELECT * FROM posts WHERE userId='" . $userId . "' ORDER BY postId DESC ";
        $result = $this->conn->query($sql);

        $postArray = [];
        $x = 0;
        while ($postObject = $result->fetch_object()) {
            $post = new Post(
                $postObject->postId,
                $postObject->userId,
                $postObject->content,
                $postObject->picture,
                $postObject->hashtag,
                $postObject->freigabe,
                $postObject->timestamp,
            );

            $postArray[$x] = $post;
            ++$x;
        }

        return $postArray;
    }

    public function checkPostHasPicture($id): bool {
        $sql = "select * from posts where postId=" . $id . " AND picture IS NULL";
        $result = $this->conn->query($sql);
        $resultLength = $result->num_rows;

        if ($resultLength > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getPostPicturePath($id): array
    {
        $userArray = [];
        $sql = "SELECT picture FROM posts WHERE postId=" . $id;
        $result = $this->conn->query($sql);

        return $result->fetch_assoc();
    }

    public function sortPostsByOldestDate(): array {
        $postObject = [];
        $sql = "SELECT * FROM posts ORDER BY postId ASC";
        $result = $this->conn->query($sql);

        $postArray = [];
        $x = 0;
        while ($postObject = $result->fetch_object()) {
            $post = new Post(
                $postObject->postId,
                $postObject->userId,
                $postObject->content,
                $postObject->picture,
                $postObject->hashtag,
                $postObject->freigabe,
                $postObject->timestamp,
            );

            $postArray[$x] = $post;
            ++$x;
        }

        return $postArray;
    }

    public function sortPostsByNoPicture(): array
    {
        $postObject = [];
        $sql = "SELECT * FROM posts WHERE picture IS NULL";
        $result = $this->conn->query($sql);

        $postArray = [];
        $x = 0;
        while ($postObject = $result->fetch_object()) {
            $post = new Post(
                $postObject->postId,
                $postObject->userId,
                $postObject->content,
                $postObject->picture,
                $postObject->hashtag,
                $postObject->freigabe,
                $postObject->timestamp,
            );

            $postArray[$x] = $post;
            ++$x;
        }

        return $postArray;
    }

    /*
     * Friends implementation
     * getFriends, getFriendRequests, addFriend, acceptFriend, removeFriend
     * */

    public function getFriends($username): array
    {
        $friendsObject = [];
        $sql = "SELECT * FROM friends WHERE user='{$username}'";
        $result = $this->conn->query($sql);

        $friendsArray = [];
        $x = 0;
        while ($friendsObject = $result->fetch_object()) {
            $friend = new Friends(
                $friendsObject->user,
                $friendsObject->friend,
            );

            $friendsArray[$x] = $friend;
            ++$x;
        }
        return $friendsArray;
    }

    public function checkIfUsersAreFriends($username, $friendname): bool
    {
        $sql = "select * from friends where user='" . $username . "' AND friend='" . $friendname . "'";
        $result = $this->conn->query($sql);
        $resultLength = $result->num_rows;

        if ($resultLength > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function removeFriend($username, $friendname): bool
    {
        $sql = "";
        try {
            $sql = "DELETE FROM friends WHERE user = '{$username}' AND friend = '{$friendname}'";
            $result = $this->conn->query($sql);

            $sql = "DELETE FROM friends WHERE user = '{$friendname}' AND friend = '{$username}'";
            $result = $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getFriendRequests($username): array
    {
        $friendRequestArray = [];
        $friendRequestObject = [];
        $sql = "SELECT * FROM friendrequests WHERE to_friend='" . $username . "'";
        $result = $this->conn->query($sql);

        $friendsArray = [];
        $x = 0;

        while ($friendRequestObject = $result->fetch_object()) {
            $friendRequest = new FriendRequest(
                $friendRequestObject->from_user,
                $friendRequestObject->to_friend,
            );

            $friendRequestArray[$x] = $friendRequest;
            ++$x;
        }
        return $friendRequestArray;
    }

    public function requestFriend($username, $friendName): bool
    {
        $sql = "";
        $sql = "INSERT INTO `friendrequests`(`from_user`, `to_friend`) VALUES ('{$username}', '{$friendName}')";

        try {
            $result = $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function checkRequestSent($username, $friendname): bool
    {
        $sql = "SELECT * FROM friendrequest WHERE user='" . $username . "' AND friend='" . $friendname . "'";

        $result = $this->conn->query($sql);
        if ($result > 0) {
            return true;
        }

        return false;
    }

    public function acceptFriend($username, $friendname): bool
    {
        $this->rejectFriendRequest($friendname, $username);

        $sql = "";
        try {
            $sql = "INSERT INTO friends (user, friend) VALUES ('{$username}', '{$friendname}')";
            $result = $this->conn->query($sql);

            $sql = "INSERT INTO friends (user, friend) VALUES ('{$friendname}', '{$username}')";
            $result = $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function rejectFriendRequest($username, $friendname): bool
    {
        $sql = "DELETE FROM friendrequests WHERE from_user = '{$username}' AND to_friend = '{$friendname}'";

        return $this->conn->query($sql);
    }

    public function setActive($username): bool
    {
        $sql = "UPDATE users SET status='active' WHERE username='{$username}'";

        try {
            $result = $this->conn->query($sql);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function setInactive($username): bool
    {
        $sql = "UPDATE users SET status='inactive' WHERE username='{$username}'";

        try {
            $result = $this->conn->query($sql);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /*
     * Chat implementation
     * getOpenMessages, getReadMessage, sendMessage, setMessageRead, getMessages
     */
    public function getOpenMessages($from_user, $to_friend): ?Chat
    {
        $chatObject = [];
        $sql = "SELECT * FROM chat WHERE (sendername ='" . $from_user . "' AND receivername ='" . $to_friend . "') OR (receivername ='" . $from_user . "' AND sendername ='" . $to_friend . "') AND msg_read = 0 ORDER BY timestamp DESC";
        $result = $this->conn->query($sql);

        // NULL abfrage
        if ($result->num_rows == 0) {
            return NULL;
        }

        $chatArray = [];
        $x = 0;
        $chatObject = $result->fetch_object();
        return new Chat(
            $chatObject->id,
            $chatObject->sendername,
            $chatObject->receivername,
            $chatObject->message,
            $chatObject->msg_read,
            $chatObject->timestamp,
        );
    }

    public function getAllMessages($from_user, $to_friend): array
    {
        $chatObject = [];
        $sql = "SELECT * FROM chat WHERE (sendername ='" . $from_user . "' AND receivername ='" . $to_friend . "') OR (receivername ='" . $from_user . "' AND sendername ='" . $to_friend . "')";
        $result = $this->conn->query($sql);

        $chatArray = [];
        $x = 0;
        while ($chatObject = $result->fetch_object()) {
            $chat = new Chat(
                $chatObject->id,
                $chatObject->sendername,
                $chatObject->receivername,
                $chatObject->message,
                $chatObject->msg_read,
                $chatObject->timestamp,
            );

            $chatArray[$x] = $chat;
            ++$x;
        }

        return $chatArray;
    }

    public function sendMessage($from_user, $to_friend, $message): bool
    {
        $sql = "INSERT INTO chat (sendername, receivername, message) VALUES ('$from_user', '$to_friend', '$message')";

        try {
            $this->conn->query($sql);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}
