<?php

declare(strict_types=1);

namespace SevenSpan\LaravelChat;

use SevenSpan\LaravelChat\Models\Channel;
use SevenSpan\LaravelChat\Models\Message;
use SevenSpan\LaravelChat\Models\ChannelUser;

final class Chat
{
    public function channelList(int $userId, int $perPage = null)
    {
        $channels = Channel::select('channels.id', 'name', 'channel_id', 'unread_message_count')->join('channel_users', 'channels.id', '=', 'channel_users.channel_id')->where('channel_users.user_id', $userId)->orderBy('channel_users.unread_message_count', 'DESC');
        $channels = $perPage ? $channels->paginate($perPage) : $channels->get();
        return $channels;
    }

    public function channelDetail(int $userId, int $channelId)
    {
        $channel = Channel::with('channelUser.user')->where('id', $channelId)->first();
        return $channel;
    }

    public function channelCreate(int $userId, int $receiverId, string $channelName)
    {
        if ($userId == $receiverId) {
            $data['errors']['message'][] = "The sender and receiver should be different.";
            return $data;
        }

        $channel = Channel::create(['name' => $channelName, 'created_by' => $userId]);

        ChannelUser::create(['user_id' => $userId, 'channel_id' => $channel->id, 'created_by' => $userId]);
        ChannelUser::create(['user_id' => $receiverId, 'channel_id' => $channel->id, 'created_by' => $userId]);

        $data['message'] = "Channel created successfully.";
        return $data;
    }

    public function channelUpdate(int $userId, int $channelId,  $channelName)
    {
        $channel = $this->channelDetail($userId, $channelId);

        if ($channel == null) {
            $data['errors']['message'][] = "Channel not found.";
            return $data;
        }

        $channel->update(['name' => $channelName, 'updated_by' => $userId]);
        $data['message'] = "Channel updated successfully.";
        return $data;
    }

    public function channelDelete(int $userId, int $channelId)
    {
        $channel = $this->channelDetail($userId, $channelId);

        if ($channel == null) {
            $data['errors']['message'][] = "Channel not found.";
            return $data;
        }

        $channel->update(['deletead_by' => $userId]);
        $channel->channelUser()->delete();
        $channel->delete();

        $data['message'] = "Channel deleted successfully.";
        return $data;
    }

    public function channelMessageList(int $userId, int $channelId, int $perPage = null)
    {
        $messages = Message::with(['channel', 'sender'])->where('channel_id', $channelId)->orderBy('id', 'DESC');
        $messages = $perPage ? $messages->paginate($perPage) : $messages->get();
        return $messages;
    }

    public function messageSend(int $userId, int $channelId, array $data)
    {
        $channel = $this->channelDetail($userId, $channelId);
    }
}
