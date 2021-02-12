<?php


namespace Verse\Telegram\Run\Controller;


class ControllerResponse
{
    private array $keyboard = [];

    private string $text = '';
    /**
     * @return array
     */
    public function getKeyboard(): array
    {
        return $this->keyboard;
    }

    /**
     * @param array $keyboard
     */
    public function setKeyboard(array $keyboard): void
    {
        $this->keyboard = $keyboard;
    }

    public function addKeyboardKey(string $text, string $url) {
        $this->keyboard[$text] = $url;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

}