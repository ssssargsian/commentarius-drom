<?php

declare(strict_types=1);

namespace App\Tests\Unit\Commentary;

use App\ExampleComClient\CommentClient;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CommentClientTest extends TestCase
{
    protected CommentClient $client;
    protected ClientInterface $clientMock;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(ClientInterface::class);

        $this->client = new CommentClient('http://example.com', $this->clientMock);
    }

    public function test_add_comment(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);

        $this->clientMock->expects($this->once())
            ->method(Request::METHOD_POST)
            ->with(
                'http://example.com/comment',
                ['json' => ['name' => 'John', 'text' => 'Test comment']]
            )
            ->willReturn($response);

        $response = $this->client->addComment('John', 'Test comment');

        $this->assertEquals(['status' => 'success'], $response);
    }

    public function test_update_comment(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);

        $this->clientMock->expects($this->once())
            ->method(Request::METHOD_PUT)
            ->with(
                'http://example.com/comment/1',
                ['json' => ['name' => 'John', 'text' => 'Updated comment']]
            )
            ->willReturn($response);

        $response = $this->client->updateComment(1, 'John', 'Updated comment');

        $this->assertEquals(['status' => 'success'], $response);
    }
}