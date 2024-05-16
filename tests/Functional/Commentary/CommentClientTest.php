<?php

declare(strict_types=1);

namespace App\Tests\Functional\Commentary;

use App\ExampleComClient\CommentClient;
use PHPUnit\Framework\TestCase;

final class CommentClientTest extends TestCase
{
    protected CommentClient $client;

    protected function setUp(): void
    {
        $this->client = new CommentClient();
    }

    public function test_get_comments(): void
    {
        $comments = $this->client->getComments();

        $this->assertIsArray($comments);
        $this->assertNotEmpty($comments);
    }

    public function test_add_comment(): void
    {
        $response = $this->client->addComment('John', 'Test comment');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
    }

    public function test_update_comment(): void
    {
        $response = $this->client->updateComment(1, 'Updated Name', 'Updated Text');

        $this->assertIsArray($response);
        $this->assertEquals('Updated Name', $response['name']);
        $this->assertEquals('Updated Text', $response['text']);
    }
}