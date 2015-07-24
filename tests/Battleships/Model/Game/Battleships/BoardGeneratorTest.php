<?php
require_once __DIR__ . '/../../../../BaseTestCase.php';

use Battleships\Model\Game\Battleships,
    Battleships\Model\Matrix;

class BoardGeneratorTest extends BaseTestCase
{
    public function testGenerate()
    {
        $rows = 10;
        $cols = 10;
        $boardGenerator = new Battleships\BoardGenerator(new Battleships\Board(new Matrix($rows, $cols)), [2, 3, 4]);
        $board = $boardGenerator->generate();


        $ships = $this->extractShips($board);

        $this->validateShipsLength($ships);

        for($i=0,$c=count($ships); $i<$c; $i++) {
            $shipType = $this->detectShipType($ships[$i]);

            if ($shipType == 'unknown') {
                $this->fail("Unknown ship type {$i}");
            } elseif ($shipType == 'horizontal' && !$this->validHorizontalShip($ships[$i])) {
                $this->fail("Invalid horizontal ship {$i}");
            } elseif ($shipType == 'vertical' && !$this->validVerticalShip($ships[$i])) {
                $this->fail("Invalid vertical ship {$i}");
            }
        }
    }

    private function extractShips($board)
    {
        $ships = [];
        $board->walk(function ($row, $col) use ($board, &$ships) {
            if ($board->isShip($row, $col)) {
                $shipId = $board->get($row, $col);

                if (!isset($ships[$shipId])) {
                    $ships[$shipId] = [];
                }

                $ships[$shipId][] = ['row' => $row, 'col' => $col];
            }
        });

        return $ships;
    }

    private function validateShipsLength($ships)
    {
        $this->assertCount(3, $ships);

        $this->assertArrayHasKey(0, $ships);
        $this->assertCount(2, $ships[0]);

        $this->assertArrayHasKey(1, $ships);
        $this->assertCount(3, $ships[1]);

        $this->assertArrayHasKey(2, $ships);
        $this->assertCount(4, $ships[2]);
    }

    private function detectShipType($coords)
    {
        $isHorizontal = count($coords) == 1 || (isset($coords[1]) && $coords[0]['col'] + 1 == $coords[1]['col']);
        if ($isHorizontal) {
            return 'horizontal';
        }

        $isVertical = isset($coords[1]) && $coords[0]['row'] + 1 == $coords[1]['row'];
        if ($isVertical) {
            return 'vertical';
        }

        return 'unknown';
    }

    private function validHorizontalShip($coords)
    {
        for ($i=0,$c=count($coords); $i<$c; $i++) {
            if (isset($coords[$i+1]) && $coords[$i]['col'] + 1 != $coords[$i+1]['col']) {
                return false;
            }
        }

        return true;
    }

    private function validVerticalShip($coords)
    {
        for ($i=0,$c=count($coords); $i<$c; $i++) {
            if (isset($coords[$i+1]) && $coords[$i]['row'] + 1 != $coords[$i+1]['row']) {
                return false;
            }
        }

        return true;
    }
}