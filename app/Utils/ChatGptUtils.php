<?php

namespace App\Utils;

class ChatGptUtils
{
    private string $urlApi;
    private string $serectKey;

    public function __construct()
    {
        $this->urlApi = env('OPENAI_API_URL');
        $this->serectKey = env('OPENAI_API_KEY');
    }


    public function sendMessageUsingChat(string $message) : mixed
    {
        $endpoint = $this->urlApi ? "{$this->urlApi}/chat/completions" : null;

        if(! $endpoint) {
            info("URL API OpenAPI: {$this->urlApi}");
            return false;
        }

        $data = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'developer',
                    'content' => 'You are a helpful assistant.'
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ]
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->serectKey
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            info('Curl error: ' . curl_error($ch));
            return false;
        } 

        curl_close($ch);

        return json_decode($response);
    }
}
