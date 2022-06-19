<?php

require __DIR__ . "/vendor/autoload.php";

use Ecotone\Lite\EcotoneLiteApplication;

$messagingSystem = EcotoneLiteApplication::boostrap();
$commandBus = $messagingSystem->getCommandBus();
$queryBus = $messagingSystem->getQueryBus();