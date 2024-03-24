<?php

namespace SubStalker;

use SubStalker\Subjects\Club;
use SubStalker\Subjects\Subscriber;
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

    public function notifyJoin(int $receiver_id, int $sub_id, int $club_id): void
    {
        $this->notifyAdmin(self::NOTIFICATION_TYPE_JOIN, $receiver_id, $sub_id, $club_id);
        $this->notifySub(self::NOTIFICATION_TYPE_JOIN, $sub_id, $club_id);
    }

    public function notifyLeave(int $receiver_id, int $sub_id, int $club_id): void
    {
        $this->notifyAdmin(self::NOTIFICATION_TYPE_LEAVE, $receiver_id, $sub_id, $club_id);
    }

    private function notifyAdmin(string $type, int $receiver_id, int $sub_id, int $club_id): void
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

        $sub_privacy = $sub->privacyStatus();

        $text = $this->buildText($type, $sub, $club);
        $text_about_privacy = $this->buildTextAboutPrivacy($type, $sub);
        $this->client->sendMessage($receiver_id, $text);

        if ($type != self::NOTIFICATION_TYPE_LEAVE) {
            if (!$sub_privacy) {
                $this->client->sendMessage($receiver_id, $text_about_privacy);
            }
        }
    }

    private function notifySub(string $type, int $sub_id, int $club_id): void
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

        $text = $this->buildTextForSub($type, $sub, $club);

        $this->client->sendMessage($sub_id, $text);
    }

    private function buildMention(User $user): string
    {
        $prefix = ($user instanceof User) ? 'id' : 'club';
        return "[{$prefix}{$user->getId()}|{$user->getName()}]";
    }

    private function buildText(string $type, Subscriber $sub, Club $club): string
    {
        $sub_mention = self::buildMention($sub);
        $club_mention = self::buildMention($club);
        switch ($type) {
            case self::NOTIFICATION_TYPE_JOIN:
                if ($sub->isFemale()) {
                    $action_string = "подписалась";
                } elseif ($sub->isMale()) {
                    $action_string = "подписался";
                }
                return "{$sub_mention} {$action_string} на сообщество {$club_mention}:)";

            case self::NOTIFICATION_TYPE_LEAVE:
                if ($sub->isFemale()) {
                    $action_string = "покинула";
                } elseif ($sub->isMale()) {
                    $action_string = "покинул";
                }
                return "{$sub_mention} {$action_string} сообщество {$club_mention}:(";
            default:
                return "Событие непонятого типа :P";
        }
    }

    private function buildTextForSub(string $type, Subscriber $sub, Club $club): string
    {
        $sub_mention = self::buildMention($sub);
        $club_mention = self::buildMention($club);
        switch ($type) {
            case self::NOTIFICATION_TYPE_JOIN:
                try {
                    return "{$sub_mention}, добро пожаловать в сообщество {$club_mention}!";
                } catch (\Exception $exception) {
                    echo $exception;
                }
            default:
                return "Событие непонятого типа :P";
        }
    }

    private function buildTextAboutPrivacy(string $type, Subscriber $sub): string
    {
        $sub_mention = self::buildMention($sub);
        switch ($type) {
            case self::NOTIFICATION_TYPE_JOIN:
                try {
                    return "Пользователь {$sub_mention} стесняется! Нельзя отправить приветственное сообщение:(";
                } catch (\Exception $exception) {
                    echo $exception;
                }
            default:
                return "Приветственное сообщение отправлено пользователю {$sub_mention}!";
        }
    }
}