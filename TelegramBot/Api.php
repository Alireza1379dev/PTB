<?php

namespace TelegramBot;

class Api {
    
    private $token;
    
    private $settings;
    
    private $updates;
    
    private $methods = [
        "sendMessage",
        "deleteMessage",
        "copyMessage",
        "editMessageText",
        "editMessageReplyMarkup",
        "sendPhoto",
        "sendAudio",
        "sendDocument",
        "sendSticker",
        "sendVideo",
        "sendVoice",
        "sendLocation",
        "leaveChat",
        "forwardMessage",
        "editMessageLiveLocation",
        "stopMessageLiveLocation",
        "setChatStickerSet",
        "deleteChatStickerSet",
        "sendMediaGroup",
        "sendContact",
        "answerCallbackQuery",
        "answerInlineQuery",
        "banChatSenderChat",
        "unbanChatSenderChat",
        "banChatMember",
        "unbanChatMember",
        "unbanChatMember",
        "sendChatAction",
        "getUserProfilePhotos",
        "getChat",
        "getChatAdministrators",
        "getChatMember",
        "answerInlineQuery",
        "setGameScore",
        "answerCallbackQuery",
        "editMessageCaption"

    ];
    
    public function __construct($token, array $settings = []) {
        $this->token = $token;
        $this->settings = $settings;
        
        if ($this->settings["updates"] == "getupdate") {
            $this->updates = $this->getUpdates();
        } elseif ($this->settings["updates"] == "webhook") {
            $this->updates = json_decode(file_get_contents("php://input"));
        }
    }
    
    public function __call($method, $args) {
        if (in_array($method, $this->methods)) {
            $result = $this->request($method, $args[0]);
            return $result;
        }
    }
    
    protected function bot($update) {
        
    }
    
    public function getUpdates() {
        $offset = 0;
        while (true) {
            $updates = $this->request("getUpdates", [
                "offset" => $offset,
                "limit" => 100
            ]);
            if (isset($updates->result) && !empty($updates->result)) {
                foreach ($updates->result as $update) {
                    call_user_func([$this, "bot"], $this->updates);
                }
                $offset = $update->update_id + 1;
            }
        }
    }
    
    public function request($method, $params = []) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.telegram.org/bot".$this->token."/".$method,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }
}