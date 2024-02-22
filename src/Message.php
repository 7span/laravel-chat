<?php

declare(strict_types=1);

namespace SevenSpan\Chat;

use SevenSpan\Chat\Models\Channel;
use SevenSpan\Chat\Helpers\Helper;
use SevenSpan\Chat\Events\SendMessage;
use SevenSpan\Chat\Models\ChannelUser;
use SevenSpan\Chat\Models\MessageRead;
use SevenSpan\Chat\Events\UpdateMessage;
use SevenSpan\Chat\Events\DeleteMessage;
use SevenSpan\Chat\Models\MessageVariable;
use SevenSpan\Chat\Channel as ChatChannel;
use SevenSpan\Chat\Models\Message as MessageModel;

final class Message
{
    public function list(int $userId, int $channelId, int $perPage = null)
    {
        $messages = MessageModel::with(['channel', 'sender', 'variables'])
            ->where('channel_id', $channelId)
            ->orderBy('id', 'DESC');
        $messages = $perPage ? $messages->paginate($perPage) : $messages->get();
        return $messages;
    }

    public function send(int $userId, int $channelId, array $data, array $variables = [])
    {
        if (!isset($data['body']) && !isset($data['file'])) {
            $error['errors']['message'][] = "The message body or file any one is should be required.";
            return $error;
        }

        $channel = Channel::where('id', $channelId)->first();

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

        // Adding the dynamic variables
        if(count($variables) > 0 ) {
            foreach($variables as $variable) {
                if(isset($variable['key']) && isset($variable['meta'])){
                    $messageVariable = MessageVariable::create([
                        'message_id' => $message->id,
                        'key' => $variable['key'],
                        'meta' => $variable['meta']
                    ]);
                }
            }
        }

        $message = MessageModel::with(['channel', 'sender', 'variables'])->find($message->id);

        broadcast(new SendMessage($channel->slug, $message))->toOthers();

        // Added the unread message count
        ChannelUser::where('channel_id', $channelId)->where('user_id', '!=', $userId)->increment('unread_message_count', 1, ['updated_by' => $userId]);

        // Added the entry into the unread message table
        $channelUsers = $channel->channelUsers->where('user_id', '!=', $userId);
        foreach ($channelUsers as $channelUser) {
            MessageRead::create([
                'user_id' => $channelUser->user_id,
                'message_id' => $message->id,
                'channel_id' => $channelId,
                'created_by' => $userId
            ]);
        }

        $response['message'] = 'Message send successfully.';
        $response['data'] = $message;
        return $response;
    }

    public function update(int $userId, int $channelId, int $messageId, array $data, array $variables = [])
    {
        if (!isset($data['body']) && !isset($data['file'])) {
            $error['errors']['message'][] = "The message body or file any one is should be required.";
            return $error;
        }

        $channel = Channel::where('id', $channelId)->first();

        if ($channel == null) {
            $error['errors']['message'][] = "Channel not found.";
            return $error;
        }

        $message = MessageModel::where('id', $messageId)->first();

        if ($message == null) {
            $error['errors']['message'][] = "Message not found.";
            return $error;
        }

        $messageData = [
            'body' => isset($data['body']) ? $data['body'] : null
        ];

        if (isset($data['file'])) {
            $file = Helper::fileUpload($data['file']);
            if (isset($file['errors'])) {
                return $file;
            }
            $messageData += $file;
        }

        $message->variables()->delete();
        $message->update($messageData);

        // Adding the dynamic variables
        if(count($variables) > 0 ) {
            foreach($variables as $variable) {
                if(isset($variable['key']) && isset($variable['meta'])){
                    MessageVariable::create([
                        'message_id' => $message->id,
                        'key' => $variable['key'],
                        'meta' => $variable['meta']
                    ]);
                }
            }
        }

        $message = MessageModel::with(['channel', 'sender', 'variables'])->find($message->id);

        broadcast(new UpdateMessage($channel->slug, $message))->toOthers();

        $response['message'] = 'Message updated successfully.';
        $response['data'] = $message;
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

        $message->variables()->delete();
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

    public function readAll(int $userId, int $channelId)
    {
        $channelObj = new ChatChannel();
        $channel = $channelObj->detail($userId, $channelId);

        if ($channel == null) {
            $error['errors']['message'][] = "Channel not found.";
            return $error;
        }

        ChannelUser::where('user_id', $userId)->where('channel_id', $channelId)->update(['unread_message_count' => 0]);

        $data['message'] = "Messages read successfully.";

        return $data;
    }
}
