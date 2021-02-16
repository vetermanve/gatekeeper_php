<?php


namespace App\WaitingList\Service;


use App\WaitingList\Storage\WaitingListStorage;
use Verse\Storage\SimpleStorage;

class WaitingListService
{
    private SimpleStorage $storage;

    public function getStorage() : SimpleStorage
    {
        if (!isset($this->storage)) {
            $this->storage = new WaitingListStorage();
        }

        return $this->storage;
    }
    
    
    public function add($userId, $chatId)
    {
        $time = time();
        $id = $chatId.'.'.$userId;

        return $this->getStorage()->write()->update($id, [
            WaitingListStorage::USER_ID => $userId,
            WaitingListStorage::CHAT_ID => $chatId,
            WaitingListStorage::TIME => $time,
        ], __METHOD__);
    }

    public function check($userId, $chatId)
    {
        
    }

    public function drop($userId, $chatId)
    {
        
    }

    public function listCurrent()
    {
        return $this->getStorage()->search()->find([], 100, __METHOD__);
    }
}