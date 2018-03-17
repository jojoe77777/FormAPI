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
        $this->formCount = rand(0, 0xFFFFFFFF);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param callable $function
     * @return CustomForm
     */
    public function createCustomForm(callable $function = null) : CustomForm {
        $this->formCountBump();
        $form = new CustomForm($this->formCount, $function);
        $this->forms[$this->formCount] = $form;
        return $form;
    }

    public function createSimpleForm(callable $function = null) : SimpleForm {
        $this->formCountBump();
        $form = new SimpleForm($this->formCount, $function);
        $this->forms[$this->formCount] = $form;
        return $form;
    }

    public function createModalForm(callable $function = null) : ModalForm {
        $this->formCountBump();
        $form = new ModalForm($this->formCount, $function);
        $this->forms[$this->formCount] = $form;
        return $form;
    }

    public function formCountBump() : void {
        ++$this->formCount;
        if($this->formCount & (1 << 32)) { // integer overflow!
            $this->formCount = rand(0, 0xFFFFFFFF);
        }
    }

    /**
     * @param DataPacketReceiveEvent $ev
     */
    public function onPacketReceived(DataPacketReceiveEvent $ev) : void {
        $pk = $ev->getPacket();
        if($pk instanceof ModalFormResponsePacket) {
            $player = $ev->getPlayer();
            $formId = $pk->formId;
            $data = json_decode($pk->formData, true);
            if(isset($this->forms[$formId])) {
                /** @var Form $form */
                $form = $this->forms[$formId];
                if(!$form->isRecipient($player)) {
                    return;
                }
                $form->processData($data);
                $callable = $form->getCallable();
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
    public function onPlayerQuit(PlayerQuitEvent $ev) {
        $player = $ev->getPlayer();
        /**
         * @var int $id
         * @var Form $form
         */
        foreach ($this->forms as $id => $form) {
            if($form->isRecipient($player)) {
                unset($this->forms[$id]);
                break;
            }
        }
    }

}
