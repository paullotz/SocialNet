<?php

class Comments
{
    public $postId;
    public $userId;
    public $commentContent;
    public $timestamp;

    public function __construct($postId, $userId, $commentContent, $timestamp)
    {
        $this->postId = $postId;
        $this->userId = $userId;
        $this->commentContent = $commentContent;
        $this->timestamp = $timestamp;
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
     * Get the value of postContent.
     */
    public function getPostContent()
    {
        return $this->postContent;
    }

    /**
     * Set the value of postContent.
     *
     * @param mixed $postContent
     *
     * @return self
     */
    public function setPostContent($postContent)
    {
        $this->postContent = $postContent;

        return $this;
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
