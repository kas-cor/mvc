<?php

declare(strict_types=1);

namespace src\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:controller')
            ->setDescription('Create a new controller')
            ->addArgument('name', InputArgument::REQUIRED, 'Controller name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        
        // Remove "Controller" suffix if present
        $name = rtrim($name, 'Controller');
        $className = $name . 'Controller';
        
        $controllerDir = __DIR__ . '/../../app/controllers';
        $filePath = $controllerDir . '/' . $className . '.php';
        
        if (file_exists($filePath)) {
            $output->writeln("<error>Controller {$className} already exists!</error>");
            return Command::FAILURE;
        }

        $content = <<<PHP
<?php

declare(strict_types=1);

namespace app\controllers;

use app\core\Controller;

class {$className} extends Controller
{
    public function index()
    {
        return \$this->view('{$name}/index');
    }
}

PHP;

        if (!is_dir($controllerDir)) {
            mkdir($controllerDir, 0755, true);
        }

        file_put_contents($filePath, $content);
        
        $output->writeln("<info>Controller created:</info> {$filePath}");
        
        return Command::SUCCESS;
    }
}
