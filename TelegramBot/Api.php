<?php

namespace TelegramBot;

class Api {
    
    private $token;
    
    private $methods = [
        "sendMessage",
        "deleteMessage",
        "copyMessage",
        "editMessageText",
        "sendPhoto",
    ];
    
    public function __construct($token) {
        $this->token = $token;
        $this->getUpdates();
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
                    call_user_func([$this, "bot"], $update);
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