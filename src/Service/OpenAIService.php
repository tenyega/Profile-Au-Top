<?php

namespace App\Service;

use Exception;
use OpenAI\Client;

class OpenAIService
{
    private $openai;

    public function __construct(Client $openai)
    {
        $this->openai = $openai;
    }

    public function generateText(string $prompt, array $options = []): string
{
    try {
        $defaultOptions = [
            'model' => 'gpt-3.5-turbo-instruct',
            'max_tokens' => 500,
            'temperature' => 0.7,
            'top_p' => 1.0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ];

        $params = array_merge($defaultOptions, $options, ['prompt' => $prompt]);

        $result = $this->openai->completions()->create($params);

        if (isset($result['choices'][0]['text'])) {
            return trim($result['choices'][0]['text']);
        } else {
            throw new \RuntimeException('No text was generated');
        }
    } catch (Exception $e) {
        throw new \RuntimeException('Error generating text: ' . $e->getMessage());
    }
}
}
