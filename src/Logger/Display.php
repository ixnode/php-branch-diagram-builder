<?php declare(strict_types=1);

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
 *
 * PHP version 8
 *
 * @category Display
 * @package  Ixnode\PHPBranchDiagramBuilder\Logger
 * @author   Björn Hempel <bjoern@hempel.li>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @version  GIT: 1.0.0
 * @link     https://www.hempel.li
 */

namespace Ixnode\PHPBranchDiagramBuilder\Logger;

use Exception;

/**
 * Class Display
 *
 * @category Display
 * @package  Ixnode\PHPBranchDiagramBuilder\Logger
 * @author   Björn Hempel <bjoern@hempel.li>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @version  Release: @package_version@
 * @link     https://www.hempel.li
 */
class Display
{
    protected Logger $logger;

    /**
     * Display constructor.
     *
     * @param Logger $logger The logger.
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Indicates that given file was not found.
     *
     * @param string $file The file.
     *
     * @return void
     * @throws Exception
     */
    public function fileNotFound(string $file): void
    {
        $this->logger->error(
            'The given file "{file}" was not found.',
            array('file' => $file, ),
            true,
            true
        );
    }

    /**
     * Indicates that the given file already exists.
     *
     * @param string $file The file.
     *
     * @return void
     * @throws Exception
     */
    public function fileAlreadyExists(string $file): void
    {
        $this->logger->error(
            'File "{file}" already exist. Abort.',
            ['file' => $file, ],
            true,
            true
        );
    }

    /**
     * Indicates that an error occurred while trying to write the given file.
     *
     * @param string $file The file.
     *
     * @return void
     * @throws Exception
     */
    public function writeFileErrorOccurred(string $file): void
    {
        $this->logger->error(
            'An error occurred while trying to write the env file "{file}".',
            ['file' => $file, ],
            true,
            true
        );
    }

    /**
     * Indicates that the file was successfully written.
     *
     * @param string $file The file.
     *
     * @return void
     * @throws Exception
     */
    public function successfullyWritten(string $file): void
    {
        $this->logger->ok(
            'The given file "{file}" was successfully written.',
            ['file' => $file, ],
            true,
            true
        );
    }
}
