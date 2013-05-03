<?php

require_once('bootstrap.php');

$gen = new b8\Database\CodeGenerator(b8\Database::getConnection(), 'PHPCI', './PHPCI/');
$gen->generateModels();
$gen->generateStores();