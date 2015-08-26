<?php

namespace SportTrackerConnector\Core\Tests\Workout\Workout\Workout\Workout;

use SportTrackerConnector\Core\Workout\Workout;
use SportTrackerConnector\Core\Workout\Author;

class WorkoutTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test setting/getting the author.
     */
    public function testSetGetAuthor()
    {
        $workout = new Workout();

        $this->assertNull($workout->getAuthor());

        $author = new Author();
        $workout->setAuthor($author);
        $this->assertSame($author, $workout->getAuthor());
    }
}
