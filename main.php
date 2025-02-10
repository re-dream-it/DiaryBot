<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ADD EXTENSIONS
require_once __DIR__ . '/Models/Class_DiaryBot.php';
require_once __DIR__ . '/Models_DataBase/Class_DiaryBotDataBase.php';

$bot_token = '-';
$DB = new DiaryBotDataBase('localhost', '-', '-', '-');

$bot_id = "-";
$bot = new DiaryBot($bot_token);
$data = $bot->getUpdates();

// TELEGRAM WEBHOOKS
$user_name = $data['message']['chat']['username'] ?? '';
$messag = $data['message'] ?? '';
$message = $data['message']['text'] ?? '';
$messageid = $data['message']['message_id'] ?? '';
$chatid = $data['message']['chat']['id'] ?? '';
$callbackdata = $data['callback_query']['data'] ?? '';
$callbackid = $data['callback_query']['from']['id'] ?? '';
$cbmesageid = $data['callback_query']['message']['message_id'] ?? '';
$joinchatid = $data['chat_join_request']['chat']['id'] ?? '';
$joinid = $data['chat_join_request']['from']['id'] ?? '';
$forwardfromchatid = $data['message']['forward_from_chat']['id'] ?? '';
$forwardfromchatname = $data['message']['forward_from_chat']['title'] ?? '';
$forwardfromchattype = $data['message']['forward_from_chat']['type'] ?? '';
$channelsended = $data['message']['chat_shared']['request_id'] ?? '';
$channelsendedid = $data['message']['chat_shared']['chat_id'] ?? '';
$mesphoto = $data['message']['photo'] ?? '';
$date = $data['message']['date'] ?? '';

// GOOGLE SHEETS
require __DIR__ . '/vendor/autoload.php';

$client = new \Google_Client();
$client->setApplicationName('Google Sheets with Primo');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__ . '/credentials.json');

$service = new Google_Service_Sheets($client);
$spreadsheetId = "-";

function create($title)
    {   
        /* Load pre-authorized user credentials from the environment.
           TODO(developer) - See https://developers.google.com/identity for
            guides on implementing OAuth2 for your application. */
            $client = new \Google_Client();
            $client->setApplicationName('Google Sheets with Primo');
            $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
            $client->setAccessType('offline');
            $client->setAuthConfig(__DIR__ . '/credentials.json');
            
            $service = new Google_Service_Sheets($client);
        try{

            $spreadsheet = new Google_Service_Sheets_Spreadsheet([
                'properties' => [
                    'title' => $title
                    ]
                ]);
                $spreadsheet = $service->spreadsheets->create($spreadsheet, [
                    'fields' => 'spreadsheetId'
                ]);
                printf("Spreadsheet ID: %s\n", $spreadsheet->spreadsheetId);
                return $spreadsheet->spreadsheetId;
        }
        catch(Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' .$e->getMessage();
          }
    }


// COMMANDS
if ($message === '/start')
{   
    
    $DB->addUser($chatid, $user_name);
    $keyboard = [
        [ ['text' => '-12', 'callback_data' => 'timeshift:-12'], ['text' => '-11', 'callback_data' => 'timeshift:-11'], ['text' => '-10', 'callback_data' => 'timeshift:-10'], ['text' => '-9', 'callback_data' => 'timeshift:-9'], ['text' => '-8', 'callback_data' => 'timeshift:-8'], ['text' => '-7', 'callback_data' => 'timeshift:-7'], ['text' => '-6', 'callback_data' => 'timeshift:-6'], ['text' => '-5', 'callback_data' => 'timeshift:-5'],],
        [ ['text' => '-4', 'callback_data' => 'timeshift:-4'], ['text' => '-3', 'callback_data' => 'timeshift:-3'], ['text' => '-2', 'callback_data' => 'timeshift:-2'], ['text' => '-1', 'callback_data' => 'timeshift:-1'], ['text' => '0', 'callback_data' => 'timeshift:0'], ['text' => '+1', 'callback_data' => 'timeshift:+1'], ['text' => '+2', 'callback_data' => 'timeshift:+2'], ['text' => '+3', 'callback_data' => 'timeshift:+3'] ],
        [ ['text' => '+4', 'callback_data' => 'timeshift:+4'], ['text' => '+5', 'callback_data' => 'timeshift:+5'], ['text' => '+6', 'callback_data' => 'timeshift:+6'], ['text' => '+7', 'callback_data' => 'timeshift:+7'], ['text' => '+8', 'callback_data' => 'timeshift:+8'], ['text' => '+9', 'callback_data' => 'timeshift:+9'], ['text' => '+10', 'callback_data' => 'timeshift:+10'], ['text' => '+11', 'callback_data' => 'timeshift:+11'] ],
        [ ['text' => '+12', 'callback_data' => 'timeshift:+12'] ]
    ];
    $res = $bot->sendMessage($chatid, 'Привет! Сколько часов у вас разница с Москвой? (по умолчанию стоит 0)' . PHP_EOL . PHP_EOL . '[Ваша таблица](https://docs.google.com/spreadsheets/d/1JFLtDocRGPM62aWMWZBwwr0D5S-OuuPxRfdVGsNDEPg/edit#gid=0)', $keyboard, 'inline_keyboard');
    $file_put_contents('res.txt', print_r($res, 1), FILE_APPEND);

}

