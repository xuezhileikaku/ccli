<?php

namespace Ccli;

class CommandRegistry
{

//    protected $registry = [];
//    protected $controllers = [];
    protected $commandsPath;
    protected $namespaces = [];
    protected $defaultRegistry = [];

    public function __construct($commandPath)
    {

        $this->commandsPath = $commandPath;
        $this->autoloadNamespaces();
    }

    public function autoloadNamespaces()
    {

        foreach (glob($this->geCommandsPath() . '/*', GLOB_ONLYDIR) as $namespacePath) {
            $this->registerNamespce(basename($namespacePath));
        }
    }

    public function registerNamespce($commandNamespace)
    {
        $namespace = new CommandNamespace($commandNamespace);
        $namespace->loadControllers($this->geCommandsPath());
        $this->namespaces[strtolower($commandNamespace)] = $namespace;
    }

    public function getNamespace($command)
    {
        return isset($this->namespaces[$command]) ? $this->namespaces[$command] : null;

    }

    public function geCommandsPath()
    {
        return $this->commandsPath;
    }

    public function registerCommand($name, $callable)
    {
        $this->defaultRegistry[$name] = $callable;
    }

    public function getCommand($command)
    {
        return isset($this->defaultRegistry[$command]) ? $this->defaultRegistry[$command] : null;
    }

    public function getCallableController($command, $subcommand = null)
    {
        $namespace = $this->getNamespace($command);
        if ($namespace !== null) {
            return $namespace->getController($subcommand);
        }
        return null;
    }


//    public function registerController($commandName, CommandController $controller)
//    {
//        $this->controllers = [$commandName => $controller];
//    }
//
//
//    public function getController($command)
//    {
//        return isset($this->controllers[$command]) ? $this->controllers[$command] : null;
//    }

    public function getCallable($command)
    {
        $singleCommand = $this->getCommand($command);
//        if ($controller instanceof CommandController) {
//            return [$controller, 'run'];
//        }
//        $command = $this->getCommand($commandName);
        if ($singleCommand === null) {
            throw new \Exception(sprintf("Command \"%s\" not found.", $command));
        }
        return $singleCommand;
    }


}