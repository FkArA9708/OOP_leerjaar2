<?php

class Hint
{
    private string $hint;

    public function __construct($hint)
    {
        $this->hint = $hint;
    }

    /**
     * @return array
     */
    public function getHintString(): string
    {
        return $this->hint;
    }
}