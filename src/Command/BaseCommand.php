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

namespace Ixnode\PHPBranchDiagramBuilder\Command;

use Ahc\Cli\Application as App;
use Ahc\Cli\Helper\OutputHelper;
use Ahc\Cli\Input\Command;
use Ahc\Cli\IO\Interactor;
use Ahc\Cli\Output\Writer;
use Composer\Autoload\ClassLoader;
use Ixnode\PHPBranchDiagramBuilder\Exception\BaseException;
use Ixnode\PHPBranchDiagramBuilder\Exception\NullException;
use Ixnode\PHPBranchDiagramBuilder\Logger\Logger;
use Exception;
use Ixnode\PHPBranchDiagramBuilder\Tools\Converter;
use Ixnode\PHPBranchDiagramBuilder\TypeCheck\TypeCheck;
use ReflectionClass;
use Throwable;

/**
 * Class BaseCommand
 *
 * @author   Björn Hempel <bjoern@hempel.li>
 * @version  1.0 <2021-10-16>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/ixnode/php-branch-diagram-builder
 * @category Command
 * @package  Ixnode\PHPBranchDiagramBuilder\Command
 */
abstract class BaseCommand extends Command
{
    public const LOGO = 'PHPVault command line interpreter.';

    public const TEMPLATE_SIMPLE_EXCEPTION = '<eol/><boldRed>%s</end><red>: %s</end><eol/>';

    protected Writer $writer;

    protected Logger $logger;

    protected ?string $root;

    /**
     * Abstract function handle to handle all the command stuff.
     *
     * @return void
     */
    abstract public function handle(): void;

    /**
     * BaseCommand constructor.
     *
     * @param string   $name         The name of the base command.
     * @param string   $desc         The description of the base command.
     * @param bool     $allowUnknown The bool value of allow unknown.
     * @param App|null $app          The app.
     *
     * @throws Exception
     */
    public function __construct(string $name, string $desc = '', bool $allowUnknown = false, App $app = null)
    {
        parent::__construct($name, $desc, $allowUnknown, $app);

        /**
         * Gets the Interactor
         *
         * @var Interactor|null $io
         */
        $io = $app instanceof App ? $app->io() : null;

        /* Initiate Writer */
        $this->writer = $io !== null ? $io->writer() : new Writer();

        /* Initiate Logger */
        $this->logger = new Logger($this->writer);

        /* Set root path */
        $this->root = $this->getComposerJsonRootPath();

        /* Add debug option */
        $this->option(
            '-D --debug',
            'Set application in debug mode.',
            function ($value) {
                return TypeCheck::isBoolean($value);
            },
            false
        );
    }

    /**
     * The execute function from Ahc\Cli\Application
     *
     * @return int
     * @throws Exception
     */
    public function execute(): int
    {
        try {
            $this->handle();
        } catch (BaseException $exception) {
            if ($this->getOption('debug')) {
                $this->printTrace($exception);
            } else {
                $this->printSimpleException($exception);
            }

            return $exception->getReturnCode();
        }

        return 0;
    }

    /**
     * Prints a simple exception message.
     *
     * @param Throwable $exception The exception.
     *
     * @return void
     */
    public function printSimpleException(Throwable $exception): void
    {
        $message = sprintf(
            self::TEMPLATE_SIMPLE_EXCEPTION,
            (new ReflectionClass($exception))->getShortName(),
            $exception->getMessage()
        );

        $this->writer->colors($message);
    }

    /**
     * Print stack trace and error msg of an exception.
     *
     * @param Throwable $exception The exception.
     *
     * @return void
     */
    public function printTrace(Throwable $exception): void
    {
        $outputHelper = new OutputHelper($this->io()->writer());
        $outputHelper->printTrace($exception);
    }

    /**
     * Returns an option from command line.
     *
     * @param string      $option                   The option.
     * @param string|bool $default                  The default.
     * @param bool        $replaceWithDefaultIfTrue The bool value of
     *                                              replaceWithDefaultIfTrue.
     *
     * @return mixed
     * @throws Exception
     */
    protected function getOption(string $option, $default = null, bool $replaceWithDefaultIfTrue = false)
    {
        $option = $this->convertToCamelCase($option);

        $return = $this->registered($option) ? $this->$option : $default;

        if ($replaceWithDefaultIfTrue && $return === true) {
            $return = $default;
        }

        return $return;
    }

    /**
     * Returns an argument from command line.
     *
     * @param string $argument The argument.
     * @param null   $default  The default.
     *
     * @return mixed
     * @throws Exception
     */
    protected function getArgument(string $argument, $default = null)
    {
        $arguments = $this->args();

        $argument = $this->convertToCamelCase($argument);

        return array_key_exists($argument, $arguments) ?
            $arguments[$argument] :
            $default;
    }

    /**
     * Converts given string into CamelCase.
     *
     * @param string $value The value.
     *
     * @return string
     * @throws NullException
     */
    protected function convertToCamelCase(string $value): string
    {
        /* Replace capitals to "-capital". */
        $value = Converter::pregReplaceString('~([A-Z])~', '-$1', $value);

        /* Split string by - */
        $array = explode('-', $value);

        /* Convert each string part into "strtolower" and "ucfirst". */
        $array = array_map(
            function ($element) {
                return ucfirst(strtolower($element));
            },
            $array
        );

        /* Rebuild string. */
        return lcfirst(implode('', $array));
    }

    /**
     * Returns the composer.json root path (project path).
     *
     * @return string
     * @throws Exception
     */
    public function getComposerJsonRootPath(): string
    {
        $reflection = new ReflectionClass(ClassLoader::class);

        if ($reflection->getFileName() === false) {
            throw new Exception('The file name of ClassLoader class was not found.');
        }

        return dirname($reflection->getFileName(), 3);
    }
}
