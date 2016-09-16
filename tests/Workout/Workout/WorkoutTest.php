<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Workout\Workout\Workout;

use SportTrackerConnector\Core\Workout\Author;
use SportTrackerConnector\Core\Workout\Workout;

class WorkoutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setting/getting the author.
     */
    public function testSetGetAuthor()
    {
        $workout = new Workout();

        self::assertNull($workout->author());

        $author = new Author('author');
        $workout->setAuthor($author);
        self::assertSame($author, $workout->author());
    }
}
