<?php


namespace App\Telegram\Run\Provider;

use App\Telegram\Run\Spec\MessageType;
use App\Telegram\Run\Storage\PullUpdatesStorage;
use App\Telegram\Service\TelegramUpdatePull;
use Telegram\Bot\Api;
use Verse\Run\Provider\RequestProviderProto;
use Verse\Run\RunRequest;

class TelegramGetUpdatesProvider extends RequestProviderProto
{

    private TelegramUpdatePull $puller;

    private $alreadyReadUpdates = [];

    private $lastUpdateId = 0;

    private PullUpdatesStorage $updateTrackerStorage;

    public function prepare()
    {
        $this->puller = new TelegramUpdatePull();
        $this->updateTrackerStorage = new PullUpdatesStorage();
    }

    public function run()
    {
        $lastUpdateInfo = $this->updateTrackerStorage->read()->get('last_update', __METHOD__,0);

        $this->lastUpdateId = (double)$lastUpdateInfo['offset'] ?? 0;
        $this->runtime->debug('TELEGRAM_PULL_START', ['offsetId' => $this->lastUpdateId]);

        while (true) {
            $updates = $this->puller->get($this->lastUpdateId);
            $this->runtime->debug('TELEGRAM_PULL_UPDATES', ['count' => count($updates), 'offset' => $this->lastUpdateId]);

            foreach ($updates as $index => $update) {
                $updateId = $update->updateId;

                $this->lastUpdateId = $updateId;
                if ($this->lastUpdateId > 0) {
                    $this->updateTrackerStorage->write()->update('last_update', ['offset' => $this->lastUpdateId], __METHOD__);
                }

                if (isset($this->alreadyReadUpdates[$updateId])) {
                    continue;
                }

                $this->alreadyReadUpdates[$updateId] = time();

                $request = null;

                $type = $update->detectType();

                 if ($type === MessageType::CALLBACK_QUERY) {
                    $this->runtime->debug("Got Callback", (array)$update);
                    $reply = 'tg'.':'.$update->getChat()->id.':'.MessageType::CALLBACK_QUERY.':'.$update->callbackQuery->id;
                    $request = new RunRequest($updateId, '/telegram/callback', $reply);
                    $request->data = $update->getCallbackQuery();
                } else if ($type === MessageType::MESSAGE) {
                    $this->runtime->debug("Got Message", (array)$update);
                    $reply = 'tg'.':'.$update->getChat()->id.':'.MessageType::MESSAGE.':'.$update->message->messageId;
                    $request = new RunRequest($updateId, '/telegram/message', $reply);
                    $request->data = $update->getMessage();
                }

                if ($request) {
                    $this->core->process($request);
                } else {
                    $this->runtime->warning('Skipping message', [$update]);
                }
            }

            if (empty($updates)) {
               sleep(1);
            }
        }
    }
}