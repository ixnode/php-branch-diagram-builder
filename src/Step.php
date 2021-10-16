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

use Exception;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepEqualSourceAndTargetException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepMissingSourceException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepMissingTargetException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepNotEqualSourceAndTargetException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepUnknownTypeException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepUnnecessarySourceException;

/**
 * Class Step
 *
 * @author   Björn Hempel <bjoern@hempel.li>
 * @version  1.0 <2021-10-16>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/ixnode/php-branch-diagram-builder
 * @category Main
 * @package  Ixnode\PHPBranchDiagramBuilder
 */
class Step
{
    protected string $type;

    protected ?string $source;

    protected string $target;

    protected int $stepPosition;

    /**
     * Step constructor.
     *
     * @param string $type   The type of this step.
     * @param ?mixed $source The source of this step.
     * @param ?mixed $target The target of this step.
     *
     * @throws Exception
     */
    public function __construct(string $type, $source = null, $target = null)
    {
        /* Get and convert source and target */
        switch ($type) {
        case Builder::REPOSITORY_NAME_COMMIT:
            $source = $this->buildName($source);
            $target = $this->buildName($target ?? $source);
            break;

        case Builder::REPOSITORY_NAME_INIT:
        case Builder::REPOSITORY_NAME_CHECKOUT:
        case Builder::REPOSITORY_NAME_MERGE:
            $source = $this->buildName($source);
            $target = $this->buildName($target);
            break;

        default:
            throw new StepUnknownTypeException();
        }

        /* Check source */
        switch (true) {
        case $type === Builder::REPOSITORY_NAME_INIT && $source !== null:
            throw new StepUnnecessarySourceException();

        case $type !== Builder::REPOSITORY_NAME_INIT && $source === null:
            throw new StepMissingSourceException();
        }

        /* Check target */
        if ($target === null) {
            throw new StepMissingTargetException();
        }

        /* Check equal branches */
        switch (true) {
        case $type !== Builder::REPOSITORY_NAME_COMMIT && $source === $target:
            throw new StepEqualSourceAndTargetException();

        case $type === Builder::REPOSITORY_NAME_COMMIT && $source !== $target:
            throw new StepNotEqualSourceAndTargetException();
        }

        /* Add values */
        $this->type = $type;
        $this->source = $source;
        $this->target = $target;
    }

    /**
     * Returns the branch name from mixed parameter.
     *
     * @param mixed $name The build name.
     *
     * @return ?string
     * @throws Exception
     */
    public function buildName($name): ?string
    {
        if ($name === null) {
            return null;
        }

        if (gettype($name) === 'array') {
            $name = implode('/', $name);
        }

        if (gettype($name) !== 'string') {
            throw new Exception('Unknown given name type.');
        }

        return $name;
    }

    /**
     * Returns the type of step.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns the target of step.
     *
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * Returns the source of step.
     *
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * Sets the step position.
     *
     * @param int $stepPosition The step position.
     *
     * @return void
     */
    public function setStepPosition(int $stepPosition): void
    {
        $this->stepPosition = $stepPosition;
    }

    /**
     * Returns the stop position.
     *
     * @return int
     */
    public function getStepPosition(): int
    {
        return $this->stepPosition;
    }
}
