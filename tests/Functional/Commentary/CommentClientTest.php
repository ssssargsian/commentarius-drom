<?php

declare(strict_types=1);

namespace App\Tests\Functional\Commentary;

use App\ExampleComClient\CommentClient;
use PHPUnit\Framework\TestCase;

final class CommentClientTest extends TestCase
{
    public function test_get_comments(): void
    {
        $client = new CommentClient('http://example.com');
        $comments = $client->getComments();

        $this->assertIsArray($comments);
    }

    public function test_add_comment(): void
    {
        $client = new CommentClient('http://example.com');
        $response = $client->addComment('John', 'Test comment');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
    }

    public function test_update_comment(): void
    {
        $client = new CommentClient('http://example.com');

        $response = $client->updateComment(1, 'Updated Name', 'Updated Text');

        $this->assertIsArray($response);
        $this->assertEquals('Updated Name', $response['name']);
        $this->assertEquals('Updated Text', $response['text']);
    }
}