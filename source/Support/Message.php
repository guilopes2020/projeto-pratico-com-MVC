<?php

namespace Source\Support;

use Source\Core\Session;

/**
 * Class Message
 * @package Source\Support
 */
class Message
{
    /** @var string */
    private $text;

    /** @var string */
    private $type;

    /** @var string */
    private $before;

    /** @var string */
    private $after;

    /**
     * Magic Method To String
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Get Text Method
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->before . $this->text . $this->after;
    }

    /**
     * Get Type Method
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Before Method
     *
     * @param string $text
     * @return Message
     */
    public function before(string $text): Message
    {
        $this->before = $text;
        return $this;
    }

    /**
     * After Method
     *
     * @param string $text
     * @return Message
     */
    public function after(string $text): Message
    {
        $this->after = $text;
        return $this;
    }

    /**
     * Info Method
     *
     * @param string $message
     * @return Message
     */
    public function info(string $message): Message
    {
        $this->type = "info icon-info";
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * Success Method
     *
     * @param string $message
     * @return Message
     */
    public function success(string $message): Message
    {
        $this->type = "success icon-check-square-o";
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * Warning Method
     *
     * @param string $message
     * @return Message
     */
    public function warning(string $message): Message
    {
        $this->type = "warning icon-warning";
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * Error Method
     *
     * @param string $message
     * @return Message
     */
    public function error(string $message): Message
    {
        $this->type = "error icon-warning";
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * Render Method
     *
     * @return string
     */
    public function render(): string
    {
        return "<div class='message {$this->getType()}'>{$this->getText()}</div>";
    }

    /**
     * Json Method
     *
     * @return string
     */
    public function json(): string
    {
        return json_encode(["error" => $this->getText()]);
    }

    /**
     * Flash Method
     *
     * @return void
     */
    public function flash(): void
    {
        (new Session())->set("flash", $this);
    }

    /**
     * Filter Method
     *
     * @param string $message
     * @return string
     */
    private function filter(string $message): string
    {
        return filter_var($message, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}