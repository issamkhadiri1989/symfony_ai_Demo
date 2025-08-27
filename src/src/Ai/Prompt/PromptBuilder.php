<?php

namespace App\Ai\Prompt;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PromptBuilder
{
    public function buildFromRequest(Request $request): string
    {
        // ... extract parameters from the request (e.g. $request->getPayload())

        $json = <<<JSON
{
    "ingredients": [
        "3 potetos",
        "1 onion",
        "2 tomatos",
        "1 cucomber"
    ]
}
JSON;

        $userInput = \json_decode($json);


        $prompt = sprintf(
        'You are an AI agent with access to the SerpApi tool. I have the following ingredients: %s. You must use SerpApi to search the internet for a real recipe using these ingredients. Return the result as raw JSON with keys: "title", "ingredients", "instructions", "source". Do not invent or hallucinate. Do not format as Markdown.',
        implode(', ', $userInput->ingredients),
    );

        $prompt = sprintf(
        'You are an AI agent with access to the SerpApi tool. When given ingredients like: %s, you must invoke the SerpApi tool to search for a real recipe. Do not respond directly. Do not guess. Always use the tool. Return the result as raw JSON with keys: "title", "ingredients", "instructions", "source".',
        implode(', ', $userInput->ingredients),
    );



        return $prompt;
    }
}
