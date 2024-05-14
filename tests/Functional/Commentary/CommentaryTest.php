<?php

namespace App\Tests\Functional\Commentary;

use App\Entity\Commentary;
use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentaryTest extends WebTestCase
{
    public function test_get_collection(): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, '/commentaries');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function test_create_commentary():void
    {
        $client = self::createClient();
        $client->request(
            method: Request::METHOD_POST,
            uri: '/commentaries',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['name' => 'Test Commentary', 'text' => 'Test Text'])
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $commentaryRepository = $em->getRepository(Commentary::class);

        $commentary = $commentaryRepository->findOneBy(['name' => 'Test Commentary']);
        $this->assertNotNull($commentary);
        $this->assertEquals('Test Commentary', $commentary->getName());
        $this->assertEquals('Test Text', $commentary->getText());
    }
}