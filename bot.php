<?php

require "TelegramBot/Api.php";

use TelegramBot\Api;

class Bot extends Api {
    public function __construct($token, array $settings = []) {
        parent::__construct($token, $settings);
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

$settings["updates"] = "getupdate";

$bot = new bot("2084151219:AAEhJBWvcxz7W-ax61oW8R2JKLqGLtniWNQ", $settings);