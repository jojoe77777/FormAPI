<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;

class CustomForm extends Form {

	/** @var int */
	public $id;
	/** @var array */
	private $data = [];
	/** @var string */
	public $playerName;

	/**
	 * @param int $id
	 * @param callable $callable
	 */
	public function __construct(int $id, ?callable $callable) {
		parent::__construct($id, $callable);
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
	 * @param string $text
	 */
	public function addLabel(string $text) : void {
		$this->addContent(["type" => "label", "text" => $text]);
	}

	/**
	 * @param string $text
	 * @param bool|null $default
	 */
	public function addToggle(string $text, bool $default = null) : void {
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
	public function addSlider(string $text, int $min, int $max, int $step = -1, int $default = -1) : void {
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
	public function addStepSlider(string $text, array $steps, int $defaultIndex = -1) : void {
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
	public function addDropdown(string $text, array $options, int $default = null) : void {
		$this->addContent(["type" => "dropdown", "text" => $text, "options" => $options, "default" => $default]);
	}

	/**
	 * @param string $text
	 * @param string $placeholder
	 * @param string $default
	 */
	public function addInput(string $text, string $placeholder = "", string $default = null) : void {
		$this->addContent(["type" => "input", "text" => $text, "placeholder" => $placeholder, "default" => $default]);
	}

	/**
	 * @param array $content
	 */
	private function addContent(array $content) : void {
		$this->data["content"][] = $content;
	}

}