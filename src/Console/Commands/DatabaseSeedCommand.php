<?php

declare(strict_types=1);

namespace src\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseSeedCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('database:seed')
            ->setDescription('Seed the database')
            ->addArgument('class', InputArgument::OPTIONAL, 'Specific seeder class to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seedsDir = __DIR__ . '/../../db/seeds';
        
        if (!is_dir($seedsDir)) {
            $output->writeln('<error>Seeds directory not found!</error>');
            return Command::FAILURE;
        }

        $specificClass = $input->getArgument('class');
        
        if ($specificClass) {
            $seedFile = $seedsDir . '/' . $specificClass . '.php';
            
            if (!file_exists($seedFile)) {
                $output->writeln("<error>Seeder {$specificClass} not found!</error>");
                return Command::FAILURE;
            }
            
            $output->writeln("Running seeder: <info>{$specificClass}</info>");
            require_once $seedFile;
            
            if (class_exists($specificClass)) {
                $seeder = new $specificClass();
                if (method_exists($seeder, 'run')) {
                    $seeder->run();
                    $output->writeln('<fg=green>Seeder completed successfully!</fg=green>');
                }
            }
        } else {
            // Run all seeders
            $output->writeln('<info>Running all seeders...</info>');
            
            $files = glob($seedsDir . '/*.php');
            
            foreach ($files as $file) {
                $className = pathinfo($file, PATHINFO_FILENAME);
                $output->write("  Seeding <comment>{$className}</comment>... ");
                
                require_once $file;
                
                if (class_exists($className)) {
                    $seeder = new $className();
                    if (method_exists($seeder, 'run')) {
                        $seeder->run();
                        $output->writeln('<fg=green>done</fg=green>');
                    }
                }
            }
            
            $output->writeln("\n<info>All seeders completed!</info>");
        }
        
        return Command::SUCCESS;
    }
}
