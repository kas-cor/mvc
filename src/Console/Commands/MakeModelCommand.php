<?php

declare(strict_types=1);

namespace src\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModelCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:model')
            ->setDescription('Create a new model')
            ->addArgument('name', InputArgument::REQUIRED, 'Model name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        
        $modelDir = __DIR__ . '/../../src/Model';
        $filePath = $modelDir . '/' . $name . '.php';
        
        if (file_exists($filePath)) {
            $output->writeln("<error>Model {$name} already exists!</error>");
            return Command::FAILURE;
        }

        $content = <<<PHP
<?php

declare(strict_types=1);

namespace src\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\\Entity]
#[ORM\\Table(name: '" . strtolower($name) . "')]
class {$name}
{
    #[ORM\\Id]
    #[ORM\\GeneratedValue]
    #[ORM\\Column(type: 'integer')]
    private int \$id;

    public function getId(): int
    {
        return \$this->id;
    }
}

PHP;

        if (!is_dir($modelDir)) {
            mkdir($modelDir, 0755, true);
        }

        file_put_contents($filePath, $content);
        
        $output->writeln("<info>Model created:</info> {$filePath}");
        
        return Command::SUCCESS;
    }
}
