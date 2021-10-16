<?php

declare(strict_types=1);

/**
 * MIT License
 *
 * Copyright (c) 2021 Björn Hempel <bjoern@hempel.li>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Ixnode\PHPBranchDiagramBuilder;

use Ahc\Cli\Application;
use Ahc\Cli\IO\Interactor;
use Ixnode\PHPBranchDiagramBuilder\Command\BaseCommand;
use Ixnode\PHPBranchDiagramBuilder\Command\BuildCommand;
use Ixnode\PHPBranchDiagramBuilder\Command\InfoCommand;
use Exception;

/**
 * Class Cli
 *
 * @author   Björn Hempel <bjoern@hempel.li>
 * @version  1.0 <2021-10-16>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/ixnode/php-branch-diagram-builder
 * @category Main
 * @package  Ixnode\PHPBranchDiagramBuilder
 */
class Cli
{
    public const FILE_NAME_VERSION = 'VERSION';

    /**
     * The argv.
     *
     * @var string[] $argv
     */
    protected array $argv = array();

    /**
     * The argv loaded.
     *
     * @var bool $argvLoaded
     */
    protected bool $argvLoaded = false;

    /**
     * The interactor.
     *
     * @var Interactor|null $interactor
     */
    protected ?Interactor $interactor = null;

    /**
     * The callable to perform exit
     *
     * @var callable $onExit
     */
    protected $onExit;

    /**
     * Cli constructor.
     *
     * @param ?string $command The command.
     * @param ?Interactor $interactor The interactor.
     * @param ?callable $onExit The on exit.
     *
     * @throws Exception
     */
    public function __construct(string $command = null, Interactor $interactor = null, callable $onExit = null)
    {
        // @codeCoverageIgnoreStart
        $this->onExit = $onExit ?? function ($exitCode = 0) {
            exit($exitCode);
        };
        // @codeCoverageIgnoreEnd

        if ($interactor !== null) {
            $this->interactor = $interactor;
        }

        /* Parse given command */
        if (!$this->argvLoaded && $command) {
            $this->setArgv($this->parseCommand($command));
        }

        /* Use cli arguments */
        if (!$this->argvLoaded && array_key_exists('argv', $_SERVER)) {
            $this->setArgv($_SERVER['argv']);
        }

        /* No arguments given */
        if (!$this->argvLoaded) {
            throw new Exception();
        }
    }

    /**
     * Parse given command and split into arguments with the help of bash.
     *
     * @param string $command The command.
     *
     * @return string[]
     */
    public function parseCommandWithBash(string $command): array
    {
        $parsed = array();

        /* Simulate bash argument parser */
        exec(sprintf('for i in %s; do echo $i; done', $command), $parsed);

        return $parsed;
    }

    /**
     * Parse given command and split into arguments with the help of str_getcsv.
     *
     * @param string $command The command.
     *
     * @return string[]
     */
    public function parseCommand(string $command): array
    {
        $parsed = str_getcsv($command, ' ');

        return array_values(
            array_filter(
                $parsed,
                function ($value) {
                    return ($value !== null && $value !== false && $value !== "");
                }
            )
        );
    }

    /**
     * Set argument list.
     *
     * @param string[] $argv The argv.
     *
     * @return void
     */
    protected function setArgv(array $argv): void
    {
        $this->argv = $argv;

        $this->argvLoaded = true;
    }

    /**
     * Get parsed argument list.
     *
     * @return string[]
     */
    public function getArgv(): array
    {
        return $this->argv;
    }

    /**
     * Handle cli commands.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        $filePathVersion = sprintf('%s/%s', dirname(__DIR__), self::FILE_NAME_VERSION);

        /* Check file path */
        if (!file_exists($filePathVersion)) {
            throw new Exception(sprintf('The given file "%s" does not exists.', $filePathVersion));
        }

        /* Read version file */
        $version = sprintf('v%s', file_get_contents($filePathVersion));

        /* Init App with name and version */
        $app = new Application(Builder::NAME, $version, $this->onExit);

        if ($this->interactor !== null) {
            $app->io($this->interactor);
        }

        /* Add commands with optional aliases */
        $app->add(new InfoCommand(false, $app), InfoCommand::ALIAS);
        $app->add(new BuildCommand(false, $app), BuildCommand::ALIAS);

        /* Set logo */
        $app->logo(BaseCommand::LOGO);

        /* Handle arguments */
        $app->handle($this->getArgv());
    }
}
