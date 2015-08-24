<?php

namespace TestBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TestBundle\Entity\Game;

class GameService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Persist a game
     *
     * @param Game $data
     * @param $isNew
     */
    public function saveGame(Game $data, $isNew)
    {
        if ($isNew === true) {
            $this->em->persist($data);
        }
        $this->em->flush();
    }

    /**
     * Delete a game
     *
     * @param $id
     * @return mixed
     */
    public function removeGame($id)
    {
        return $this->em->getRepository('TestBundle:Game')->remove($id);
    }
}