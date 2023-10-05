<?php

declare(strict_types=1);

namespace SevenSpan\Chat;

use App\Models\User;
use SevenSpan\Chat\Helpers\Helper;
use SevenSpan\Chat\Models\Channel;
use SevenSpan\Chat\Models\Message;
use SevenSpan\Chat\Models\ChannelUser;
use SevenSpan\Chat\Models\MessageRead;

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
        $channel = Channel::with('channelUser.user')->whereHas('channelUser', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('id', $channelId)->first();
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

        $channel->update(['deleted_by' => $userId]);

        $this->channelMessageClear($userId, $channelId);

        $channel->channelUser()->delete();
        $channel->delete();

        $data['message'] = "Channel deleted successfully.";
        return $data;
    }

    public function channelMessageClear(int $userId, int $channelId)
    {
        $channel = $this->channelDetail($userId, $channelId);

        if ($channel == null) {
            $data['errors']['message'][] = "Channel not found.";
            return $data;
        }

        $messages = Message::where('channel_id', $channelId)->get();
        $documents = $messages->whereNotNull('disk');
        if ($documents->isNotEmpty()) {
            foreach ($documents as $document) {
                Helper::fileDelete($document->disk, $document->path, $document->filename);
            }
        }
        MessageRead::where('channel_id', $channelId)->delete();
        Message::where('channel_id', $channelId)->delete();
        ChannelUser::where('channel_id', $channelId)->update(['unread_message_count' => 0]);

        $data['message'] = "Channel message clear successfully.";

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
        if (!isset($data['body']) && !isset($data['file'])) {

            $error['errors']['message'][] = "The message body or file any one is should be required.";
            return $error;
        }

        $channel = $this->channelDetail($userId, $channelId);
        if ($channel == null) {
            $error['errors']['message'][] = "Channel not found.";
            return $error;
        }

        $messageData = [
            'channel_id' => $channel->id,
            'sender_id' => $userId,
            'created_by' => $userId,
            'body' => isset($data['body']) ? $data['body'] : null
        ];

        if (isset($data['file'])) {
            $file = Helper::fileUpload($data['file']);
            if (isset($file['errors'])) {
                return $file;
            }
            $messageData += $file;
        }

        $message = Message::create($messageData);

        // Added the unread message count
        ChannelUser::where('channel_id', $channelId)->where('user_id', '!=', $userId)->increment('unread_message_count', 1, ['updated_by' => $userId]);

        // Added the entry into the unread message table
        $channelUsers = $channel->channelUser->where('user_id', '!=', $userId);
        foreach ($channelUsers as $channelUser) {
            MessageRead::create([
                'user_id' => $channelUser->user_id,
                'message_id' => $message->id,
                'channel_id' => $channelId,
                'created_by' => $userId
            ]);
        }

        $response['message'] = 'Message send successfully.';
        return $response;
    }

    public function getFiles(int $userId, int $channelId, string $type = 'image', int $perPage = null)
    {
        if (!in_array($type, ['image', 'zip'])) {
            $data['errors']['type'][] = 'The files types must be image or zip.';
            return $data;
        }
        $messages = Message::where('channel_id', $channelId)->where('type', $type)->orderBy('id', 'DESC');
        $messages = $perPage == null ? $messages->get() : $messages->paginate($perPage);
        return $messages;
    }

    public function messageDelete(int $userId, int $channelId, $messageId)
    {
        $message = Message::where('sender_id', $userId)->where('channel_id', $channelId)->find($messageId);

        if ($message == null) {
            $data['errors']['message'][] = 'Sorry, This message is not found.';
            return $data;
        }
        if ($message->disk != null) {
            Helper::fileDelete($message->disk, $message->path, $message->filename);
        }

        $this->messageReadDelete($userId, $channelId, (int)$messageId);

        $message->delete();

        $data['message'] = "Message deleted successfully.";
        return $data;
    }

    public function messageRead(int $userId, int $channelId, int $messageId)
    {
        $message = Message::select('id')->where('channel_id', $channelId)->find($messageId);

        if ($message == null) {
            $data['errors']['message'][] = "Message not found.";
            return $data;
        }

        $unReadMessage = messageRead::where('user_id', $userId)->where('channel_id', $channelId)->where('message_id', '<=', $messageId)->whereNull('read_at');

        ChannelUser::where("user_id", $userId)->where('channel_id', $channelId)->decrement('unread_message_count', $unReadMessage->count(), ['updated_by' => $userId]);

        $unReadMessage->update(['read_at' => now()]);

        $data['message'] = 'Messages read successfully.';
        return $data;
    }

    public function messageReadDelete(int $userId, int $channelId, int $messageId)
    {
        $messageRead = messageRead::where('channel_id', $channelId)->where('message_id', $messageId)->whereNull('read_at');
        $unReadMessage = $messageRead->first();

        if ($unReadMessage) {
            ChannelUser::where("user_id", $unReadMessage->user_id)->where('channel_id', $channelId)->decrement('unread_message_count', 1, ['updated_by' => $userId]);
        }
        $messageRead->delete();
    }

    public function usersList(int $userId, string $name = null, int $perPage = null)
    {
        $users = User::where('id', '!=', $userId);

        if ($name) {
            $users->where("name", 'LIKE', "{$name}%");
        }

        $users = $perPage ? $users->paginate($perPage) : $users->get();
        return $users;
    }
}
