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

/**
 * Class Branch
 *
 * @author   Björn Hempel <bjoern@hempel.li>
 * @version  1.0 <2021-10-16>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/ixnode/php-branch-diagram-builder
 * @category Main
 * @package  Ixnode\PHPBranchDiagramBuilder
 */
class Branch
{
    protected string $name;

    protected ?string $title;

    protected ?string $targetSystem;

    protected int $row;

    protected string $fillColor;

    protected string $strokeColor;

    /**
     * The stroke dash array.
     *
     * @var int[]
     */
    protected array $strokeDashArray = [5, 5];

    protected int $strokeOpacity = 1;

    protected int $strokeWidth = 1;

    protected ?string $textColor;

    protected int $textSize = 20;

    protected ?int $lastStepPosition = null;

    /**
     * Branch constructor.
     *
     * @param string      $colorFill   The color fill.
     * @param string      $colorStroke The color stroke.
     * @param string|null $colorText   The color text.
     */
    public function __construct(
        string $colorFill,
        string $colorStroke,
        string $colorText = null
    ) {
        $this->fillColor = $colorFill;
        $this->strokeColor = $colorStroke;
        $this->textColor = $colorText;

        $this->title = null;
        $this->targetSystem = null;
    }

    /**
     * Sets the name of this branch.
     *
     * @param string $name The name.
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the name of this branch.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the title of this branch.
     *
     * @param string $title The title.
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Returns the title of this branch.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? $this->name;
    }

    /**
     * Sets the target system.
     *
     * @param string|null $targetSystem The target system.
     *
     * @return void.
     */
    public function setTargetSystem(?string $targetSystem): void
    {
        $this->targetSystem = $targetSystem;
    }

    /**
     * Returns the target system.
     *
     * @return string|null
     */
    public function getTargetSystem(): ?string
    {
        return $this->targetSystem;
    }

    /**
     * Sets the row of this branch.
     *
     * @param int $row The row.
     *
     * @return void
     */
    public function setRow(int $row): void
    {
        $this->row = $row;
    }

    /**
     * Returns the row of this branch.
     *
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * Returns the color fill.
     *
     * @return string
     */
    public function getFillColor(): string
    {
        return $this->fillColor;
    }

    /**
     * Returns the color stroke.
     *
     * @return string
     */
    public function getStrokeColor(): string
    {
        return $this->strokeColor;
    }

    /**
     * Returns the dash array stroke.
     *
     * @return int[]
     */
    public function getStrokeDashArray(): array
    {
        return $this->strokeDashArray;
    }

    /**
     * Returns the stroke opacity.
     *
     * @return int
     */
    public function getStrokeOpacity(): int
    {
        return $this->strokeOpacity;
    }

    /**
     * Returns the stroke width.
     *
     * @return int
     */
    public function getStrokeWidth(): int
    {
        return $this->strokeWidth;
    }

    /**
     * Returns the color text.
     *
     * @return string
     */
    public function getTextColor(): string
    {
        return $this->textColor ?? $this->strokeColor;
    }

    /**
     * Returns the text size.
     *
     * @return int
     */
    public function getTextSize(): int
    {
        return $this->textSize;
    }

    /**
     * Sets the last step number of this branch.
     *
     * @param int $lastStepPosition The last step position.
     *
     * @throws Exception
     * @return void
     */
    public function setLastStepPosition(int $lastStepPosition): void
    {
        if ($lastStepPosition < $this->lastStepPosition) {
            throw new Exception(
                sprintf(
                    <<<TEXT
The new last step number "%d" must be greater or equal than the last one %s.
TEXT,
                    $lastStepPosition,
                    $this->lastStepPosition
                )
            );
        }

        $this->lastStepPosition = $lastStepPosition;
    }

    /**
     * Returns the last step number of this branch.
     *
     * @return ?int
     */
    public function getLastStepPosition(): ?int
    {
        return $this->lastStepPosition;
    }
}
