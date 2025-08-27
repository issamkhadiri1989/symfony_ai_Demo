<?php

namespace App\Ai\Agent;

use App\Service\OutputSanitizer;
use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class Sanitizier
{
    public function __construct(
        #[Autowire(service: 'ai.agent.gemini')]
        private AgentInterface $agent,
    ) {
    }

    public function sanitize(string $rawJson): mixed
    {
        $systemPrompt = <<<SYS
            You are a backend API.
            You can use tools like "formatter" to clean up messy JSON.
            The formatter tool accepts a single argument "raw" and returns cleaned JSON with keys: title, ingredients, instructions, source.
            Respond with raw JSON only, no markdown or explanation.
SYS;

        $messages = new MessageBag(
            Message::forSystem($systemPrompt),
            Message::ofUser('Call the "formatter" tool with this input: ' . $rawJson),
        );

        $response = $this->agent->call($messages);

        return $response->getContent();
    }
}
