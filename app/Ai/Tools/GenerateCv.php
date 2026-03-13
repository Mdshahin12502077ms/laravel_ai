<?php

namespace App\Ai\Tools;

use App\Models\Resume;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Auth;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GenerateCv implements Tool
{
    public function description(): Stringable|string
    {
        return "Generate a CV using user details such as name, email, phone, summary, experience, education and skills. Call this only when you have collected enough information.";
    }

    public function handle(Request $request): Stringable|string
    {
        $resume = Resume::create([
            'user_id' => Auth::id(),
            'data' => [
                'fullName' => $request['fullName'],
                'email' => $request['email'],
                'phone' => $request['phone'] ?? null,
                'summary' => $request['summary'] ?? null,
                'experience' => $request['experience'] ?? [],
                'education' => $request['education'] ?? [],
                'skills' => $request['skills'] ?? [],
            ]
        ]);

        return "I have successfully generated your CV! You can view it here: /cv/" . $resume->id;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'fullName' => $schema->string()->required(),
            'email' => $schema->string()->required(),
            'phone' => $schema->string(),
            'summary' => $schema->string(),
            'experience' => $schema->array()->items(
                $schema->object([
                    'jobTitle' => $schema->string(),
                    'company' => $schema->string(),
                    'dates' => $schema->string(),
                ])
            ),
            'education' => $schema->array()->items(
                $schema->object([
                    'degree' => $schema->string(),
                    'institution' => $schema->string(),
                    'year' => $schema->string(),
                ])
            ),
            'skills' => $schema->array()->items(
                $schema->string()
            ),
        ];
    }
}
