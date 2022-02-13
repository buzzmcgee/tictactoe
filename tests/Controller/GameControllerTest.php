<?php

namespace Buzz\Tests\Controller;

use Buzz\TTT\Controller\GameController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class GameControllerTest extends TestCase
{
    private ?GameController $gameController = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gameController = new GameController();
    }

    /**
     * @return void
     * @dataProvider coordinateSets
     */
    public function testGetCoordinates($coordinateQueryParameter, $expectedCoordinates)
    {
        $request = new Request(['coordinates' => $coordinateQueryParameter]);

        $coordinates = $this->gameController->getCoordinates($request);
        $this->assertSame($coordinates, $expectedCoordinates);
    }

    /**
     * @return void
     */
    public function testGetCoordinatesEmptyRequest()
    {
        $request = new Request();
        $expectedCoordinates = null;

        $coordinates = $this->gameController->getCoordinates($request);
        $this->assertSame($coordinates, $expectedCoordinates);
    }

    /**
     * @return void
     */
    public function testGetCoordinatesWrongParameterKey()
    {
        $request = new Request(['coordinate' => '0:0']);
        $expectedCoordinates = null;

        $coordinates = $this->gameController->getCoordinates($request);
        $this->assertSame($coordinates, $expectedCoordinates);
    }

    public function coordinateSets(): array
    {
        return [
            'valid 0:0' => ['param' => '0:0', 'expected' => [0, 0]],
            'valid 1:0' => ['param' => '1:0', 'expected' => [1, 0]],
            'valid 2:0' => ['param' => '2:0', 'expected' => [2, 0]],
            'valid 0:1' => ['param' => '0:1', 'expected' => [0, 1]],
            'valid 1:1' => ['param' => '1:1', 'expected' => [1, 1]],
            'valid 2:1' => ['param' => '2:1', 'expected' => [2, 1]],
            'valid 0:2' => ['param' => '0:2', 'expected' => [0, 2]],
            'valid 1:2' => ['param' => '1:2', 'expected' => [1, 2]],
            'valid 2:2' => ['param' => '2:2', 'expected' => [2, 2]],

            'invalid 0,0' => ['param' => '0,0', 'expected' => null],
            'invalid 0|0' => ['param' => '0|0', 'expected' => null],
            'invalid 0:3' => ['param' => '0:3', 'expected' => null],
            'invalid 0:10' => ['param' => '0:10', 'expected' => null],
            'invalid 0:a' => ['param' => '0:a', 'expected' => null],
            'invalid 0:' => ['param' => '0:', 'expected' => null],
            'invalid 0: ' => ['param' => '0: ', 'expected' => null],
            'invalid 0' => ['param' => '0', 'expected' => null],
            'invalid %0' => ['param' => '%0', 'expected' => null],
            'invalid null' => ['param' => null, 'expected' => null],
        ];
    }
}
