<?php

class GameList
{
    private array $games = [];

    public function addGame(Game $game)
    {
        $this->games[] = $game;
        $_SESSION['status'] = 'start';
    }

    /**
     * @return array
     */
    public function getGames(): array
    {
        return $this->games;
    }

    public function getCurrentGame()
    {
        return end($this->games);
    }
}