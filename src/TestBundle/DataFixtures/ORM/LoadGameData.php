<?php

namespace TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TestBundle\Entity\Game;

class LoadGameData implements FixtureInterface
{
    static public $games = array();

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $game = new Game();
        $game->setName('League of Legends');
        $game->setNbPlayer(5);
        $game->setPlatform('PC');
        $game->setLaunchDate(new \DateTime('now'));

        $game2 = new Game();
        $game2->setName('Gravity Rush');
        $game2->setNbPlayer(1);
        $game2->setPlatform('PS Vita');
        $game2->setLaunchDate(new \DateTime('now'));

        $game3 = new Game();
        $game3->setName('The Last of Us');
        $game3->setNbPlayer(2);
        $game3->setPlatform('PS4');
        $game3->setLaunchDate(new \DateTime('now'));

        $manager->persist($game);
        $manager->persist($game2);
        $manager->persist($game3);
        $manager->flush();

        self::$games[] = $game;
        self::$games[] = $game2;
        self::$games[] = $game3;
    }
}