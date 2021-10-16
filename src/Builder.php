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
use Imagick;
use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use ImagickPixel;
use ImagickPixelException;

/**
 * Class Builder
 *
 * @author   Björn Hempel <bjoern@hempel.li>
 * @version  1.0 <2021-10-16>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/ixnode/php-branch-diagram-builder
 * @category Main
 * @package  Ixnode\PHPBranchDiagramBuilder
 */
class Builder
{
    public const NAME = 'PHPBranchDiagramBuilder';

    public const PNG_EXTENSION = 'png';

    protected Imagick $imagick;

    protected BranchContainer $branchContainer;

    protected StepContainer $stepContainer;

    protected string $title;

    protected int $width = 0;

    protected int $height = 0;

    public const WIDTH = 2500;

    public const START_X = 220;

    public const START_Y = 150;

    public const FINISH_X = 50;

    public const FINISH_Y = 50;

    public const STEP_RADIUS = 15;

    public const STEP_CONNECTION_DISTANCE = 8;

    public const STEP_WIDTH_FIRST = 20;

    public const STEP_WIDTH = 80;

    public const ROW_WIDTH = 80;

    public const COLOR_BACKGROUND = '#fff';

    public const CONNECTION_FILL_COLOR = '#fff';

    public const CONNECTION_FILL_OPACITY = 0;

    public const CONNECTION_STROKE_COLOR = '#606060';

    public const CONNECTION_STROKE_OPACITY = 1;

    public const CONNECTION_STROKE_WIDTH = 3;

    public const FORMAT_OUTPUT = 'png';

    public const REPOSITORY_NAME_INIT = 'init';

    public const REPOSITORY_NAME_CHECKOUT = 'checkout';

    public const REPOSITORY_NAME_COMMIT = 'commit';

    public const REPOSITORY_NAME_MERGE = 'merge';

    public const REPOSITORY_COLOR_INIT = '#606060';

    public const REPOSITORY_COLOR_CHECKOUT = '#0083b3';

    public const REPOSITORY_COLOR_COMMIT = '#00b368';

    public const REPOSITORY_COLOR_MERGE = '#b30000';

    public const REPOSITORY_BRANCH_MASTER = 'master';

    public const REPOSITORY_BRANCH_PRE_MASTER = 'pre-master';

    public const REPOSITORY_BRANCH_HOTFIX = 'hotfix';

    public const REPOSITORY_BRANCH_DEVELOP = 'develop';

    public const REPOSITORY_BRANCH_TESTING = 'testing';

    public const REPOSITORY_BRANCH_FEATURE = 'feature';

    public const TEXT_PADDING = 20;

    public const TITLE_TEXT_SIZE = 40;

    public const TITLE_FILL_COLOR = '#606060';

    public const DISTANCE_TYPE_NONE = 'none';

    public const DISTANCE_TYPE_LEFT = 'left';

    public const DISTANCE_TYPE_RIGHT = 'right';

    public const DISTANCE_TYPE_TOP = 'top';

    public const DISTANCE_TYPE_BOTTOM = 'bottom';

    /**
     * Builder constructor.
     *
     * @param string $title The title.
     * @param int $width The Width.
     */
    public function __construct(string $title, int $width = self::WIDTH)
    {
        $this->branchContainer = new BranchContainer();
        $this->stepContainer = new StepContainer();
        $this->title = $title;
        $this->width = $width;
    }

    /**
     * Adds the given branch by name.
     *
     * @param string $name The name.
     * @param Branch $branch The branch.
     *
     * @return void
     * @throws Exception
     */
    public function addBranch($name, Branch $branch): void
    {
        $this->branchContainer->add($name, $branch);
    }

    /**
     * Returns a branch by name.
     *
     * @param string $name The name.
     *
     * @return Branch
     * @throws Exception
     */
    public function getBranch($name): Branch
    {
        return $this->branchContainer->get($name);
    }

    /**
     * Returns the row by given branch name.
     *
     * @param string $name The name.
     *
     * @return int
     * @throws Exception
     */
    public function getBranchRow(string $name): int
    {
        return $this->getBranch($name)->getRow();
    }

    /**
     * Returns the y row position by given branch name.
     *
     * @param string $name The name.
     * @param string $distanceType The distance type.
     *
     * @return int
     * @throws Exception
     */
    public function getBranchRowY(
        string $name,
        string $distanceType = self::DISTANCE_TYPE_NONE
    ): int {
        return $this->getY($this->getBranchRow($name), $distanceType);
    }

    /**
     * Returns all branches.
     *
     * @return Branch[]
     */
    public function getBranches(): array
    {
        return $this->branchContainer->getAll();
    }

