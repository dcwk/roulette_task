<?php

require_once (__DIR__ . '/Roulette.php');

class RouletteManager
{
    private $roulette;
    private $file;
    
    public function __construct($chipCount, $fieldsCount)
    {
        $this->roulette = new Roulette($chipCount, $fieldsCount);
        $this->roulette->onSaveData = function($data) {
            return $this->saveData($data);
        };
    }

    /**
     * Создает файл и запускает рулетку
     */
    public function generateFileWithCombinations()
    {
        echo 'Start...' . PHP_EOL;

        $this->file = fopen('combinations.txt', 'w');
        $this->roulette->start();
        fclose($this->file);

        echo 'Finish!' . PHP_EOL;
    }

    /**
     * Сохраняет данные в файл
     * @param $data
     */
    private function saveData($data)
    {
        fwrite($this->file, $data . PHP_EOL);
    }

}