<?php

namespace App\Ai\Agents;

use App\Ai\Tools\GenerateCv;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;
use Laravel\Ai\Concerns\RemembersConversations;

class CvAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are an expert CV and Resume writing assistant. Your goal is to interview the user and gather all necessary information to build a professional CV. Get their full name, email, phone number, a professional summary, work experience (job title, company, dates, bullet points), education, and skills. Ask them questions one at a time if they do not provide all the information upfront. Once you have enough information, use the `generate_cv` tool to create the CV. Be polite, concise, and guiding.';
    }

    /**
     * Get the tools available to the agent.
     */
    public function tools(): array
    {
        return [
            new GenerateCv(),
        ];
    }
}
