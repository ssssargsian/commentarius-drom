<?php

namespace App\Shared\Service;
use InvalidArgumentException;
use RecursiveIteratorIterator;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

final readonly class DirectoryFileSumService
{
    public function calculateSumCountsInDirectory(string $directory): int
    {
        $totalSum = 0;

        if (!is_dir($directory)) {
            throw new InvalidArgumentException('The provided path is not a directory');
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === 'count') {
                $content = file_get_contents($file->getPathname());
                $totalSum += (int)trim($content);
            }
        }

        return $totalSum;
    }
}