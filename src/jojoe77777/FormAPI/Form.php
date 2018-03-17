<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;

abstract class Form {

    /** @var int */
    public $id;
    /** @var array */
    private $data = [];
    /** @var string */
    public $playerName;
    /** @var callable */
    private $callable;

    /**
     * @param int $id
     * @param callable $callable
     */
    public function __construct(int $id, ?callable $callable) {
        $this->id = $id;
        $this->callable = $callable;
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

    public function isRecipient(Player $player) : bool {
        return $player->getName() === $this->playerName;
    }

    public function getCallable() : ?callable {
        return $this->callable;
    }

    public function setCallable(?callable $callable) {
        $this->callable = $callable;
    }

    public function processData(&$data) : void {
    }
}