elseif ($message === '/timezone')
{   
    
    $DB->addUser($chatid, $user_name);
    $keyboard = [
        [ ['text' => '-12', 'callback_data' => 'timeshift:-12'], ['text' => '-11', 'callback_data' => 'timeshift:-11'], ['text' => '-10', 'callback_data' => 'timeshift:-10'], ['text' => '-9', 'callback_data' => 'timeshift:-9'], ['text' => '-8', 'callback_data' => 'timeshift:-8'], ['text' => '-7', 'callback_data' => 'timeshift:-7'], ['text' => '-6', 'callback_data' => 'timeshift:-6'], ['text' => '-5', 'callback_data' => 'timeshift:-5'],],
        [ ['text' => '-4', 'callback_data' => 'timeshift:-4'], ['text' => '-3', 'callback_data' => 'timeshift:-3'], ['text' => '-2', 'callback_data' => 'timeshift:-2'], ['text' => '-1', 'callback_data' => 'timeshift:-1'], ['text' => '0', 'callback_data' => 'timeshift:0'], ['text' => '+1', 'callback_data' => 'timeshift:+1'], ['text' => '+2', 'callback_data' => 'timeshift:+2'], ['text' => '+3', 'callback_data' => 'timeshift:+3'] ],
        [ ['text' => '+4', 'callback_data' => 'timeshift:+4'], ['text' => '+5', 'callback_data' => 'timeshift:+5'], ['text' => '+6', 'callback_data' => 'timeshift:+6'], ['text' => '+7', 'callback_data' => 'timeshift:+7'], ['text' => '+8', 'callback_data' => 'timeshift:+8'], ['text' => '+9', 'callback_data' => 'timeshift:+9'], ['text' => '+10', 'callback_data' => 'timeshift:+10'], ['text' => '+11', 'callback_data' => 'timeshift:+11'] ],
        [ ['text' => '+12', 'callback_data' => 'timeshift:+12'] ]
    ];
    $shift = $DB->getTimeShift($chatid);
    $res = $bot->sendMessage($chatid, 'Ваш текущий часовой пояс: ' . $shift . PHP_EOL . PHP_EOL . 'Выберите часовой пояс от -12 до +12 от МСК.', $keyboard, 'inline_keyboard');
    $file_put_contents('res.txt', print_r($res, 1), FILE_APPEND);

}

elseif ($message === '/table'){

    $bot->sendMessage($chatid, '[Ваша таблица](https://docs.google.com/spreadsheets/d/1JFLtDocRGPM62aWMWZBwwr0D5S-OuuPxRfdVGsNDEPg/edit#gid=0)');

}
elseif ($message)
{   
    if($DB->getTable($chatid) == '0'){
        $tabletext = '[Ваша таблица](https://docs.google.com/spreadsheets/d/1JFLtDocRGPM62aWMWZBwwr0D5S-OuuPxRfdVGsNDEPg/edit#gid=0)';
        $DB->setTable($chatid, '1');
    }
    $bot->sendMessage($chatid, '✏ сохраняю запись: ' . $message);
    $shift = (int) $DB->getTimeShift($chatid);

    if($shift >= 0 && $shift < 10){
        $shift = -$shift -3;
        date_default_timezone_set("Etc/GMT{$shift}");
        $day = date("d", $date);
    }
    elseif($shift < 0){
        $shift = -$shift - 3;
        date_default_timezone_set("Etc/GMT{$shift}");
        $day = date("d", $date);
    }
    elseif($shift >= 10){
        if ($shift === 10){
            $shift = 11;
            $day = date("d", $date) + 1;
        }
        elseif ($shift === 11){
            $shift = 10;
            $day = date("d", $date) + 1;
        }
        elseif ($shift === 12){
            $shift = 9;
            $day = date("d", $date) + 1;
        }
    }
    $shift = (string) $shift;
    if(strpos($shift, '-') === false && $shift !== '0'){
        $shift = '+' . $shift;
    }
    $month = (int) date("m", $date);
    if($month === 2 && $day > 28){
        $day = 1;
        $month = $month + 1;

    }
    elseif($month % 2 === 0 && $month != 12 && $day>30){
        $day = 1;
        $month = $month + 1;
    }
    elseif($month % 2 === 1 && $day>31){
        $day = 1;
        $month = $month + 1;
    }
    $day = (string) $day;
    $month = (string) $month;
    if(strlen($day) === 1){
        $day = '0' . $day;
    }
    if(strlen($month) === 1){
        $month = '0' . $month;
    }

    date_default_timezone_set("Etc/GMT{$shift}");
    
    $new_date = date("Y H:i:s", $date);





    $values = [
        [$user_name, $message, $day . '.' . $month . '.' . $new_date]
    ];
    //echo "<pre>";print_r($values);echo "</pre>";exit;
    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);
    $params = [
        'valueInputOption' => 'RAW'
    ];
    
    $result = $service->spreadsheets_values->append(
        $spreadsheetId,
        $range,
        $body,
        $params
    );
    
    if($result->updates->updatedRows == 1){
        $bot->sendMessage($chatid, '✅ сохранил' . PHP_EOL . PHP_EOL . $tabletext);
    } else {
        $bot->sendMessage($chatid, 'Ошибка добавления!');
    }
}
elseif (strpos($callbackdata, 'timeshift:') !== false )
{
    $timeshift = trim(strstr($callbackdata, ':'), ':');
    $bot->editMessage($callbackid, $cbmesageid, 'Вы выбрали ' . $timeshift . ' от МСК. Для создания записи отправьте любое сообщение.');
    $DB->setTimeShift($callbackid, $timeshift);
}

