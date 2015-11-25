<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tests\Tools\ToolboxTest.php


namespace Truckee\MatchBundle\Tests\Tools;

use Truckee\MatchBundle\Tools\Toolbox;

/**
 * ToolboxTest.
 */
class ToolboxTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->repository = $this
            ->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->em = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->em->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->repository);
        $this->user = $this->getMock('Truckee\MatchBundle\Tests\Stub\SomeUser',
            array('getUserType'));
        $this->userOptions = array(
            'focus_required' => true,
            'skill_required' => true,
        );
    }

    public function testGetTypeFromId()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->willReturn($this->user);
        $this->user->expects($this->once())
            ->method('getUserType')
            ->willReturn('volunteer');

        $tools = new Toolbox($this->em, $this->userOptions);
        $type = $tools->getTypeFromId(12);

        $this->assertEquals('volunteer', $type);
    }
}
