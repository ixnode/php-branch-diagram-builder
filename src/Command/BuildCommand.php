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
use Exception;
use Ixnode\PHPBranchDiagramBuilder\Builder;
use Ixnode\PHPBranchDiagramBuilder\Branch;
use Ixnode\PHPBranchDiagramBuilder\Exception\FunctionDoesNotExistException;
use Ixnode\PHPBranchDiagramBuilder\Step;
use Ixnode\PHPBranchDiagramBuilder\Tools\Converter;

/**
 * Class BuildCommand
 *
 * @author   Björn Hempel <bjoern@hempel.li>
 * @version  1.0 <2021-10-16>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/ixnode/php-branch-diagram-builder
 * @category Command
 * @package  Ixnode\PHPBranchDiagramBuilder\Command
 */
class BuildCommand extends BaseCommand
{
    public const COMMAND = 'build';

    public const ALIAS = 'b';

    public const DESCRIPTION = 'Builds the branching diagram.';

    public const FUNCTION_NAME_YAML_PARSE_FILE = 'yaml_parse_file';

    /**
     * GenerateKeysCommand constructor.
     *
     * @param bool     $allowUnknown The bool value of allow unknown.
     * @param App|null $app          The app.
     *
     * @throws Exception
     */
    public function __construct(bool $allowUnknown = false, App $app = null)
    {
        parent::__construct(self::COMMAND, self::DESCRIPTION, $allowUnknown, $app);

        $this
            ->argument(
                '<file>',
                'The branching diagram source yaml file to convert.'
            )
            ->option(
                '-o --output-file',
                'Persists the branching diagram to output file.',
                null,
                false
            )
            ->usage(
                '<bold>  $0 info</end> ## Shows information.<eol/>'
            );
    }

    /**
     * System pre-check.
     *
     * @return void
     *
     * @throws FunctionDoesNotExistException
     */
    protected function preCheck(): void
    {
        if (!function_exists(self::FUNCTION_NAME_YAML_PARSE_FILE)) {
            throw new FunctionDoesNotExistException(
                self::FUNCTION_NAME_YAML_PARSE_FILE
            );
        }
    }

    /**
     * Bootstrap show information function.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        /* Pre-check */
        $this->preCheck();

        /* Gets the branching diagram source yaml file to convert. */
        $branchingDiagramSourceFile = $this->getArgument('file');

        /* Check, if the branching diagram source file exists. */
        if (!file_exists($branchingDiagramSourceFile)) {
            $this->logger->getDisplay()->fileNotFound($branchingDiagramSourceFile);
            return;
        }

        /* Parse yaml file */
        $config = yaml_parse_file($branchingDiagramSourceFile);

        /* Gets the output file. */
        $outputFile = $this->getOption('output-file') ?:
            Converter::replaceFileExtension(
                $branchingDiagramSourceFile,
                Builder::PNG_EXTENSION
            );

        /* Initiate builder */
        $title = array_key_exists('title', $config) ?
            $config['title'] :
            Builder::NAME;
        $width = array_key_exists('width', $config) ?
            $config['width'] :
            Builder::WIDTH;
        $branchStrategyBuilder = new Builder($title, $width);

        /* Add branches */
        $branches = array_key_exists('branches', $config) ? $config['branches'] : [];
        foreach ($branches as $branch) {
            /* Get configs */
            $name = array_key_exists('name', $branch) ? $branch['name'] : null;
            $colorFill = array_key_exists('color-light', $branch) ?
                $branch['color-light'] :
                Builder::CONNECTION_FILL_COLOR;
            $colorStroke = array_key_exists('color-dark', $branch) ?
                $branch['color-dark'] :
                Builder::CONNECTION_STROKE_COLOR;

            /* Build branch. */
            $branchInstance = new Branch($colorFill, $colorStroke);

            /* Add target system if available */
            if (array_key_exists('system', $branch)) {
                $branchInstance->setTargetSystem($branch['system']);
            }

            /* Add branch. */
            $branchStrategyBuilder->addBranch($name, $branchInstance);
        }

        /* Add steps */
        $steps = array_key_exists('steps', $config) ? $config['steps'] : [];
        foreach ($steps as $step) {
            $type = array_key_exists('type', $step) ? $step['type'] : null;
            $source = array_key_exists('source', $step) ? $step['source'] : null;
            $target = array_key_exists('target', $step) ? $step['target'] : null;

            $branchStrategyBuilder->addStep(new Step($type, $source, $target));
        }

        /* Build and save chart. */
        $branchStrategyBuilder->build();
        $branchStrategyBuilder->savePicture($outputFile);

        /* Check output file. */
        if (!file_exists($outputFile)) {
            $this->logger->getDisplay()->writeFileErrorOccurred($outputFile);
            return;
        }

        /* Display success. */
        $this->logger->getDisplay()->successfullyWritten($outputFile);
    }
}
