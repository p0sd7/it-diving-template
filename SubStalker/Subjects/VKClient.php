<?php

namespace SubStalker\Subjects;
use VK\Client\VKApiClient;
class VKClient
{
    private string $access_token;
    private VKApiClient $client;

    public function __construct(string $access_token, VKApiClient $client)
    {
        $this->access_token = $access_token;
        $this->client = $client;
    }

    public function getSubscriber(int $sub_id): ?Subscriber
    {
        try {
            $response = $this->client->users()->get($this->access_token, [
                'user_ids' => [$sub_id], 'fields' => ['sex']
            ]);
        } catch (\Exception $exception){
            var_dump($exception);
            return null;
        }
        return new Subscriber(
            $sub_id,
            $response[0]['first_name'] . ' ' . $response[0]['last_name'],
            (int)$response[0]['sex']
        );
    }

    public function getClub(int $club_id): ?Club {
        try {
            $response = $this->client->groups()->getById(
                $this->access_token, [
                'group_id'=>$club_id
                ]
            );
        } catch(\Exception $exception){
            var_dump($exception);
            return null;
        }

        return new Club($club_id, $response[0]['name']);
    }

    public function sendMessage(int $recepient_id, string $text)
    {
        try {
            $_response = $this->client->messages()->send($this->access_token,
                ['peer_id' => $recepient_id,
                    'message' => $text,
                    'random_id'=>rand()]
            );
        }catch (\Exception $exception){
            var_dump($exception);
        }
    }
}