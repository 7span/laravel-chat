<?php

declare(strict_types=1);

namespace SevenSpan\Chat;

use SevenSpan\Chat\Helpers\Helper;
use SevenSpan\Chat\Events\SendMessage;
use SevenSpan\Chat\Models\ChannelUser;
use SevenSpan\Chat\Models\MessageRead;
use SevenSpan\Chat\Events\DeleteMessage;
use SevenSpan\Chat\Models\Message as MessageModel;

final class Message
{
    public function list(int $userId, int $channelId, int $perPage = null)
    {
        $messages = MessageModel::with(['channel', 'sender'])
            ->where('channel_id', $channelId)
            ->orderBy('id', 'DESC');
        $messages = $perPage ? $messages->paginate($perPage) : $messages->get();
        return $messages;
    }

    public function send(int $userId, int $channelId, array $data)
    {
        if (!isset($data['body']) && !isset($data['file'])) {
            $error['errors']['message'][] = "The message body or file any one is should be required.";
            return $error;
        }

        $channelObj = new Channel();
        $channel = $channelObj->detail($userId, $channelId);

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

        $message = MessageModel::create($messageData);

        broadcast(new SendMessage($channel->slug, $message))->toOthers();

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
        $messages = MessageModel::where('channel_id', $channelId)->where('type', $type)->orderBy('id', 'DESC');
        $messages = $perPage == null ? $messages->get() : $messages->paginate($perPage);
        return $messages;
    }

    public function delete(int $userId, int $channelId, $messageId)
    {
        $message = MessageModel::with('channel')->where('sender_id', $userId)->where('channel_id', $channelId)->find($messageId);

        if ($message == null) {
            $data['errors']['message'][] = 'Sorry, This message is not found.';
            return $data;
        }
        if ($message->disk != null) {
            Helper::fileDelete($message->disk, $message->path, $message->filename);
        }

        $messageRead = MessageRead::where('channel_id', $channelId)->where('message_id', $messageId)->whereNull('read_at');
        $unReadMessage = $messageRead->first();

        if ($unReadMessage) {
            ChannelUser::where("user_id", $unReadMessage->user_id)->where('channel_id', $channelId)->decrement('unread_message_count', 1, ['updated_by' => $userId]);
        }
        $messageRead->delete();

        broadcast(new DeleteMessage($message->channel->slug, $message))->toOthers();

        $message->delete();

        $data['message'] = "Message deleted successfully.";
        return $data;
    }

    public function read(int $userId, int $channelId, int $messageId)
    {
        $message = MessageModel::select('id')->where('channel_id', $channelId)->find($messageId);

        if ($message == null) {
            $data['errors']['message'][] = "Message not found.";
            return $data;
        }

        $unReadMessage = MessageRead::where('user_id', $userId)->where('channel_id', $channelId)->where('message_id', '<=', $messageId)->whereNull('read_at');

        ChannelUser::where("user_id", $userId)->where('channel_id', $channelId)->decrement('unread_message_count', $unReadMessage->count(), ['updated_by' => $userId]);

        $unReadMessage->update(['read_at' => now()]);

        $data['message'] = 'Messages read successfully.';
        return $data;
    }
}
