<?php

declare(strict_types=1);

namespace App\Ai\Agent;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Agent\Exception\ExceptionInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\Target;

final readonly class Service
{
    public function __construct(
        #[Autowire(service: 'ai.agent.gemini')]
        private AgentInterface $agent,
    ) {
    }

    /**
     * @param string $prompt
     *
     * @return mixed
     *
     * @throws ExceptionInterface
     */
    public function send(string $prompt): mixed
    {
        $systemPrompt = <<<PROMPT
You are an AI agent with access to external tools.
When asked to find a recipe using ingredients, you must invoke the "serpapi" tool with a relevant query.
You are allowed to use the SerpApi tool to search the internet.
When ingredients are provided, use SerpApi to find a real recipe and return it in JSON format.
When given a list of ingredients, you must search the internet for a real recipe using those ingredients.
Use SerpApi to find relevant results and extract a recipe from a trusted source.
Respond only with a raw JSON object using these exact keys: "title", "ingredients", "instructions", "source".
Do not include any Markdown formatting, code blocks, backticks, or escaped characters. Do not hallucinate recipes.
Always base your response on real search results on internet.
You are allowed to use tools like SerpApi to search for recipes in the internet.
Get Recipes in english.
Your response will be parsed by a machine. It must be valid JSON, not a string or formatted text.
PROMPT;

        $systemPrompt = 'You are an AI agent with access to the SerpApi tool.

Your task is to find a cheap flight from Casablanca to Tokyo, departing next month. You must invoke the "serpapi" tool with a search query like: "cheap flight Casablanca to Tokyo September".

Do not respond directly. Wait for the tool result and format the final answer as raw JSON with keys: "origin", "destination", "departure", "price", "airline", "source".

Do not format as Markdown. Do not hallucinate. Always use the tool.
';

        $systemPrompt = 'You are an AI agent with access to external tools.
Your task is to retrieve the current weather forecast for Paris, France site:weather.com.
You must use an external tool to search for this information.
Do not guess or respond directly. Always rely on external data.
Once retrieved, respond with a raw JSON object using these keys: "location", "temperature", "condition", "humidity", "wind", "source".
';

        $messages = new MessageBag(
            Message::forSystem($systemPrompt),
            Message::ofUser($prompt)
        );

        $response = $this->agent->call($messages);

        $content = $response->getContent();

        return $content;
    }
}
