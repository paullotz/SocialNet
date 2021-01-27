<?php
class FriendRequest {
    public $from_user;
    public $to_friend;

    public function __construct($from_user, $to_friend) {
        $this->from_user = $from_user;
        $this->to_friend = $to_friend;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from_user;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from_user): void
    {
        $this->frfrom_userom = $from_user;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to_friend;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to_friend): void
    {
        $this->to_friend = $to_friend;
    }
}