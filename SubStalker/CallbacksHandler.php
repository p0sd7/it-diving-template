<?php

namespace SubStalker;
use SubStalker\Subjects\VKClient;
use VK\CallbackApi\VKCallbackApiHandler;

// здесь получаем данные о пользователе
class CallbacksHandler extends VKCallbackApiHandler {
  private VKClient $client;
  private int $notification_receiver;
  private Notifier $notifier;

  public function __construct(VKClient $client, int $recepient_user_id)
  {
      $this->client = $client;
      $this->notification_receiver = $recepient_user_id;
      $this->notifier = new Notifier($client);
  }

  public function groupJoin(int $group_id, ?string $secret, array $object): void
  {
        $sub_id = (int)$object['user_id'];
        $this->notifier->notifyJoin($this->notification_receiver, $sub_id, $group_id);

  }

  public function groupLeave(int $group_id, ?string $secret, array $object): void
  {
      $sub_id = (int)$object['user_id'];
      $this->notifier->notifyLeave($this->notification_receiver, $sub_id, $group_id);;
  }
}
