<?php
require_once dirname(__FILE__) . '/../DatabaseTestCase.php';
require_once dirname(__FILE__) . '/../../../models/thread.php';

class ThreadTest extends DatabaseTestCase
{

    /**
     * Returns the test dataset.
     *
     * @return \PHPUnit\DbUnit\DataSet\IDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\YamlDataSet(dirname(__FILE__) . '/_files/thread.yml');
    }

    public function test_getTest()
    {
        $thread = Thread::get(1);
        $this->assertEquals('1', $thread->id);
        $this->assertEquals('test', $thread->title);
        $this->assertEquals('2019-03-27 00:00:00', $thread->created);

        //$this->assertEquals($this->getDataSet()->getTable('thread')->getRow(0), (array)$thread);
    }

    public function getTearDownOperation()
    {
        return \PHPUnit\DbUnit\Operation\Factory::TRUNCATE();
    }
}