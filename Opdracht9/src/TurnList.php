<?php
class TurnList
{
    private array $turns = [];

    public function addTurn(Turn $turn)
    {
        $this->turns[] = $turn;
    }

    /**
     * @return array
     */
    public function getTurns(): array
    {
        return $this->turns;
    }

    public function getAmountTurns()
    {
        return count($this->turns);
    }

    public function getCurrentTurn()
    {
        return end($this->turns);
    }
}