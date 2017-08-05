<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;

class CustomForm {

	/** @var int */
	public $id;
	/** @var array */
	private $data = [];

	/**
	 * @param int $id
	 */
	public function __construct(int $id) {
		$this->id = $id;
		$this->data["type"] = "custom_form";
		$this->data["title"] = "";
		$this->data["content"] = [];
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
	public function sendToPlayer(Player $player){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $this->id;
		$pk->formData = json_encode($this->data);
		$player->dataPacket($pk);
	}

	/**
	 * @param string $title
	 */
	public function setTitle(string $title){
		$this->data["title"] = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle() : string {
		return $this->data["title"];
	}

	/**
	 * @param string $text
	 */
	public function addLabel(string $text){
		$this->addContent(["type" => "label", "text" => $text]);
	}

	/**
	 * @param string $text
	 */
	public function addToggle(string $text){
		$this->addContent(["type" => "toggle", "text" => $text]);
	}

	/**
	 * @param string $text
	 * @param int $min
	 * @param int $max
	 */
	public function addSlider(string $text, int $min, int $max){
		$this->addContent(["type" => "slider", "text" => $text, "min" => $min, "max" => $max]);
	}

	/**
	 * @param string $text
	 * @param array $steps
	 */
	public function addStepSlider(string $text, array $steps){
		$this->addContent(["type" => "step_slider", "text" => $text, "steps" => $steps]);
	}

	/**
	 * @param string $text
	 * @param array $options
	 */
	public function addDropdown(string $text, array $options){
		$this->addContent(["type" => "dropdown", "text" => $text, "options" => $options]);
	}

	/**
	 * @param string $text
	 */
	public function addInput(string $text){
		$this->addContent(["type" => "input", "text" => $text]);
	}

	/**
	 * @param array $content
	 */
	private function addContent(array $content){
		$this->data["content"][] = $content;
	}

}