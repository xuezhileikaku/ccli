<?php

namespace App\Command\Hello;

use Ccli\CommandController;

class NameController extends CommandController
{
    public function handle()
    {
        $name = $this->hasParam('user') ? $this->getParam('user') : 'world';
        $this->getPrinter()->display(sprintf("Hello %s!", $name));
    }
}