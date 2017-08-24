<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase implements Listener {

	/** @var int */
	public $formCount = 0;
	/** @var array */
	public $forms = [];

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @param callable $function
	 * @return CustomForm
	 */
	public function createCustomForm(callable $function = null) : CustomForm {
		$this->formCount++;
		if($function !== null) {
			$this->forms[$this->formCount] = $function;
		}
		return new CustomForm($this->formCount);
	}

	public function createSimpleForm(callable $function = null) : SimpleForm {
		$this->formCount++;
		if($function !== null) {
			$this->forms[$this->formCount] = $function;
		}
		return new SimpleForm($this->formCount);
	}

	/**
	 * @param DataPacketReceiveEvent $ev
	 */
	public function onPacketReceived(DataPacketReceiveEvent $ev){
		$pk = $ev->getPacket();
		if($pk instanceof ModalFormResponsePacket && isset($pk->formId) && isset($pk->data)){
			$formId = $pk->formId;
			$data = $pk->data;
			if(isset($this->forms[$formId])){
				$this->forms[$formId]($ev->getPlayer(), $data);
				unset($this->forms[$formId]);
			}
		}
	}

}
