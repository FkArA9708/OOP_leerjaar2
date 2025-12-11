<?php

class CubeList
{
    private array $cubes = [];

    public function addCube(Cube $cube)
    {
        $this->cubes[] = $cube;
    }

    /**
     * @return array
     */
    public function getCubes(): array
    {
        return $this->cubes;
    }
}