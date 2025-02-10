<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class TelegramBot
{
    /* PARAMS */ 
    protected $token = "";
    private $chatLogQuery = 623574456;
    private $logFilePath = __DIR__ . "/file_log.txt";

    public function __construct($token) 
    {
	    $this->token = $token;
    }

    /* ЛОГИРОВАНИЕ ДАННЫХ И ОТПРАВКА В TELEGRAM */
    public function sendLogTelegram($logData, $reasonLog = false)
    {
	    file_put_contents($this->logFilePath, $logData);
	    $textMessage = date("d.m.Y H:i") . " Логирование данных";
        if($reasonLog) 
        {
	        $textMessage .= "\n" . "Причина: " . $reasonLog;
	    }

	    $arrQuery = [
	        "chat_id" 	=> $this->chatLogQuery,
	        "caption" 	=> $textMessage,
	        "document" 	=> new CURLFile($this->logFilePath)
	    ];

	    $this->sendQueryTelegram("sendDocument", $arrQuery);
	    unlink($this->logFilePath);
    }

    /* ================================== */

    /* ПАРСЕР ДЛЯ ОТПРАВКИ ЗАПРОСОВ */
    public function sendQueryTelegram($method, $arrayQuery = '') 
    {
	    $ch = curl_init("https://api.telegram.org/bot{$this->token}/{$method}");
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $result = curl_exec($ch);
	    curl_close($ch);
        
	    if(!isset($result)){
	        throw new Exception(curl_error($ch));
	    }
    
	    if(isset($result["ok"]) && $result["ok"] == false) 
        {
        
	        $arrResult = json_decode($result, true);
	        $arrDataLog = [
	            "method" => $method,
	    	    "arrayQuery" => $arrayQuery,
	    	    "arrResult" => $arrResult
	        ];
        
	        $arrDataLogJSON = json_encode($arrDataLog);
	        throw new Exception($arrDataLogJSON);
	    }
    
	    return $result;
    }     

    /* ================================== */

    /* ПОЛУЧАЕМ ВЕБХУКИ */
    public function getUpdates()
    {
        $update = json_decode(file_get_contents('php://input'), TRUE);
        return $update;
    }
    /* ОТПРАВКА КЛАВИАТУРЫ */
    public function replyKeyboardMarkup(array $params)
    {
        return json_encode($params);
    } 

	public function sendMessage($chat_id, $text, $keyboard=null, $reply_markup=null)
    {   
        $send_data = [
            'chat_id' => $chat_id,
            'text' => $text,  
            'parse_mode' => 'markdown',
            'reply_markup' => $this->replyKeyboardMarkup([
                    $reply_markup => $keyboard,
                    'resize_keyboard' => true,
                    'remove_keyboard' => true
               ])
            
       ];     
               
        return $this->sendQueryTelegram("sendMessage", $send_data);      
    }

    public function deleteMessage($chat_id, $message_id)
    {   
        $send_data = [
            'chat_id' => $chat_id,
            'message_id' => $message_id,  
        ];
               
        return $this->sendQueryTelegram("deleteMessage", $send_data);      
    }

    public function sendPhoto($chat_id, $photopath, $filename, $caption=null, $keyboard=null, $reply_markup=null)
    {
        $send_data = [
            'chat_id' => $chat_id,
            'photo' => curl_file_create($photopath, 'image/jpg' , $filename),
            'caption' => $caption,
            'reply_markup' => $this->replyKeyboardMarkup([
                $reply_markup => $keyboard,
                'resize_keyboard' => true,
                'remove_keyboard' => true
           ])
        ];

        return $this->sendQueryTelegram("sendPhoto", $send_data);  
    }

    

    public function sendPhotoFromServer($chat_id, $photopath, $caption=null)
    {
        $send_data = array(
            'chat_id' => $chat_id,
            'caption' => $caption,
            'parse_mode' => 'markdown',
            'photo' => curl_file_create($photopath, 'image/jpg' , 'qr.png')
        );	

        return $this->sendQueryTelegram("sendPhoto", $send_data);  
    }

    public function editMessage($chat_id, $message_id, $text, $keyboard=null, $reply_markup=null)
	{
        $send_data = [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'reply_markup' => $this->replyKeyboardMarkup([
                $reply_markup => $keyboard,
                'resize_keyboard' => true
           ])
        ];
        return $this->sendQueryTelegram("editMessageText", $send_data);
    }
    

    public function getMe($bot_token)
	{
        $ch = curl_init("https://api.telegram.org/bot{$bot_token}/getMe");
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $result = curl_exec($ch);
	    curl_close($ch);
        
        return $result;
        
    }



}   