    /**
     * Returns the number of branches.
     *
     * @return int
     */
    public function getNumberOfBranches(): int
    {
        return $this->branchContainer->count();
    }

    /**
     * Adds the given step.
     *
     * @param Step $step The step.
     *
     * @return void
     */
    public function addStep(Step $step): void
    {
        $this->stepContainer->add($step);
    }

    /**
     * Returns all steps.
     *
     * @return Step[]
     */
    public function getSteps(): array
    {
        return $this->stepContainer->getAll();
    }

    /**
     * Sets the last step position given by branch name.
     *
     * @param string $name The name.
     * @param int $lastStep The last step.
     *
     * @return void
     * @throws Exception
     */
    public function setLastStepPosition(string $name, int $lastStep): void
    {
        $this->branchContainer->get($name)->setLastStepPosition($lastStep);
    }

    /**
     * Returns the last step position of given branch name.
     *
     * @param string $name The name.
     *
     * @return ?int
     * @throws Exception
     */
    public function getLastStepPosition(string $name): ?int
    {
        return $this->branchContainer->get($name)->getLastStepPosition();
    }

    /**
     * Returns the width of image.
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width > 0 ? $this->width : self::WIDTH;
    }

    /**
     * Returns the height of image.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return self::START_Y +
            $this->getNumberOfBranches() * self::ROW_WIDTH +
            self::FINISH_Y;
    }

    /**
     * Builds the given branch strategy.
     *
     * @return void
     * @throws ImagickDrawException
     * @throws ImagickException
     * @throws ImagickPixelException
     */
    public function build(): void
    {
        /* Calculates the width and height of the image. */
        $this->width = $this->getWidth();
        $this->height = $this->getHeight();

        /* Initialize image magick */
        $this->imagick = new Imagick();
        $this->imagick->newImage(
            $this->width,
            $this->height,
            self::COLOR_BACKGROUND
        );
        $this->imagick->setImageFormat(self::FORMAT_OUTPUT);

        /* Add the title of diagram. */
        $this->printTitle();

        /* Draw branches */
        $this->drawBranches();

        /* Draw steps */
        $this->drawSteps();
    }

    /**
     * Prints the title to document
     *
     * @return void
     * @throws ImagickException
     * @throws ImagickDrawException
     */
    public function printTitle(): void
    {
        /* Initialize imagick. */
        $draw = new ImagickDraw();

        /* Get metrics */
        $metrics = $this->imagick->queryFontMetrics($draw, $this->title);

        /* Calculates the position of text. */
        $x = round($this->getWidth() / 2);
        $y = round(self::START_Y / 2) - round($metrics['textHeight'] / 3);

        /* Set some properties. */
        //$draw->setFont('Arial');
        $draw->setFontSize(self::TITLE_TEXT_SIZE);
        $draw->setFillColor(new ImagickPixel(self::TITLE_FILL_COLOR));
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setTextAlignment(Imagick::ALIGN_CENTER);

        /* Write text. */
        $draw->annotation($x, $y, $this->title);
        $this->imagick->drawImage($draw);
    }

    /**
     * Draws a given step.
     *
     * @param Step $step The step.
     *
     * @return void
     * @throws ImagickDrawException
     * @throws ImagickException
     * @throws Exception
     */
    public function drawStep(Step $step): void
    {
        /* Draw the step point. */
        $this->drawStepPoint($step);

        /* Draw connections between points. */
        switch ($step->getType()) {
            case self::REPOSITORY_NAME_INIT:
                /* Nothing to do: First point. */
                break;

            case self::REPOSITORY_NAME_CHECKOUT:
                $this->drawStepConnection(
                    $step,
                    $step->getSource(),
                    $step->getTarget(),
                    self::REPOSITORY_COLOR_CHECKOUT
                );
                break;

            case self::REPOSITORY_NAME_COMMIT:
                $this->drawStepConnection(
                    $step,
                    $step->getSource(),
                    $step->getTarget(),
                    self::REPOSITORY_COLOR_COMMIT
                );
                break;

            case self::REPOSITORY_NAME_MERGE:
                $this->drawStepConnection(
                    $step,
                    $step->getSource(),
                    $step->getTarget(),
                    self::REPOSITORY_COLOR_MERGE
                );
                $this->drawStepConnection(
                    $step,
                    $step->getTarget(),
                    $step->getTarget(),
                    self::CONNECTION_STROKE_COLOR
                );
                break;
        }

        /* Remember the last step position. */
        $this->setLastStepPosition($step->getTarget(), $step->getStepPosition());
    }

