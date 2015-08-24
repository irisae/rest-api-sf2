<?php

namespace TestBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use TestBundle\DataFixtures\ORM\LoadGameData;

class RestControllerTest extends WebTestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = static::createClient();
        $loadGameData = new LoadGameData();
        $loadGameData->load($this->client->getContainer()->get('doctrine.orm.entity_manager'));
    }

    protected function tearDown()
    {
        LoadGameData::$games = [];
        $this->client->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('TestBundle:Game')
            ->clearAllGames();
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    public function testAllAction()
    {
        $games = LoadGameData::$games;
        $route =  $this->getUrl('api_get_games', ['_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $content = $response->getContent();

        $arrayGames = json_decode($content, true);
        $firstGame = reset($games);
        $lastGame = end($games);

        $this->assertJsonResponse($response, 200);
        $this->assertEquals(count($games), count($arrayGames));
        $this->assertEquals($firstGame->getId(), $arrayGames[0]['id']);
        $this->assertEquals($firstGame->getName(), $arrayGames[0]['name']);
        $this->assertEquals($firstGame->getNbPlayer(), $arrayGames[0]['nb_player']);
        $this->assertFalse(array_key_exists('platform', $arrayGames[0]));
        $this->assertEquals($lastGame->getId(), $arrayGames[2]['id']);
        $this->assertEquals($lastGame->getName(), $arrayGames[2]['name']);
        $this->assertEquals($lastGame->getNbPlayer(), $arrayGames[2]['nb_player']);
        $this->assertFalse(array_key_exists('platform', $arrayGames[2]));
    }

    public function testAllActionReturnsEmptyList()
    {
        $this->tearDown();
        $route =  $this->getUrl('api_get_games', ['_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $content = $response->getContent();

        $arrayGames = json_decode($content, true);

        $this->assertJsonResponse($response, 200);
        $this->assertEmpty($arrayGames);
    }

    public function testGetGamesDetailAction()
    {
        $games = LoadGameData::$games;
        $route =  $this->getUrl('api_get_games_detail', ['_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $content = $response->getContent();

        $arrayGames = json_decode($content, true);
        $firstGame = reset($games);
        $lastGame = end($games);

        $this->assertJsonResponse($response, 200);
        $this->assertEquals(count($games), count($arrayGames));
        $this->assertEquals($firstGame->getId(), $arrayGames[0]['id']);
        $this->assertEquals($firstGame->getName(), $arrayGames[0]['name']);
        $this->assertEquals($firstGame->getNbPlayer(), $arrayGames[0]['nb_player']);
        $this->assertEquals($firstGame->getPlatform(), $arrayGames[0]['platform']);
        $this->assertTrue(array_key_exists('launch_date', $arrayGames[0]));
        $this->assertEquals($lastGame->getId(), $arrayGames[2]['id']);
        $this->assertEquals($lastGame->getName(), $arrayGames[2]['name']);
        $this->assertEquals($lastGame->getNbPlayer(), $arrayGames[2]['nb_player']);
        $this->assertEquals($lastGame->getPlatform(), $arrayGames[2]['platform']);
        $this->assertTrue(array_key_exists('launch_date', $arrayGames[2]));
    }

    public function testGetAction()
    {
        $games = LoadGameData::$games;
        $game = end($games);
        $route =  $this->getUrl('api_get_game', ['id' => $game->getId(), '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $content = $response->getContent();

        $gameResult = json_decode($content, true);

        $this->assertJsonResponse($response, 200);
        $this->assertEquals($game->getId(), $gameResult['id']);
        $this->assertEquals($game->getName(), $gameResult['name']);
        $this->assertEquals($game->getNbPlayer(), $gameResult['nb_player']);
    }

    public function testGetActionThrows404()
    {
        $route =  $this->getUrl('api_get_game', ['id' => 999999999, '_format' => 'json']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
    }

    public function testPostPageAction()
    {
        $this->client->request(
            'POST',
            '/api/games.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'TOTO FAIT DU VELO',
                'nbPlayer' => 2,
                'platform' => 'PC',
                'launchDate' => '2012-12-05'
            ])
        );

        $this->assertJsonResponse($this->client->getResponse(), 201);
    }

    public function testPostActionReturns400WithBadParameters()
    {
        $this->client->request(
            'POST',
            '/api/games.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'TOTO FAIT DU VELO',
                'nbPlayer' => 2,
                'platform' => 'PC',
                'launchDate' => '5-12-05'
            ])
        );
        $this->assertJsonResponse($this->client->getResponse(), 400);
    }

    public function testPutActionShouldUpdateGame()
    {
        $games = LoadGameData::$games;
        $game = end($games);
        $route =  $this->getUrl('api_put_game', ['id' => $game->getId(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Super Smash Bros',
                'nbPlayer' => 4,
                'platform' => 'Gamecube',
                'launchDate' => '2010-11-07'
            ])
        );
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 204);
        $routeGet = $this->getUrl('api_get_game', ['id' => $game->getId(), '_format' => 'json']);
        $this->assertContains(
            $routeGet,
            $response->headers->get('Location')
        );
    }

    public function testPutActionThrows404()
    {
        $route =  $this->getUrl('api_put_game', ['id' => 999999999, '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Super Smash Bros',
                'nbPlayer' => 4,
                'platform' => 'Gamecube',
                'launchDate' => '2010-11-07'
            ])
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
    }

    public function testDeleteActionThrows404()
    {
        $route =  $this->getUrl('api_delete_game', ['id' => 999999999, '_format' => 'json']);
        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
    }

    public function testDeleteAction()
    {
        $games = LoadGameData::$games;
        $game = end($games);
        $route =  $this->getUrl('api_delete_game', ['id' => $game->getId(), '_format' => 'json']);
        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 204);
    }
}