<?php

namespace SubStalker;

use Generator\Skeleton\skeleton\base\src\VK\CallbackApi\VKCallbackApiLongPollExecutor;
use SubStalker\Subjects\VKClient;
use VK\Client\VKApiClient;

class SubStalker
{
    private CallbacksHandler $handler;
    private VKCallbackApiLongPollExecutor $executor;
    private VKApiClient $apiClient;

    private VKClient $client;

    public function __construct(int $group_id, int $recepient_id, string $access_token)
    {
        $this->apiClient = new VKApiClient('5.131');
        $this->client = new VKClient(Config::ACCESS_TOKEN, $this->apiClient);
        $this->handler = new CallbacksHandler($this->client, $recepient_id);
        $this->executor = new VKCallbackApiLongPollExecutor(
            $this->apiClient,
            $access_token,
            $group_id,
            $this->handler
        );
    }

    public function listen()
    {
        $ts = time();
        while (true) {
            try {
                $ts = $this->executor->listen($ts);
            } catch (\Exception) {
            }
        }
    }
}
