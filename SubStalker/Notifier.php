<?php

namespace SubStalker;

use SubStalker\Subjects\Subscriber;
use SubStalker\Subjects\Owner;
use SubStalker\Subjects\Club;
use SubStalker\Subjects\User;
use SubStalker\Subjects\VKClient;

class Notifier
{
    private VKClient $client;

    private const NOTIFICATION_TYPE_JOIN = 'join';
    private const NOTIFICATION_TYPE_LEAVE = 'leave';

    public function __construct(VKClient $client)
    {
        $this->client = $client;
    }

    public function notifyJoin(int $receiver_id, int $sub_id, int $club_id)
    {
        $this->notify(self::NOTIFICATION_TYPE_JOIN, $receiver_id, $sub_id, $club_id);
    }
    public function notifyLeave(int $receiver_id, int $sub_id, int $club_id)
    {
        $this->notify(self::NOTIFICATION_TYPE_LEAVE, $receiver_id, $sub_id, $club_id);
    }
    private function notify(string $type, int $receiver_id, int $sub_id, int $club_id)
    {
        $sub = $this->client->getSubscriber($sub_id);
        if (!$sub) {
            echo "failed to load user\r\n";
            return;
        }

        $club = $this->client->getClub($club_id);
        if (!$club) {
            echo "failed to load club\r\n";
            return;
        }

        $text = $this->buildText($type, $sub, $club);

        $this->client->sendMessage($receiver_id, $text);
    }

    private function buildMention(User $owner)
    {
        $prefix = ($owner instanceof User) ? 'id':'club';
        return "[{$prefix}{$owner->getId()}|{$owner->getName()}]";
    }

    private function buildText(string $type, Subscriber $sub, Club $club): string
    {
        $sub_mention = self::buildMention($sub);
        $club_mention = self::buildMention($club);
        switch ($type){
            case self::NOTIFICATION_TYPE_JOIN:
                if($sub->isFemale()){
                    $action_string = "подписалась";
                }else{
                    $action_string = "подписался";
                }
                return "{$sub_mention} {$action_string} на сообщество {$club_mention}:)";

            case self::NOTIFICATION_TYPE_LEAVE:
                if($sub->isFemale()){
                    $action_string = "покинула";
                }else{
                    $action_string = "покинул";
                }
                return "{$sub_mention} {$action_string} сообщество {$club_mention}:(";
            default:
                return "Событие непонятого типа :P";
        }
    }
}