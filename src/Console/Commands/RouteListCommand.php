<?php

declare(strict_types=1);

namespace src\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class RouteListCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('route:list')
            ->setDescription('List all registered routes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $routesFile = __DIR__ . '/../../config/components/routes.php';
        
        if (!file_exists($routesFile)) {
            $output->writeln('<error>Routes configuration file not found</error>');
            return Command::FAILURE;
        }

        $routes = require $routesFile;
        
        $table = new Table($output);
        $table
            ->setHeaders(['Method', 'Path', 'Controller', 'Action'])
            ->setStyle('borderless');

        foreach ($routes as $path => $config) {
            $methods = is_array($config['method']) ? implode(', ', $config['method']) : $config['method'];
            $controller = $config['controller'] ?? 'N/A';
            $action = $config['action'] ?? 'N/A';
            
            $table->addRow([
                $methods,
                $path,
                $controller,
                $action
            ]);
        }

        $table->render();
        
        return Command::SUCCESS;
    }
}
