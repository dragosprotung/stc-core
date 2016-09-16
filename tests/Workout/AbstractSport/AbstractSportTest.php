<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\AbstractSport;

use SportTrackerConnector\Core\Workout\AbstractSportMapper;
use SportTrackerConnector\Core\Workout\SportMapperInterface;

/**
 * Test for AbstractSport.
 */
class AbstractSportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get sport from code returns correct sport.
     */
    public function testGetSportFromCodeReturnsCorrectSport()
    {
        $mock = $this->getMockBuilder(AbstractSportMapper::class)
            ->setMethods(array('getMap'))
            ->getMockForAbstractClass();
        $mock->expects(self::once())
            ->method('getMap')
            ->willReturn(
                array(
                    SportMapperInterface::SWIMMING => 'swim_sport',
                    SportMapperInterface::GOLF => 'golf'
                )
            );

        /** @var AbstractSportMapper $mock */
        $actual = $mock->sportFromCode('swim_sport');
        self::assertSame(SportMapperInterface::SWIMMING, $actual);
    }

    /**
     * Test get sport from code returns other sport if code not defined.
     */
    public function testGetSportFromCodeReturnsOtherSportIfCodeNotDefined()
    {
        $mock = $this->getMockBuilder(AbstractSportMapper::class)
            ->setMethods(array('getMap'))
            ->getMockForAbstractClass();
        $mock->expects(self::once())
            ->method('getMap')
            ->willReturn(array());

        /** @var AbstractSportMapper $mock */
        $actual = $mock->sportFromCode('unexisting_code');
        self::assertSame(SportMapperInterface::OTHER, $actual);
    }

    /**
     * Test get code from sport throws exception if sport is not mapped
     */
    public function testGetCodeFromSportThrowsExceptionIfSportIsNotMapped()
    {
        $mock = $this->getMockBuilder(AbstractSportMapper::class)
            ->setMethods(array('getMap'))
            ->getMockForAbstractClass();
        $mock->expects(self::once())
            ->method('getMap')
            ->willReturn(array());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Sport not mapped.');

        /** @var AbstractSportMapper $mock */
        $mock->codeFromSport(SportMapperInterface::SWIMMING);
    }

    /**
     * Test get code from sport returns other sport if original sport not defined but other is defined.
     */
    public function testGetCodeFromSportReturnsOtherSportIfOriginalSportNotDefinedButOtherIsDefined()
    {
        $mock = $this->getMockBuilder(AbstractSportMapper::class)
            ->setMethods(array('getMap'))
            ->getMockForAbstractClass();
        $mock->expects(self::once())
            ->method('getMap')
            ->willReturn(
                array(
                    SportMapperInterface::SWIMMING => 'swim_sport',
                    SportMapperInterface::GOLF => 'golf',
                    SportMapperInterface::OTHER => 'other_sport'
                )
            );

        /** @var AbstractSportMapper $mock */
        $actual = $mock->codeFromSport(SportMapperInterface::RUNNING);
        self::assertSame('other_sport', $actual);
    }

    /**
     * Test get code from sport returns correct sport.
     */
    public function testGetCodeFromSportReturnsCorrectSport()
    {
        $mock = $this->getMockBuilder(AbstractSportMapper::class)
            ->setMethods(array('getMap'))
            ->getMockForAbstractClass();
        $mock->expects(self::once())
            ->method('getMap')
            ->willReturn(
                array(
                    SportMapperInterface::SWIMMING => 'swim_sport',
                    SportMapperInterface::RUNNING => 'running_hard',
                    SportMapperInterface::OTHER => 'other_sport'
                )
            );

        /** @var AbstractSportMapper $mock */
        $actual = $mock->codeFromSport(SportMapperInterface::RUNNING);
        self::assertSame('running_hard', $actual);
    }
}
