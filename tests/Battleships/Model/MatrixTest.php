<?php
require __DIR__ . '/../../../vendor/autoload.php';

use Battleships\Model\Matrix;

class MatrixTest extends \Silex\WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . '/../../../app/app.php';
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongRowsArgument()
    {
        $matrix = new Matrix('a', null);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongColsArgument()
    {
        $matrix = new Matrix(10, null);
    }

    public function testSetAndGet()
    {
        $matrix = new Matrix(10, 10);
        $matrix->set(1, 1, 1);
        $this->assertEquals(1, $matrix->get(1, 1));
    }

    /**
     * @expectedException \Exception
     */
    public function testSetNotExistingRow()
    {
        $matrix = new Matrix(10, 10);
        $matrix->set(11, 10, 1);
    }

    /**
     * @expectedException \Exception
     */
    public function testSetNotExistingColumn()
    {
        $matrix = new Matrix(10, 10);
        $matrix->set(10, 11, 1);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetNotExistingRow()
    {
        $matrix = new Matrix(10, 10);
        $matrix->get(11, 10, 1);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetNotExistingColumn()
    {
        $matrix = new Matrix(10, 10);
        $matrix->get(10, 11, 1);
    }

    public function testWalk()
    {
        $rows = 10;
        $cols = 10;
        $matrix = new Matrix($rows, $cols);

        $data = $matrix->toArray();

        $this->assertEquals($rows, count($data));

        for ($row = 1; $row <= $rows; $row++) {
            $this->assertArrayHasKey($row, $data);
            $this->assertEquals($cols, count($data[$row]));

            for ($col = 1; $col <= $cols; $col++) {
                $this->assertArrayHasKey($col, $data[$row]);
            }
        }
    }
}