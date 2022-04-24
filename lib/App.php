<?php

namespace Ccli;

class App
{
    protected $printer;
    protected $commandRegistry;
    protected $appSignature;

    public function __construct()
    {
        $this->printer = new CliPrinter();
        $this->commandRegistry = new CommandRegistry(__DIR__ . '/../app/Command');
    }

    public function getPrinter()
    {
        return $this->printer;
    }

    public function getSignature()
    {
        return $this->appSignature;
    }

    public function printSignature()
    {
        $this->getPrinter()->display(sprintf("usage: %s", $this->getSignature()));
    }

    public function setSignature($appSignature)
    {
        $this->appSignature = $appSignature;
    }

//    public function registerController($name, CommandController $controller)
//    {
//        $this->commandRegistry->registerController($name, $controller);
//    }

    public function registerCommand($name, $callable)
    {
        $this->commandRegistry->registerCommand($name, $callable);
    }


    public function runCommand(array $argv)
    {
//        $commandName = $defaultCommand;
        $input = new CommandCall($argv);
        if (count($input->args) < 2) {
            $this->printSignature();
            exit();
        }
//        if (isset($argv[1])) {
//            $commandName = $argv[1];
//        }
//        try {
//            call_user_func($this->commandRegistry->getCallable($commandName), $argv);
//        } catch (\Exception $e) {
//            $this->getPrinter()->display("ERROR: " . $e->getMessage());
//            exit();
//        }
        $controller = $this->commandRegistry->getCallableController($input->command, $input->subcommad);
        if ($controller instanceof CommandController) {
            $controller->boot($this);
            $controller->run($input);
            $controller->teardown();
            exit();
        }
        $this->runSingle($input);
    }

    public function runSingle(CommandCall $input)
    {
        try {
            $callable = $this->commandRegistry->getCallable($input->command);
            call_user_func($callable, $input);
        } catch (\Exception $e) {
            $this->getPrinter()->display("ERROR: " . $e->getMessage());
            $this->printSignature();
            exit();
        }
    }
}