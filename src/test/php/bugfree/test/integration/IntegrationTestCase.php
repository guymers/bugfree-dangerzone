<?php

namespace bugfree\test\integration;

use bugfree\cli\Lint;
use Symfony\Component\Console\Tester\CommandTester;

abstract class IntegrationTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $tempDir;

    /** @var CommandTester */
    private $commandTester;

    public function setUp()
    {
        $basedir = __DIR__ . "/../../../../../..";

        if (!defined("BASEDIR")) {
            define("BASEDIR", $basedir);
        }

        $srcFolder = $basedir . "/src/test/resources/testfiles";

        $prefix = str_replace("\\", "_", get_class($this)) . "_";
        $this->tempDir = sys_get_temp_dir() . "/" . uniqid($prefix);

        $this->recursiveCopy($srcFolder, $this->tempDir);

        $command = new Lint();
        $this->commandTester = new CommandTester($command);
    }

    // http://ben.lobaugh.net/blog/864/php-5-recursively-move-or-copy-files
    private function recursiveCopy($srcFolder, $destFolder)
    {
        if (!is_dir($srcFolder)) {
            return;
        }

        if (!is_dir($destFolder)) {
            if (!mkdir($destFolder)) {
                return;
            }
        }

        $iterator = new \DirectoryIterator($srcFolder);

        foreach ($iterator as $fileInfo) {
            $source = $fileInfo->getRealPath();
            $destination = "$destFolder/$fileInfo";

            if ($fileInfo->isFile()) {
                copy($source, $destination);
            } elseif ($fileInfo->isDir() && !$fileInfo->isDot()) {
                $this->recursiveCopy($source, $destination);
            }
        }
    }

    public function tearDown()
    {
        $this->recursiveRemoveDirectory($this->tempDir);
    }

    // http://stackoverflow.com/a/15111679
    private function recursiveRemoveDirectory($directory)
    {
        if (!is_dir($directory)) {
            return;
        }

        $directoryIterator = new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $path) {
            $pathname = $path->getPathname();

            if ($path->isFile()) {
                unlink($pathname);
            } else {
                rmdir($pathname);
            }
        }

        rmdir($directory);
    }

    protected function lintPath($path, $autofix = false)
    {
        $params = [
            "--bootstrap" => $this->tempDir . "/bootstrap.php",
            "--basedir" => $this->tempDir,
            "files" => [$this->tempDir . "/" . $path]
        ];

        if ($autofix) {
            $params["--autoFix"] = true;
        }

        $exitCode = $this->commandTester->execute($params);

        return $exitCode;
    }

    protected function autofixPath($path)
    {
        return $this->lintPath($path, true);
    }
}
