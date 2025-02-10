<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'Class_DataBase.php';

class DiaryBotDataBase extends TelegramBotDataBase
{
    
    public function addUser($user_id, $user_name)
    {
        $checkstatement = $this->pdo->prepare("SELECT id FROM `MEHANIKBOT_users` WHERE id = ?");
        $checkstatement->execute([$user_id]);
        $data = $checkstatement->fetch();
        // array(1) { ["id"]=> string(1) "1" } OR bool(false)
        if($data === false)
        {
            $statement = $this->pdo->prepare("INSERT INTO `MEHANIKBOT_users` (`id`, `name`, `google_table`, `status`, `timeshift`) VALUES (:id, :username, '0', '0', '0');");
            if($statement->execute(['id' => $user_id, 'username' => $user_name]))
            {
                return true;
            }
            else
            {
                file_put_contents(__DIR__ . '/dbErrors.txt', print_r($statement, 1), FILE_APPEND);
                return false;
            }
        }
        return 'Уже добавлен';
    }


    public function setStatus($user_id, $status)
    {
        $statement = $this->pdo->prepare("UPDATE MEHANIKBOT_users SET status = ? WHERE id = ?");
        $statement->execute([$status, $user_id]);
    }

    public function getStatus($userid){

        $statement = $this->pdo->prepare("SELECT status FROM `MEHANIKBOT_users` WHERE id = ?");
        $statement->execute([$userid]);
        $data = $statement->fetch();
        return $data['status'];

    }

    public function setTimeShift($user_id, $timeshift)
    {
        $statement = $this->pdo->prepare("UPDATE MEHANIKBOT_users SET timeshift = ? WHERE id = ?");
        $statement->execute([$timeshift, $user_id]);
    }

    public function getTimeShift($userid){

        $statement = $this->pdo->prepare("SELECT timeshift FROM `MEHANIKBOT_users` WHERE id = ?");
        $statement->execute([$userid]);
        $data = $statement->fetch();
        return $data['timeshift'];

    }

    public function setTable($user_id, $table)
    {
        $statement = $this->pdo->prepare("UPDATE MEHANIKBOT_users SET google_table = ? WHERE id = ?");
        $statement->execute([$table, $user_id]);
    }

    public function getTable($userid){

        $statement = $this->pdo->prepare("SELECT google_table FROM `MEHANIKBOT_users` WHERE id = ?");
        $statement->execute([$userid]);
        $data = $statement->fetch();
        return $data['google_table'];

    }

    
}


