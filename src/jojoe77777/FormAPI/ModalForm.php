<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\form\FormValidationException;

class ModalForm extends Form {

    /** @var string */
    private string $content = "";

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        parent::__construct($callable);
        $this->data["type"] = "modal";
        $this->data["title"] = "";
        $this->data["content"] = $this->content;
        $this->data["button1"] = "";
        $this->data["button2"] = "";
    }

    public function processData(&$data) : void {
        if(!is_bool($data)) {
            throw new FormValidationException("Expected a boolean response, got " . gettype($data));
        }
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title) : self {
        $this->data["title"] = $title;
        return $this;
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
     * @return $this
     */
    public function setContent(string $content) : self {
        $this->data["content"] = $content;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setButton1(string $text) : self {
        $this->data["button1"] = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getButton1() : string {
        return $this->data["button1"];
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setButton2(string $text) : self {
        $this->data["button2"] = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getButton2() : string {
        return $this->data["button2"];
    }
}
