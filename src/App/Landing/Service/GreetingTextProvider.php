<?php


namespace App\Landing\Service;


use Psr\Log\LoggerInterface;
use Verse\Di\Env;

class GreetingTextProvider
{
    public function getText($chatId, $newMembers) {
        $names = [];

        $logger  = Env::getContainer()->bootstrap(LoggerInterface::class);
        /* @var LoggerInterface $logger */
        $logger->debug(__METHOD__, ['members' => $newMembers,]);

        foreach ($newMembers as $newMember) {
            $names[] = $newMember['username'] ? '@'.$newMember['username'] : $newMember['first_name'];
        }

        $namesString = implode(', ', $names);

        $text = 'Мы рады видеть Вас, '.$namesString.', в этом чате.
Пожалуйста, представьтесь и поздоровайтесь с сообществом.
Это сообщество очень трепетно относится к составу и вежливости участников.
В случае, если я не увижу приветствия с вашей стороны, я буду вынужден удалить вас из чата через 30 минут.

Любые ссылки в приветственном сообщении будут расценены как спам и неуважение к сообществу.

Спасибо, что присоединились!';

        return $text;
    }
}