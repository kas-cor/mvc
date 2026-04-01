<?php

declare(strict_types=1);

namespace src\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Clear all application caches');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cacheDirs = [
            __DIR__ . '/../../cache/doctrine',
            __DIR__ . '/../../cache/twig',
            __DIR__ . '/../../cache/data',
        ];

        $cleared = 0;
        
        foreach ($cacheDirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $output->write("Clearing <info>{$dir}</info>... ");
            
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($iterator as $file) {
                if ($file->isDir()) {
                    @rmdir($file->getPathname());
                } else {
                    @unlink($file->getPathname());
                }
            }

            $cleared++;
            $output->writeln('<fg=green>done</>');
        }

        // Clear OPcache if available
        if (function_exists('opcache_reset')) {
            $output->write("Clearing OPcache... ");
            opcache_reset();
            $output->writeln('<fg=green>done</>');
        }

        $output->writeln("\n<info>Cache cleared successfully!</info>");
        
        return Command::SUCCESS;
    }
}