    /**
     * Draws the step point.
     *
     * @param Step $step The step.
     *
     * @return void
     * @throws ImagickDrawException
     * @throws ImagickException
     * @throws ImagickPixelException
     * @throws Exception
     */
    public function drawStepPoint(Step $step): void
    {
        /* Initialize imagick. */
        $draw = new ImagickDraw();

        /* Get source and target. */
        $source = $step->getSource();
        $target = $step->getTarget();

        /* Calculate position of step. */
        $x = $this->getX($step->getStepPosition());
        $y = $this->getY($this->branchContainer->get($target)->getRow());

        /* Set some properties. */
        $draw->setFillColor(
            new ImagickPixel($this->branchContainer->get($target)->getFillColor())
        );
        //$draw->setStrokeColor(
        //    new ImagickPixel(
        //        $this->branchContainer->get($target)->getStrokeColor()
        //    )
        //);
        $draw->setStrokeOpacity(
            $this->branchContainer->get($target)->getStrokeOpacity()
        );
        //$draw->setStrokeWidth($this->branchContainer->get($target)->getStrokeWidth());
        $draw->setStrokeColor(self::CONNECTION_STROKE_COLOR);
        $draw->setStrokeWidth(self::CONNECTION_STROKE_WIDTH);

        /* Draw step. */
        $draw->circle($x, $y, $x + self::STEP_RADIUS, $y);
        $this->imagick->drawImage($draw);
    }

    /**
     * Draws a connection between the given and the last step.
     *
     * @param Step $step The step.
     * @param ?string $source The source.
     * @param ?string $target The target.
     * @param string $color The color.
     *
     * @return void
     * @throws ImagickDrawException
     * @throws ImagickException
     * @throws Exception
     */
    public function drawStepConnection(
        Step $step,
        ?string $source,
        ?string $target,
        string $color
    ): void {
        /* Check source */
        if ($source === null) {
            throw new Exception('Null source given.');
        }

        /* Check target */
        if ($target === null) {
            throw new Exception('Null target given.');
        }

        /* Initialize imagick. */
        $draw = new ImagickDraw();

        /* We do not have a source. */
        if ($this->getLastStepPosition($source) === null) {
            return;
        }

        /* Calculates from x/y position of connection (source). */
        $fromX = $this->getX(
            $this->getLastStepPosition($source),
            self::DISTANCE_TYPE_LEFT
        );
        $fromY = $this->getBranchRowY($source);

        /* Calculates to x/y position of connection (target). */
        $toX = $this->getX($step->getStepPosition(), self::DISTANCE_TYPE_RIGHT);
        $toY = $this->getBranchRowY($target);

        /* Set some properties. */
        $draw->setFillColor(new ImagickPixel(self::CONNECTION_FILL_COLOR));
        $draw->setFillOpacity(self::CONNECTION_FILL_OPACITY);
        $draw->setStrokeColor(new ImagickPixel($color));
        $draw->setStrokeOpacity(self::CONNECTION_STROKE_OPACITY);
        $draw->setStrokeWidth(self::CONNECTION_STROKE_WIDTH);

        /* Draw connection. */
        $draw->bezier($this->getBezierPoints($fromX, $fromY, $toX, $toY));
        $this->imagick->drawImage($draw);
    }

    /**
     * Draws the dotted branch line.
     *
     * @param Branch $branch The branch.
     *
     * @return void
     * @throws ImagickDrawException
     * @throws ImagickException
     * @throws ImagickPixelException
     */
    public function drawBranchDottedLine(Branch $branch): void
    {
        /* Initialize imagick. */
        $draw = new ImagickDraw();

        /* Calculates position of line. */
        $x = self::START_X;
        $y = self::START_Y + $branch->getRow() * self::ROW_WIDTH;
        $length = $this->width - self::START_X - self::FINISH_X;

        /* Set some properties. */
        $draw->setFillColor(new ImagickPixel('#ffffff'));
        $draw->setFillOpacity(0);
        $draw->setStrokeColor(new ImagickPixel($branch->getStrokeColor()));
        $draw->setStrokeDashArray($branch->getStrokeDashArray());
        $draw->setStrokeOpacity($branch->getStrokeOpacity());
        $draw->setStrokeWidth($branch->getStrokeWidth());

        /* Draw dotted line. */
        $draw->line($x, $y, $x + $length, $y);
        $this->imagick->drawImage($draw);
    }

