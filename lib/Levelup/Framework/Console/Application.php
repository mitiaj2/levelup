<?php

namespace Levelup\Framework\Console;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Application extends BaseApplication
{
    private $container;
    private $commandsRegistered = false;
    /**
     * Constructor.
     *
     * @param KernelInterface $kernel A KernelInterface instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct('Logger console', 'v1');
        $this->getDefinition()->addOption(new InputOption('--shell', '-s', InputOption::VALUE_NONE, 'Launch the shell.'));
        $this->getDefinition()->addOption(new InputOption('--process-isolation', null, InputOption::VALUE_NONE, 'Launch commands from shell as a separate process.'));
        $this->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'prod'));
        $this->getDefinition()->addOption(new InputOption('--no-debug', null, InputOption::VALUE_NONE, 'Switches off debug mode.'));
    }

    public function getContainer()
    {
        return $this->container;
    }
    /**
     * Runs the current application.
     *
     * @param InputInterface  $input  An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommandss();
        foreach ($this->all() as $command) {
            if ($command instanceof ContainerAwareInterface) {
                $command->setContainer($this->container);
            }
        }


        $this->setDispatcher($this->container->get('event_dispatcher'));
        if (true === $input->hasParameterOption(array('--shell', '-s'))) {
            $shell = new Shell($this);
            $shell->setProcessIsolation($input->hasParameterOption(array('--process-isolation')));
            $shell->run();
            return 0;
        }
        return parent::doRun($input, $output);
    }

    protected function registerCommandss()
    {

        $container = $this->getContainer();
        $dir = $container->getParameter('commands_path');
        $commandNamespace = $container->getParameter('command_namespace');

        foreach (scandir($dir) as $file)
        {
            if ('.' != $file && '..' != $file) {
                $namespace = $commandNamespace . '\\' . str_replace('.php', '', $file);
                $this->add(new $namespace);
            }
        }
    }
}