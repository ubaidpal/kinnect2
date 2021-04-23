<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 13-1-16 2:22 PM
 * File Name    : Conversation.php
 */

namespace App\Repository\Entities;


use Illuminate\Support\Collection;

class Conversation
{

    protected $id;
    protected $participants;
    protected $messages;
    protected $created;
    protected $type;
    protected $unreadCount;
    protected $conv_for;
    const GROUP  = 'group';
    const COUPLE = 'couple';


    function __construct() {
        $this->participants = [];
//        /$this->unreadCount = [];
        $this->messages = new Collection();
    }

    function addParticipant($participant) {
        $this->participants[$participant] = TRUE;
    }

    function removeParticipant($participant) {
        unset($this->participants[$participant]);
    }

    function addMessage(Message $msg) {
        $this->addParticipant($msg->getSender());
        $this->messages[$msg->getId()] = $msg;
    }

    function getNumOfParticipants() {
        return count($this->participants);
    }

    function getNumOfMessages() {
        return count($this->messages);
    }

    function getAllParticipants() {
        return array_keys($this->participants);
    }

    function getTheOtherParticipant($me) {
        $participants = $this->participants;
        unset($participants[$me]);
        $participants = array_keys($participants);

        return array_pop($participants);
    }

    function getAllMessages() {
        return $this->messages;
    }

    function getFirstMessage() {
        return $this->messages->first();
    }

    /**
     * @return Message
     */
    function getLastMessage() {
        return $this->messages->last();
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created) {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getType() {
        if ($this->getNumOfParticipants() > 2)
            return self::GROUP;

        return self::COUPLE;
    }

    /**
     * @param mixed $unreadCount
     */
    public function setUnreadCount($unreadCount) {
        $this->unreadCount = $unreadCount;
    }

    /**
     * @return mixed
     */
    public function getUnreadCount() {
        return $this->unreadCount;
    }

    /**
     * @param mixed $conv_for
     */
    public function setConvFor($conv_for) {
        $this->conv_for = $conv_for;
    }

    /**
     * @return mixed
     */
    public function getConvFor() {
        return $this->conv_for;
    }
}

