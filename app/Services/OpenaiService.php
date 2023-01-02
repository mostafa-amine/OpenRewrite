<?php


namespace App\Services;

use OpenAI;


class OpenaiService
{
    /**
     * @param mixed $apiToken
     * @param mixed $userText
     *
     * @return [string]
     */
    public static function generate($apiToken , $userText)
    {
        $client = OpenAI::client($apiToken);

        $result = $client->completions()->create([
            "model" => "text-davinci-003",
            "temperature" => 0.7,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            'max_tokens' => 600,
            'prompt' => sprintf($userText),
        ]);

        $content = trim($result['choices'][0]['text']);

        return $content;
    }
}
