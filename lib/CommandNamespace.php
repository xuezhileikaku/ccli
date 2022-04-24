<?php

namespace Ccli;

class CommandNamespace
{
    protected $name;
    protected $constrollers = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function loadControllers($commandPath)
    {
        foreach (glob($commandPath . '/' . $this->getName() . '/*Controller.php') as $conFile) {
            $this->loadCommandMap($conFile);
        }
        return $this->getControllers();
    }

    public function getControllers()
    {
        return $this->constrollers;
    }

    public function getController($commandName)
    {
        return isset($this->constrollers[$commandName]) ? $this->constrollers[$commandName] : null;
    }

    public function loadCommandMap($controllerFile)
    {
        $fileName = basename($controllerFile);
        $constrollerClass = str_replace('.php', '', $fileName);
        $commandName = strtolower(str_replace('Controller', '', $constrollerClass));
        $fullClassName = sprintf("App\\Command\\%s\\%s", $this->getName(), $constrollerClass);
        $controller = new $fullClassName();
        $this->constrollers[$commandName] = $controller;
    }
}