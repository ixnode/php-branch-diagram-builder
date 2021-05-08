<?php declare(strict_types=1);

/*
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
use Ixnode\PHPBranchDiagramBuilder\Step;
use Ixnode\PHPBranchDiagramBuilder\Tools\Converter;

class BuildCommand extends BaseCommand
{
    const COMMAND = 'build';

    const ALIAS = 'b';

    const DESCRIPTION = 'Builds the branching diagram.';

    /**
     * GenerateKeysCommand constructor.
     *
     * @param bool $allowUnknown
     * @param App|null $app
     * @throws Exception
     */
    public function __construct(bool $allowUnknown = false, App $app = null)
    {
        parent::__construct(self::COMMAND, self::DESCRIPTION, $allowUnknown, $app);

        $this
            ->argument('<file>', 'The branching diagram source yaml file to convert.')
            ->option('-o --output-file', 'Persists the branching diagram to output file.', null, false)
            ->usage(
                '<bold>  $0 info</end> ## Shows information.<eol/>'
            );
    }

    /**
     * Bootstrap show information function.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
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
        $outputFile = $this->getOption('output-file') ?: Converter::replaceFileExtension($branchingDiagramSourceFile, Builder::PNG_EXTENSION);

        /* Initiate builder */
        $title = array_key_exists('title', $config) ? $config['title'] : Builder::NAME;
        $branchStrategyBuilder = new Builder($title);

        /* Add branches */
        $branches = array_key_exists('branches', $config) ? $config['branches'] : [];
        foreach ($branches as $branch) {
            $branchStrategyBuilder->addBranch($branch['name'], new Branch($branch['color-light'], $branch['color-dark']));
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
