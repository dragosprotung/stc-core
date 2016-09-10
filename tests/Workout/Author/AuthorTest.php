<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Author;

use SportTrackerConnector\Core\Workout\Author;

/**
 * Test for workout author.
 */
class AuthorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testSetGetNameValid();
     *
     * @return array
     */
    public function dataProviderTestSetGetNameValid()
    {
        return array(
            array('100'),
            array('John Doe'),
            array(new TestSetGetNameInvalidToString())
        );
    }

    /**
     * Test setting the name of the author with valid values.
     *
     * @dataProvider dataProviderTestSetGetNameValid
     * @param mixed $name The name to set.
     */
    public function testSetGetNameValid($name)
    {
        $author = new Author($name);
        self::assertEquals($name, $author->name());
    }

    /**
     * Data provider for testSetGetNameValid();
     *
     * @return array
     */
    public function dataProviderTestSetGetNameInvalid()
    {
        return array(
            array(null),
            array(array()),
            array(new \stdClass())
        );
    }

    /**
     * Test setting the name of the author with invalid values.
     *
     * @dataProvider dataProviderTestSetGetNameInvalid
     * @param mixed $name The name to set.
     */
    public function testSetGetNameInvalid($name)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The name of the author must be a string.');

        new Author($name);
    }
}

/**
 * Class that implements __toString for testing setting the author name.
 */
class TestSetGetNameInvalidToString
{
    public function __toString()
    {
        return 'john Doe';
    }
}
