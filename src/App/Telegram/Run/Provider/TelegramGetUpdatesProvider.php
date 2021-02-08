<?php


namespace App\Telegram\Run\Provider;

use App\Telegram\Service\TelegramUpdatePull;
use Telegram\Bot\Api;
use Verse\Run\Provider\RequestProviderProto;
use Verse\Run\RunRequest;

class TelegramGetUpdatesProvider extends RequestProviderProto
{

    private TelegramUpdatePull $puller;

    private $alreadyReadUpdates = [];

    private $lastUpdateId = 0;

    public function prepare()
    {
        $this->puller = new TelegramUpdatePull();
    }

    public function run()
    {
        while (true) {
            $updates = $this->puller->get($this->lastUpdateId);
            foreach ($updates as $index => $update) {
                $updateId = $update->getUpdateId();
                if (isset($this->alreadyReadUpdates[$updateId])) {
                    continue;
                }

                $this->alreadyReadUpdates[$updateId] = time();

                $request = null;

                if ($update->getMessage()) {
                    $request = new RunRequest($updateId, '/telegram/message');
                    $request->data = $update->getMessage();
                } else if ($update->getCallbackQuery()) {
                    $request = new RunRequest($updateId, '/telegram/callback');
                    $request->data = $update->getCallbackQuery();
                }

                if ($request) {
                    $this->core->process($request);
                } else {
                    $this->runtime->warning('Skipping message', [$update]);
                }

                if (count($this->alreadyReadUpdates) > 100) {
                    $latest = min($this->alreadyReadUpdates);
                    $latestKey = array_search($latest, $this->alreadyReadUpdates);
                    if ($latestKey) {
                        unset($this->alreadyReadUpdates[$latestKey]);
                    }
                }
            }

            if (empty($updates)) {
               sleep(1);
            }
        }
    }
}