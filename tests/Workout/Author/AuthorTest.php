<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Author;

use Assert\AssertionFailedException;
use SportTrackerConnector\Core\Workout\Author;

/**
 * Test for workout author.
 */
class AuthorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testValidName();
     *
     * @return array
     */
    public function dataProviderTestValidName()
    {
        return array(
            array('100'),
            array('John Doe')
        );
    }

    /**
     * Test setting the name of the author with valid values.
     *
     * @dataProvider dataProviderTestValidName
     * @param mixed $name The name to set.
     */
    public function testValidName($name)
    {
        $author = new Author($name);
        self::assertEquals($name, $author->name());
    }

    public function testNameEmpty()
    {
        $this->expectException(AssertionFailedException::class);

        new Author('');
    }
}
