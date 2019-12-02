<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bartosz.
 */

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

abstract class RestApiTestCase extends PantherTestCase
{
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'user', $password = 'zaq12wsx')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    /**
     * @return mixed
     */
    protected function jsonDecode(Response $response)
    {
        return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}
