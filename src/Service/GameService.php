<?php

namespace Buzz\TTT\Service;

use Buzz\TTT\Domain\Board;

class GameService
{

    /**
     * Create new Game and Board
     *
     * @return void
     */
    public function createGame(): Board
    {
        return new Board();
    }

    /**
     * Find best next move on board
     *
     * @param Board $board
     * @return null|array
     */
    public function bestNextMove(Board $board): ?array
    {
        $boardLayout = $board->getCurrentLayout();

        $move = null;
        foreach ($boardLayout as $rowIndex => $row) {
            foreach ($row as $colIndex => $col) {
                if ($col === _) {
                    $move = [$rowIndex,$colIndex];
                }
            }
        }

        return $move;
    }

    /**
     * Add Player Move to Board
     *
     * @param Board $board
     * @param int $row
     * @param int $column
     * @param int $value
     * @return void
     */

    public function makeMove(Board $board, int $row, int $column, int $value): bool
    {
        return $board->setCell($row, $column, $value);
    }

    /**
     * Check the current board layout for a winner or tie and update board accordingly
     *
     * @param Board|null $board
     * @return bool
     */
    public function updateWinner(?Board $board): bool
    {
        $cellLayout = $board->getCurrentLayout();

        $winningLines = [
            'r' => [0, 0, 0], // rows
            'c' => [0, 0, 0], // columns
            'd' => [0, 0] // diagonals
        ];

        $emptyFieldsLeft = 0;
        foreach ($cellLayout as $rowIndex => $row) {
            $winningLines['r'][$rowIndex] = array_sum($row);

            $emptyFieldsLeft += array_count_values($row)[0] ?? 0;

            foreach ($row as $colIndex => $col) {
                $winningLines['c'][$colIndex] += $col;
            }
        }

        $winningLines['d'][0] = $cellLayout[0][0] + $cellLayout[1][1] + $cellLayout[2][2];
        $winningLines['d'][1] = $cellLayout[0][2] + $cellLayout[1][1] + $cellLayout[2][0];

        foreach ($winningLines as $set) {
            if (in_array(3 * X, $set)) {
                $board->setWinner(X);
                return true;
            }
            if (in_array(3 * O, $set)) {
                $board->setWinner(O);
                return true;
            }
        }

        // no moves left -> tie
        if (!$board->countEmptySpaces()) {
            $board->setTie();
            return true;
        }

        return false;
    }

    /**
     * Check if given board layout is valid
     *
     * @param array $layout
     * @return bool
     */
    public function isValidLayout(array $layout): bool
    {
        if (count($layout) !== 3) {
            return false;
        }

        $computerTurnCount = 0;
        $playerTurnCount = 0;
        foreach ($layout as $row) {
            if (count($row) !== 3) {
                return false;
            }

            $rowValues = array_count_values($row);

            // search for foreign values in board
            $foreignValues = array_filter(array_keys($rowValues), function ($value) {
                return !in_array($value, [O, X, _]);
            });

            if (!empty($foreignValues)) {
                return false;
            }

            $computerTurnCount += $rowValues[O] ?? 0;
            $playerTurnCount += $rowValues[X] ?? 0;
        }

        // turn count for player and computer are plausible
        return abs($computerTurnCount - $playerTurnCount) <= 1;
    }

    /**
     * Get board layout information for template
     *
     * @return array|array[]|int[][]
     */
    public function getCellLayoutRender(array $currentLayout): array
    {
        $renderArray = [];
        foreach ($currentLayout as $rowIndex => $row) {
            foreach ($row as $colIndex => $col) {
                switch ($col) {
                    case O:
                        $displayValue = 'O';
                        break;
                    case X:
                        $displayValue = 'X';
                        break;
                    default:
                        $displayValue = '';
                }

                $renderArray[$rowIndex][$colIndex] = [
                    'value' => $displayValue,
                    'coordinates' => "$rowIndex:$colIndex",
                ];
            }
        }

        return $renderArray;
    }
}