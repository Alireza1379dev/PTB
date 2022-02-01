<?php

require "TelegramBot/Api.php";

use TelegramBot\Api;

class Bot extends Api {
    public function __construct($token) {
        parent::__construct($token);
    }
    
    public function bot($update) {
        $from_id = $update->message->from->id;
        $text = $update->message->text;
        
        $this->sendMessage([
            "chat_id" => $from_id,
            "text" => $text
        ]);
    }
}

$bot = new bot("2084151219:AAGsJXRLaAK0nI6hYYzw3EBNPEKLVHX-54A");