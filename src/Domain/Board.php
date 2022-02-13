<?php

namespace Buzz\TTT\Domain;

class Board
{
    private array $cellLayout = [
        [_, _, _],
        [_, _, _],
        [_, _, _],
    ];
    private ?int $winner = null;

    /**
     * @param array|int[][] $cellLayout
     */
    public function __construct(?array $cellLayout = null)
    {
        if (!is_null($cellLayout)) {
            $this->cellLayout = $cellLayout;
        }
    }

    /**
     * Set player value at coordinates
     *
     * @param int $row
     * @param int $column
     * @param int $value
     * @return void
     */
    public function setCell(int $row, int $column, int $value): bool
    {
        // only expected cell values
        if (!in_array($value, [O, X, _])) {
            return false;
        }

        // trying to reset cell or no player value on cell
        if (($value !== _) && $this->cellLayout[$row][$column] !== _) {
            return false;
        }

        $this->cellLayout[$row][$column] = $value;

        return true;
    }

    /**
     * Check how many empty spaces are left
     *
     * @return void
     */
    public function countEmptySpaces()
    {
        $emptyPositions = 0;
        foreach ($this->getCurrentLayout() as $row) {
            // get count of values in row; add the count for empty-field or 0, if all fields are used
            $emptyPositions += array_count_values($row)[_] ?? 0;
        }

        return $emptyPositions;
    }

    /**
     * return current board layout
     *
     * @return void
     */
    public function getCurrentLayout(): array
    {
        return $this->cellLayout;
    }

    /**
     * @return bool
     */
    public function hasWinner(): bool
    {
        return !is_null($this->getWinner());
    }

    /**
     * @return int|null
     */
    public function getWinner(): ?int
    {
        return $this->winner;
    }

    /**
     * @param int $winner
     */
    public function setWinner(int $winner): void
    {
        $this->winner = $winner;
    }

    /**
     * Is it a tie?
     *
     * @return bool
     */
    public function isTie(): bool
    {
        return $this->getWinner() === _;
    }

    /**
     * update board to reflect tie
     *
     * @return void
     */
    public function setTie(): void
    {
        $this->setWinner(_);
    }
}