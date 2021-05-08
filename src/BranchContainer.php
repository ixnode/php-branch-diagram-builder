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

namespace Ixnode\PHPBranchDiagramBuilder;

use Exception;

class BranchContainer
{
    /** @var Branch[] $branches */
    protected array $branches = [];

    /**
     * Returns the branch name from mixed parameter.
     *
     * @param mixed $name
     * @return string
     * @throws Exception
     */
    public function buildName($name): string
    {
        if (gettype($name) === 'array') {
            $name = implode('/', $name);
        }

        if (gettype($name) !== 'string') {
            throw new Exception('Unknown given name type.');
        }

        return $name;
    }

    /**
     * Adds the given new branch by name.
     *
     * @param mixed $name
     * @param Branch $branch
     * @return void
     * @throws Exception
     */
    public function add($name, Branch $branch): void
    {
        $name = $this->buildName($name);

        if (array_key_exists($name, $this->branches)) {
            throw new Exception(sprintf('Branch "%s" already exists.', $name));
        }

        $branch->setName($name);
        $branch->setRow($this->count());

        $this->branches[$name] = $branch;
    }

    /**
     * Returns the branch given by name.
     *
     * @param mixed $name
     * @return Branch
     * @throws Exception
     */
    public function get($name): Branch
    {
        $name = $this->buildName($name);

        if (!array_key_exists($name, $this->branches)) {
            throw new Exception(sprintf('Branch "%s" does not exist.', $name));
        }

        return $this->branches[$name];
    }

    /**
     * Returns all branches.
     *
     * @return Branch[]
     */
    public function getAll(): array
    {
        return $this->branches;
    }

    /**
     * Returns all branch names.
     *
     * @return string[]
     */
    public function getNames(): array
    {
        return array_keys($this->branches);
    }

    /**
     * Returns the number of saved branches.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->branches);
    }
}
