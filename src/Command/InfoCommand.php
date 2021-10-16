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
 * @category InfoCommand
 * @package  Ixnode\PHPBranchDiagramBuilder\Command
 * @author   Björn Hempel <bjoern@hempel.li>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @version  GIT: 1.0.0
 * @link     https://www.hempel.li
 */

namespace Ixnode\PHPBranchDiagramBuilder\Command;

use Ahc\Cli\Application as App;
use Exception;
use Ixnode\PHPBranchDiagramBuilder\Builder;

/**
 * Class InfoCommand
 *
 * @category InfoCommand
 * @package  Ixnode\PHPBranchDiagramBuilder\Command
 * @author   Björn Hempel <bjoern@hempel.li>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @version  Release: @package_version@
 * @link     https://www.hempel.li
 */
class InfoCommand extends BaseCommand
{
    const COMMAND = 'info';

    const ALIAS = 'i';

    const DESCRIPTION = 'Shows information.';

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
        $this->logger->info(
            'Name:    {name}',
            ['name' => Builder::NAME],
            false,
            true
        );
        $this->logger->info(
            'Version: {version}',
            ['version' => Builder::VERSION],
            true,
            true
        );
    }
}
