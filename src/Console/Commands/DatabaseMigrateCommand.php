<?php

declare(strict_types=1);

namespace src\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;

class DatabaseMigrateCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('database:migrate')
            ->setDescription('Run database migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Running database migrations...</info>');
        
        // Check if phinx config exists
        $phinxConfig = __DIR__ . '/../../phinx.php';
        
        if (file_exists($phinxConfig)) {
            $output->writeln('<comment>Using Phinx for migrations</comment>');
            $output->writeln('Run: <info>vendor/bin/phinx migrate</info>');
            return Command::SUCCESS;
        }

        // Fallback to Doctrine Migrations
        $migrationsDir = __DIR__ . '/../../db/migrations';
        
        if (!is_dir($migrationsDir)) {
            mkdir($migrationsDir, 0755, true);
        }

        $output->writeln("Migrations directory: <info>{$migrationsDir}</info>");
        $output->writeln('<comment>Doctrine Migrations not fully configured yet.</comment>');
        $output->writeln('Install with: <info>composer require doctrine/migrations</info>');
        
        return Command::SUCCESS;
    }
}
