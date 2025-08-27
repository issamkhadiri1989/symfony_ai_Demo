<?php

namespace App\Ai\Tool;

use App\Service\OutputSanitizer;
use Symfony\AI\Agent\Toolbox\Attribute\AsTool;

#[AsTool(
    name: 'formatter',
    description: 'Accepts a raw JSON string and returns a cleaned, schema-compliant version with keys: title, ingredients, instructions, source.'
)]class CustomTool
{
    public function __construct(
        private OutputSanitizer $sanitizer,)
    {
    }

    public function __invoke(string $data): string
    {
        return $this->sanitizer->sanitize($data);
    }
}