    /**
     * Prints the branch name.
     *
     * @param Branch $branch The branch.
     *
     * @return void
     * @throws ImagickDrawException
     * @throws ImagickException
     * @throws ImagickPixelException
     */
    public function drawBranchName(Branch $branch): void
    {
        /* Initialize imagick. */
        $draw = new ImagickDraw();

        /* Get metrics */
        $metrics = $this->imagick->queryFontMetrics($draw, $branch->getName());
        $textWidth = $metrics['textWidth'];
        $textHeight = $metrics['textHeight'];

        /* Calculates the position of text. */
        $x = self::START_X - self::TEXT_PADDING;
        $direction = $branch->getTargetSystem() ? -1 : 1;
        $y1 = self::START_Y +
            $branch->getRow() * self::ROW_WIDTH +
            $direction * round($textHeight / 3);

        /* Set some properties. */
        //$draw->setFont('Arial');
        $draw->setFontSize($branch->getTextSize());
        $draw->setFillColor(new ImagickPixel($branch->getTextColor()));
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setTextAlignment(Imagick::ALIGN_RIGHT);

        /* Write text. */
        $draw->annotation($x, $y1, $branch->getName());
        $this->imagick->drawImage($draw);

        /* Write text. */
        $targetSystem = $branch->getTargetSystem();
        if ($targetSystem) {
            $y2 = self::START_Y +
                $branch->getRow() * self::ROW_WIDTH +
                round($textHeight / 3);

            $draw->annotation($x, $y2 + 20, $targetSystem);
            $this->imagick->drawImage($draw);
        }
    }

    /**
     * Draw all branches (lines and text).
     *
     * @return void
     * @throws ImagickDrawException
     * @throws ImagickException
     * @throws ImagickPixelException
     */
    public function drawBranches(): void
    {
        foreach ($this->branchContainer->getAll() as $branch) {
            $this->drawBranchName($branch);
            $this->drawBranchDottedLine($branch);
        }
    }

    /**
     * Draws all steps.
     *
     * @return void
     * @throws ImagickDrawException
     * @throws ImagickException
     */
    public function drawSteps(): void
    {
        foreach ($this->stepContainer->getAll() as $step) {
            $this->drawStep($step);
        }
    }

    /**
     * Returns the bezier configuration of given two points.
     *
     * @param int $fromX The from x.
     * @param int $fromY The from y.
     * @param int $toX The x.
     * @param int $toY The y.
     *
     * @return int[][]
     */
    public function getBezierPoints(
        int $fromX,
        int $fromY,
        int $toX,
        int $toY
    ): array {
        return [
            ['x' => $fromX, 'y' => $fromY],
            ['x' => $toX, 'y' => $fromY],
            ['x' => $fromX, 'y' => $toY],
            ['x' => $toX, 'y' => $toY],
        ];
    }

    /**
     * Calculates the x from given stop and distance type.
     *
     * @param int $step The step.
     * @param string $distanceType The distance type.
     *
     * @return int
     * @throws Exception
     */
    public function getX(
        int $step = 0,
        string $distanceType = self::DISTANCE_TYPE_NONE
    ): int {
        switch ($distanceType) {
            case self::DISTANCE_TYPE_NONE:
                $correction = 0;
                break;

            case self::DISTANCE_TYPE_LEFT:
                $correction = self::STEP_RADIUS + self::STEP_CONNECTION_DISTANCE;
                break;

            case self::DISTANCE_TYPE_RIGHT:
                $correction = -self::STEP_RADIUS - self::STEP_CONNECTION_DISTANCE;
                break;

            default:
                throw new Exception(
                    sprintf('Unknown distance type "%s"', $distanceType)
                );
        }

        return self::START_X +
            $step * self::STEP_WIDTH +
            self::STEP_WIDTH_FIRST +
            $correction;
    }

    /**
     * Calculates the y from given row and distance type.
     *
     * @param int $row The row.
     * @param string $distanceType The distance type.
     *
     * @return int
     * @throws Exception
     */
    public function getY(
        int $row = 0,
        string $distanceType = self::DISTANCE_TYPE_NONE
    ): int {
        switch ($distanceType) {
            case self::DISTANCE_TYPE_NONE:
                $correction = 0;
                break;

            case self::DISTANCE_TYPE_TOP:
                $correction = self::STEP_RADIUS;
                break;

            case self::DISTANCE_TYPE_BOTTOM:
                $correction = -self::STEP_RADIUS;
                break;

            default:
                throw new Exception(
                    sprintf(
                        'Unknown distance type "%s"',
                        $distanceType
                    )
                );
        }

        return self::START_Y + $row * self::ROW_WIDTH + $correction;
    }

    /**
     * Saves the picture to given path.
     *
     * @param string $path The path.
     *
     * @return void
     * @throws ImagickException
     */
    public function savePicture(string $path): void
    {
        file_put_contents($path, $this->imagick->getImageBlob());
    }
}
