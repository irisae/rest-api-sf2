<?php

namespace TestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use TestBundle\Entity\Game;
use TestBundle\Form\GameType;
use FOS\RestBundle\Controller\Annotations as Rest;

class RestController extends FOSRestController
{
    /**
     * Get a list of games
     *
     * @return array
     * @Rest\View(serializerGroups={"list_games"})
     */
    public function getGamesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository('TestBundle:Game')->findAll();
        if (empty($games)) {
            return [];
        }

        return $games;

    }

    /**
     * Get a list of detailed games
     *
     * @return array
     * @Rest\View(serializerGroups={"list_games_detail"})
     */
    public function getGamesDetailAction()
    {
        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository('TestBundle:Game')->findAll();
        if (empty($games)) {
            return [];
        }

        return $games;

    }

    /**
     * Get a game
     *
     * @Rest\View(templateVar="game")
     *
     * @param int  $id   The game id
     * @param Game $game The game object gotten via DoctrineParamConverter
     *
     * @return array
     *
     * @throws NotFoundHttpException when game does not exist
     */
    public function getGameAction($id, Game $game)
    {
        return $game;

    }

    /**
     * Handle the form data
     *
     * @param Request $request
     * @param Game $game
     * @param $status
     * @return View|Response
     */
    public function processForm(Request $request, Game $game, $status)
    {
        $form = $this->createForm(new GameType(), $game, ['method' => $request->getMethod()]);

        $jsonData = json_decode($request->getContent(), true);
        $form->submit($jsonData);

        if ($form->isValid()) {
            $data = $form->getData();
            $gameService = $this->get('game_service');
            $isNew = ($status === Codes::HTTP_CREATED) ? true : false;
            $gameService->saveGame($data, $isNew);

            $routeOptions = [
                'id' => $game->getId(),
                '_format' => $request->get('_format')
            ];
            return $this->routeRedirectView('api_get_game', $routeOptions, $status);
        }

        return $this->view(
            ['form' => $form],
            Codes::HTTP_BAD_REQUEST
        );
    }

    /**
     * Create a new game
     *
     * @param Request $request
     * @return View|Response
     */
    public function postGameAction(Request $request)
    {
        return $this->processForm($request, new Game(), Codes::HTTP_CREATED);

    }

    /**
     * Update an existing game or create a new one if the game isn't found
     *
     * @param int     $id
     * @param Game    $game
     * @param Request $request
     * @return View|Response
     */
    public function putGameAction($id, Game $game, Request $request)
    {
        return $this->processForm($request, $game, Codes::HTTP_NO_CONTENT);

    }

    /**
     * Delete a game
     *
     * @param int $id The Game id
     *
     * @Rest\View(statusCode=204)
     */
    public function deleteGameAction($id)
    {
        $gameService = $this->get('game_service');
        $removed = $gameService->removeGame($id);
        if ($removed === 0) {
            return new Response('Can\'t delete Game', Codes::HTTP_NOT_FOUND);
        }
    }
}
