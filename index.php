<?php

require_once(__DIR__ . '/RouletteManager.php');

$rouletteManager = new RouletteManager(5, 10);
$rouletteManager->generateFileWithCombinations();