<?php

use SubStalker\SubStalker;

require_once 'vendor/autoload.php';
use SubStalker\Config;

main();

function main(): void
{
    $app = new SubStalker(Config::GROUP_ID, Config::RECEPIENT_ID, Config::ACCESS_TOKEN);
    $app->listen();
}
