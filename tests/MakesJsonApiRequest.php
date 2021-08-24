<?php

namespace Tests;

use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert;
use Illuminate\Support\Str;
use PHPUnit\Framework\ExpectationFailedException;

trait MakesJsonApiRequest
{
    protected bool $formatJsonApiDocument = true;

    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro('assertJsonApiValidationErrors', function ($attribute) {
            /** @var TestResponse $this */

            $pointer = Str::of($attribute)->startsWith('data')
                ? "/" . str_replace('.', '/', $attribute)
                : "/data/attributes/{$attribute}";

            try {
                $this->assertJsonFragment([
                    'source' => ['pointer' => $pointer]
                ]);
            } catch (ExpectationFailedException $e) {
                Assert::fail(
                    "Failed to find a JSON:API validation error for key: '{$attribute}'"
                        . PHP_EOL . PHP_EOL .
                        $e->getMessage()
                );
            }
            try {
                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail', 'source' => ['pointer']]
                    ]
                ]);
            } catch (ExpectationFailedException $e) {
                Assert::fail(
                    "Failed to find a valid JSON:API error response: '{$attribute}'"
                        . PHP_EOL . PHP_EOL .
                        $e->getMessage()
                );
            }
            $this->assertHeader(
                'content-type',
                'application/vnd.api+json'
            )->assertStatus(422);
        });
    }

    public function withoutJsonApiDocumentFormatting()
    {
        $this->formatJsonApiDocument = false;
    }

    public function json($method, $uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['accept'] = 'application/vnd.api+json';

        if ($this->formatJsonApiDocument) {
            $formattedData['data']['attributes'] = $data;
            $formattedData['data']['type'] = (string) Str::of($uri)->after('api/v1');
        }

        return parent::json($method, $uri, $formattedData ?? $data, $headers);
    }

    public function postJson($uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::postJson($uri, $data, $headers);
    }

    public function patchJson($uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::patchJson($uri, $data, $headers);
    }
}
