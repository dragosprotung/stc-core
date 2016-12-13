<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Workout\Workout\Workout;

use SportTrackerConnector\Core\Workout\Author;
use SportTrackerConnector\Core\Workout\Workout;

class WorkoutTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiatingWithoutAuthor()
    {
        $workout = new Workout([]);
        self::assertNull($workout->author());
    }

    public function testInstantiatingWithAuthor()
    {
        $author = new Author('author');
        $workout = new Workout([], $author);
        self::assertSame($author, $workout->author());
    }
}
