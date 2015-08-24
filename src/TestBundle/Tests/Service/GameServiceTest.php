<?php

namespace TestBundle\Tests\Service;

use TestBundle\Entity\Game;
use TestBundle\Service\GameService;

class GameServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveWhenCreateGame()
    {
        $game = new Game();
        $entityManagerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('persist', 'flush'))
            ->getMock();

        $entityManagerMock->expects($this->once())
        ->method('persist')
        ->with($game);
        $entityManagerMock->expects($this->once())
            ->method('flush');
        $gameService = new GameService($entityManagerMock);
        $gameService->saveGame($game, true);
    }

    public function testSaveWhenUpdateGame()
    {
        $game = new Game();
        $entityManagerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('persist', 'flush'))
            ->getMock();

        $entityManagerMock->expects($this->never())
            ->method('persist');
        $entityManagerMock->expects($this->once())
            ->method('flush');
        $gameService = new GameService($entityManagerMock);
        $gameService->saveGame($game, false);
    }

    public function testRemoveGame()
    {
        $repositoryMock = $this->getMockBuilder('TestBundle\Entity\GameRepository')
            ->disableOriginalConstructor()
            ->setMethods(array('remove'))
            ->getMock();
        $repositoryMock->expects($this->once())
            ->method('remove')
            ->with(1)
            ->will($this->returnValue(1));
        $entityManagerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($repositoryMock));

        $gameService = new GameService($entityManagerMock);
        $result = $gameService->removeGame(1);
        $this->assertEquals(1, $result);
    }
}