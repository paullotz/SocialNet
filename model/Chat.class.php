<?php
class Chat {
    public $id;
    public $sendername;
    public $receivername;
    public $message;
    public $msg_read;
    public $timestamp;

    public function __construct($id, $sendername, $receivername, $message, $msg_read, $timestamp)
    {
        $this->id = $id;
        $this->sendername = $sendername;
        $this->receivername = $receivername;
        $this->message = $message;
        $this->msg_read = $msg_read;
        $this->timestamp = $timestamp;
    }

}
