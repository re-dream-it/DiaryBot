<?php

require_once 'Class_TelegramBot.php';

class DiaryBot extends TelegramBot
{

    public function approveRequest($chat_id, $user_id){      
        $send_data = [ 
            'chat_id' => $chat_id,
            'user_id' => (int) $user_id,  
        ];     
               
        return $this->sendQueryTelegram("approveChatJoinRequest", $send_data);      
    }

    public function getChatName($chat_id){      
        $send_data = [
            'chat_id' => $chat_id, 
        ];     
        $data = $this->sendQueryTelegram("getChat", $send_data);   
        return json_decode($data);
    }


    public function copyMessage($chat_id, $from_chat, $message_id){      
        $send_data = [
            'chat_id' => $chat_id,
            'from_chat_id' => $from_chat,
            'message_id' => $message_id
        ];     
        $data = $this->sendQueryTelegram("copyMessage", $send_data);   
        return $data;
    }
    public function forwardMessage($chat_id, $from_chat, $message_id){      
        $send_data = [
            'chat_id' => $chat_id,
            'from_chat_id' => $from_chat,
            'message_id' => $message_id
        ];     
        $data = $this->sendQueryTelegram("forwardMessage", $send_data);   
        return $data;
    }

    public function getFile($file_id){      
        $send_data = [
            'file_id' => $file_id,
        ];     
        $data = $this->sendQueryTelegram("getFile", $send_data);   
        return $data;
    }

    
    
    
}

