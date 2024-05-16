<?php

declare(strict_types=1);

namespace App\ExampleComClient;

use ErrorException;
use GuzzleHttp\Client;
use JsonException;
use Psr\Http\Message\ResponseInterface;

final readonly class CommentClient
{
    public function __construct(
        private string $baseUrl = 'http://example.com',
        private Client $client = new Client(),
    ) {
    }

    public function getComments(): mixed
    {
        $response = $this->client->get($this->baseUrl . '/comments');

        $this->throwIfJsonError($response, $response->getBody()->getContents());

        return json_decode($response->getBody()->getContents(), true);
    }

    public function addComment(string $name, string $text): mixed
    {
        $response = $this->client->post($this->baseUrl . '/comment', [
            'json' => [
                'name' => $name,
                'text' => $text
            ]
        ]);

        $this->throwIfJsonError($response, $response->getBody()->getContents());

        return json_decode($response->getBody()->getContents(), true);
    }

    public function updateComment(int $id, string $name, string $text): mixed
    {
        $response = $this->client->put($this->baseUrl . '/comment/' . $id, [
            'json' => [
                'name' => $name,
                'text' => $text
            ]
        ]);

        $this->throwIfJsonError($response, $response->getBody()->getContents());

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws JsonException|ErrorException
     */
    private function throwIfJsonError(ResponseInterface $response, string $contents): void
    {
        $status = $response->getStatusCode();

        if ($status < 400) {
            return;
        }

        if (!str_contains($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new ErrorException(sprintf('HTTP Code %d: %s', $status, $contents));
        }

        try {
            /** @var array{error?: array{message: string|array<int, string>, type: string, code: string}} $response */
            $response = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);

            if (isset($response['error']['message'])) {
                throw new ErrorException($response['error']['message']);
            }
        } catch (JsonException $jsonException) {
            throw new JsonException($jsonException->getMessage());
        }
    }
}