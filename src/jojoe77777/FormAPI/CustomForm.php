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
	 * @param bool|null $default
	 */
	public function addToggle(string $text, bool $default = null){
		$content = ["type" => "toggle", "text" => $text];
		if($default !== null){
			$content["default"] = $default;
		}
		$this->addContent($content);
	}

	/**
	 * @param string $text
	 * @param int $min
	 * @param int $max
	 * @param int $step
	 * @param int $default
	 */
	public function addSlider(string $text, int $min, int $max, int $step = -1, int $default = -1){
		$content = ["type" => "slider", "text" => $text, "min" => $min, "max" => $max];
		if($step !== -1){
			$content["step"] = $step;
		}
		if($default !== -1){
			$content["default"] = $default;
		}
		$this->addContent($content);
	}

	/**
	 * @param string $text
	 * @param array $steps
	 * @param int $defaultIndex
	 */
	public function addStepSlider(string $text, array $steps, int $defaultIndex = -1){
		$content = ["type" => "step_slider", "text" => $text, "steps" => $steps];
		if($defaultIndex !== -1){
			$content["default"] = $defaultIndex;
		}
		$this->addContent($content);
	}

	/**
	 * @param string $text
	 * @param array $options
	 * @param int $default
	 */
	public function addDropdown(string $text, array $options, int $default = null){
		$this->addContent(["type" => "dropdown", "text" => $text, "options" => $options, "default" => $default]);
	}

	/**
	 * @param string $text
	 * @param string $placeholder
	 * @param string $default
	 */
	public function addInput(string $text, string $placeholder = "", string $default = null){
		$this->addContent(["type" => "input", "text" => $text, "placeholder" => $placeholder, "default" => $default]);
	}

	/**
	 * @param array $content
	 */
	private function addContent(array $content){
		$this->data["content"][] = $content;
	}

}