<?php

namespace App\Controller;

use App\Ai\Agent\Sanitizier;
use App\Ai\Agent\Service;
use App\Ai\Prompt\PromptBuilder;
use App\Service\OutputSanitizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AiController extends AbstractController
{
    #[Route('/ai', name: 'app_ai')]
    public function index(
        Service $agent,
        PromptBuilder $promptBuilder,
        Request $request,
        Sanitizier $sanitizer,
    ): JsonResponse
    {
        $prompt = $promptBuilder->buildFromRequest($request);

        $output = $agent->send($prompt);
        //$sanitized = $sanitizer->sanitize($rawRecipe);

        // Supprime les backticks et le bloc Markdown
        $output = preg_replace('/^```json\\n|\\n```$/', '', $output);
        $output = json_decode($output, true);

        return $this->json(['data' => $output]);
    }
}
