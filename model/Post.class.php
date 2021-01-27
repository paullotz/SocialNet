<?php

class Post
{
    public $postId;
    public $userId;
    public $content;
    public $picture;
    public $hashtag;
    public $freigabe;
    public $timestamp;

    public function __construct($postId, $userId, $content, $picture, $hashtag, $freigabe, $timestamp)
    {
        $this->postId = $postId;
        $this->userId = $userId;
        $this->content = $content;
        $this->picture = $picture;
        $this->hashtag = $hashtag;
        $this->freigabe = $freigabe;
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getFreigabe()
    {
        return $this->freigabe;
    }

    /**
     * @param mixed $freigabe
     */
    public function setFreigabe($freigabe): void
    {
        $this->freigabe = $freigabe;
    }

    /**
     * Get the value of postId.
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set the value of postId.
     *
     * @param mixed $postId
     *
     * @return self
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * Get the value of userId.
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId.
     *
     * @param mixed $userId
     *
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of content.
     */
    public function getcontent()
    {
        return $this->content;
    }

    /**
     * Set the value of content.
     *
     * @param mixed $content
     *
     * @return self
     */
    public function setcontent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getpicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setpicture($picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }
}
