<?php
require_once __DIR__ . '/../../../../BaseTestCase.php';

use Battleships\Model\Matrix,
    Battleships\Model\Game\Battleships;

class BoardTest extends BaseTestCase
{
    protected $data;
    protected $board;

    public function setUp()
    {
        parent::setUp();

        $this->data = new Matrix(10, 10);
        $this->data->set(1, 1, 0);
        $this->data->set(1, 2, 0);
        $this->board = new Battleships\Board($this->data);
    }

    public function testGet()
    {
        $this->assertSame($this->data->get(1, 1), $this->board->get(1, 1));
        $this->assertNull($this->board->get(1, 3));

        try {
            $this->board->get(11, 10);
            $this->fail('Expected exception not thrown');
        } catch(\Exception $e) {}

        try {
            $this->board->get(10, 11);
            $this->fail('Expected exception not thrown');
        } catch(\Exception $e) {}
    }

    public function testGetBoardAndHideShips()
    {
        $board = $this->board;
        $boardArray = $this->board->getBoardAndHideShips();

        $this->assertArrayHasKey(1, $boardArray);
        $this->assertArrayHasKey(1, $boardArray[1]);
        $this->assertEquals($board::SIGN_CELL_NOT_SHOWN, $boardArray[1][1]);

        $this->assertArrayHasKey(2, $boardArray[1]);
        $this->assertEquals($board::SIGN_CELL_NOT_SHOWN, $boardArray[1][2]);
    }

    public function testSetShip()
    {
        $this->board->setShip(1, 3, 1);

        $this->assertSame(1, $this->board->get(1, 3));
    }

    /**
     * @expectedException \Exception
     */
    public function testSetIncorrectShip()
    {
        $this->board->setShip(1, 3, -1);
        $this->board->setShip(1, 3, 'string');
        $this->board->setShip(1, 3, null);
    }

    public function testIsShip()
    {
        $this->assertTrue($this->board->isShip(1, 1));
        $this->assertTrue($this->board->isShip(1, 2));
        $this->assertFalse($this->board->isShip(1, 3));
    }

    public function testShipExists()
    {
        $this->data->set(1, 3, 1);

        $this->assertTrue($this->board->shipExists(0));
        $this->assertTrue($this->board->shipExists(1));
        $this->assertFalse($this->board->shipExists(2));
    }

    public function testShipsLeft()
    {
        $this->data->set(1, 3, 1);
        $this->data->set(1, 4, '-');

        $this->assertEquals(2, $this->board->shipsLeft());


        $data = new Matrix(10, 10);
        $board = new Battleships\Board($data);

        $this->assertEquals(0, $board->shipsLeft());
    }

    public function testMark()
    {
        $this->data->set(1, 3, 1);

        $board = $this->board;
        $board->mark(1, 1);
        $this->assertEquals($board::SIGN_STRIKE_SUCCESSFUL, $board->get(1, 1));

        $board->mark(1, 5);
        $this->assertEquals($board::SIGN_STRIKE_UNSUCCESSFUL, $board->get(1, 5));
    }
}