<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\plugin\PluginBase;

class FormAPI extends PluginBase implements Listener {

	/** @var int */
	public $formCount = 0;
	/** @var array */
	public $forms = [];

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @param callable $function
	 * @return CustomForm
	 */
	public function createCustomForm(callable $function = null) : CustomForm {
		$this->formCount++;
		$form = new CustomForm($this->formCount, $function);
		if($function !== null){
			$this->forms[$this->formCount] = $form;
		}
		return $form;
	}

	public function createSimpleForm(callable $function = null) : SimpleForm {
		$this->formCount++;
		$form = new SimpleForm($this->formCount, $function);
		if($function !== null){
			$this->forms[$this->formCount] = $form;
		}
		return $form;
	}
	
	public function createModalForm(callable $function = null) : ModalForm {
		$this->formCount++;
		$form = new ModalForm($this->formCount, $function);
		if($function !== null){
			$this->forms[$this->formCount] = $form;
		}
		return $form;
	}

	/**
	 * @param DataPacketReceiveEvent $ev
	 */
	public function onPacketReceived(DataPacketReceiveEvent $ev) : void {
		$pk = $ev->getPacket();
		if($pk instanceof ModalFormResponsePacket){
			$player = $ev->getPlayer();
			$formId = $pk->formId;
			$data = json_decode($pk->formData, true);
			if(isset($this->forms[$formId])){
				/** @var Form $form */
				$form = $this->forms[$formId];
				if(!$form->isRecipient($player)){
					return;
				}
				$callable = $form->getCallable();
				if(!is_array($data)){
					$data = [$data];
				}
				if($callable !== null) {
					$callable($ev->getPlayer(), $data);
				}
				unset($this->forms[$formId]);
				$ev->setCancelled();
			}
		}
	}

	/**
	 * @param PlayerQuitEvent $ev
	 */
	public function onPlayerQuit(PlayerQuitEvent $ev){
		$player = $ev->getPlayer();
		/**
		 * @var int $id
		 * @var Form $form
		 */
		foreach($this->forms as $id => $form){
			if($form->isRecipient($player)){
				unset($this->forms[$id]);
				break;
			}
		}
	}

}
