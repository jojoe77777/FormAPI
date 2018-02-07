<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;

class ModalForm extends Form {

    /** @var int */
    public $id;
    /** @var array */
    private $data = [];
    /** @var string */
    private $content = "";
    /** @var string */
    public $playerName;

    /**
     * @param int $id
     * @param callable $callable
     */
    public function __construct(int $id, ?callable $callable) {
        parent::__construct($id, $callable);
        $this->data["type"] = "modal";
        $this->data["title"] = "";
        $this->data["content"] = $this->content;
        $this->data["button1"] = "";
        $this->data["button2"] = "";
    }

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @param Player $player
     */
    public function sendToPlayer(Player $player) : void {
        $pk = new ModalFormRequestPacket();
        $pk->formId = $this->id;
        $pk->formData = json_encode($this->data);
        $player->dataPacket($pk);
        $this->playerName = $player->getName();
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) : void {
        $this->data["title"] = $title;
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->data["title"];
    }

    /**
     * @return string
     */
    public function getContent() : string {
        return $this->data["content"];
    }

    /**
     * @param string $content
     */
    public function setContent(string $content) : void {
        $this->data["content"] = $content;
    }

    /**
     * @param string $text
     */
    public function setButton1(string $text) : void {
        $this->data["button1"] = $text;
    }

    /**
     * @return string
     */
    public function getButton1() : string {
        return $this->data["button1"];
    }

    /**
     * @param string $text
     */
    public function setButton2(string $text) : void {
        $this->data["button2"] = $text;
    }

    /**
     * @return string
     */
    public function getButton2() : string {
        return $this->data["button2"];
    }
}
