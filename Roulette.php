<?php

class Roulette {
    public $onSaveData;

    private $chipCount;
    private $fieldsCount;
    private $stop;

    public function __construct($chipCount, $fieldsCount)
    {
        $this->chipCount = $chipCount;
        $this->fieldsCount = $fieldsCount;
        $this->stop = false;
    }

    /**
     * Запускает рулетку
     */
    public function start()
    {
        $combinationsCount = $this->getCombinationsCount();
        $combinationsCountText = $this->getCombinationsCountText($combinationsCount);
        $this->onSaveData->__invoke($combinationsCountText);

        if ($this->stop === false) {
            $startCombination = $this->generateStartCombination();
            $this->onSaveData->__invoke(decbin($startCombination));

            $combination = $startCombination;
            while ($combination = $this->generateNextCombination($combination)) {
                $this->onSaveData->__invoke(str_pad(decbin($combination), $this->fieldsCount, 0, STR_PAD_LEFT));
            }
        }
    }

    /**
     * Возвращает строку с информацией о количестве комбинаций
     * @param $combinationsCount
     * @return string
     */
    private function getCombinationsCountText($combinationsCount)
    {
        if ($combinationsCount < 10) {
            $this->stop = true;
            return 'Менее 10 комбинаций.';
        } else {
            return 'Количество комбинаций: ' . $combinationsCount;
        }
    }

    /**
     * Считает количество возможных комбинаций
     * @return int
     */
    private function getCombinationsCount()
    {
        $combinationsCount = 1;

        for ($i = $this->fieldsCount, $limit = $this->fieldsCount - ($this->chipCount - 1); $i >= $limit; $i--) {
            $combinationsCount *= $i;
        }

        for ($i = $this->chipCount; $i >= 1; $i--) {
            $combinationsCount /= $i;
        }

        return $combinationsCount;
    }

    /**
     * Генерирует следующую комбинацию
     * @param $combination
     * @return int
     */
    private function generateNextCombination($combination)
    {
        if (($combination & ($combination + 1)) == 0) {
            return 0;
        }

        if ($combination & 1) {
            return $this->addUnitBit($this->generateNextCombination($combination >> 1) << 1);
        }

        return $this->shiftLastUnitBit($combination);
    }

    /**
     * Генерирует стартовую комбинацию фишек
     * @return int
     */
    private function generateStartCombination()
    {
        return (((1 << $this->chipCount) - 1) << ($this->fieldsCount - $this->chipCount));
    }

    /**
     * Сдвигает вправо на один разряд самый правый единичный бит
     * @param $combination
     * @return int
     */
    private function shiftLastUnitBit($combination)
    {
        return (($combination - 1) ^ (($combination ^ ($combination - 1)) >> 2));
    }

    /**
     * Вставляет единичный бит после самого правого единичного бита
     * @param $combination
     * @return int
     */
    private function addUnitBit($combination) {
        return ($combination | ((($combination ^ ($combination - 1)) + 1) >> 2));
    }
}
