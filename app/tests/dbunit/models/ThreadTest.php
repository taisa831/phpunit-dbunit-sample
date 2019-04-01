<?php
require_once dirname(__FILE__) . '/../DatabaseTestCase.php';
require_once dirname(__FILE__) . '/../../../models/thread.php';

use PHPUnit\DbUnit\DataSet\YamlDataSet;

class ThreadTest extends DatabaseTestCase
{
    /**
     * Returns the test dataset.
     */
    protected function getDataSet()
    {
        return new YamlDataSet(dirname(__FILE__) . '/_files/thread.yml');
    }

    public function test_getTest()
    {
        $thread = Thread::get(1);
        $this->assertEquals('1', $thread->id);
        $this->assertEquals('test', $thread->title);
        $this->assertEquals('2019-03-27 00:00:00', $thread->created);
    }

    public function test_getAll()
    {
        $threadAll = Thread::getAll();
        $this->assertEquals('1', $threadAll[0]->id);
        $this->assertEquals(1, $this->getConnection()->getRowCount('thread'));
    }

    public function test_getComments()
    {
        $thread = Thread::get(1);
        $comments = $thread->getComments();
        $this->assertEquals('1', $comments[0]->id);
    }

    public function test_create()
    {
        $comment = new Comment();
        $comment->thread_id = 1;
        $comment->username = 'user';
        $comment->body = 'comment';

        $thread = Thread::get(1);
        $thread->title = 'hoge';
        $thread->create($comment);

        $threadQueryTable = $this->getConnection()->createQueryTable('thread', 'select * from thread');
        $this->assertEquals(2, $threadQueryTable->getRowCount());
        $this->assertEquals('hoge', $threadQueryTable->getValue(1, 'title'));

        $commentQueryTable = $this->getConnection()->createQueryTable('comment', 'select * from comment');
        $this->assertEquals(2, $commentQueryTable->getRowCount());
        $this->assertEquals('user', $commentQueryTable->getValue(1, 'username'));
        $this->assertEquals('comment', $commentQueryTable->getValue(1, 'body'));
    }

    public function test_write()
    {
        $comment = new Comment();
        $comment->thread_id = 1;
        $comment->username = 'user';
        $comment->body = 'comment';

        $thread = Thread::get(1);
        $thread->write($comment);

        $commentQueryTable = $this->getConnection()->createQueryTable('comment', 'select * from comment');
        $this->assertEquals(2, $commentQueryTable->getRowCount());
        $this->assertEquals('user', $commentQueryTable->getValue(1, 'username'));
        $this->assertEquals('comment', $commentQueryTable->getValue(1, 'body'));
    }

    public function getTearDownOperation()
    {
        return \PHPUnit\DbUnit\Operation\Factory::TRUNCATE();
    }

}