<?php

namespace App\Service;

class OutputSanitizer
{
    public function sanitize(string $raw): array
    {
        if (preg_match('/```json\\n(.*?)\\n```/s', $raw, $matches)) {
            $json = json_decode($matches[1], true);
            return $json ?? ['error' => 'Invalid JSON'];
        }

        return ['error' => 'No JSON block found'];
    }

}

