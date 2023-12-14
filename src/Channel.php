<?php

declare(strict_types=1);

namespace SevenSpan\Chat;

use SevenSpan\Chat\Helpers\Helper;
use SevenSpan\Chat\Models\Message;
use SevenSpan\Chat\Models\ChannelUser;
use SevenSpan\Chat\Models\MessageRead;
use SevenSpan\Chat\Events\CreateChannel;
use SevenSpan\Chat\Models\Channel as ChannelModel;

class Channel
{
    public function list(int $userId = null, array $channelIds = [], int $perPage = null)
    {
        $channels = ChannelModel::select('channels.id', 'name', 'channel_id', 'channels.slug', 'unread_message_count')
            ->join('channel_users', 'channels.id', '=', 'channel_users.channel_id')->with('channelUsers.user');

        if (!empty($userId)) {
            $channels->where('channel_users.user_id', $userId);
        }

        if (count($channelIds) > 0) {
            $channels->whereIn('channels.id', $channelIds);
        }

        $channels->orderBy('channel_users.unread_message_count', 'DESC');
        $channels = $perPage ? $channels->paginate($perPage) : $channels->get();
        return $channels;
    }

    public function detail(int $userId, int $channelId)
    {
        $channel = ChannelModel::with('channelUsers.user')
            ->whereHas('channelUsers', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('id', $channelId)->first();

        if (empty($channel)) {
            $data['errors']['channel'][] = 'Channel not found.';
            return $data;
        }

        return $channel;
    }

    public function create(int $userId, int $receiverId, string $channelName)
    {
        if ($userId == $receiverId) {
            $data['errors']['message'][] = "The sender and receiver should be different.";
            return $data;
        }

        $channel = ChannelModel::create(['name' => $channelName, 'created_by' => $userId]);

        ChannelUser::create(['user_id' => $userId, 'channel_id' => $channel->id, 'created_by' => $userId]);
        ChannelUser::create(['user_id' => $receiverId, 'channel_id' => $channel->id, 'created_by' => $userId]);

        broadcast(new CreateChannel($channel))->toOthers();

        $response['message'] = "Channel created successfully.";
        $response['data'] = $channel;
        return $response;
    }

    public function update(int $userId, int $channelId,  $channelName)
    {
        $channel = $this->detail($userId, $channelId);

        if ($channel == null) {
            $data['errors']['message'][] = "Channel not found.";
            return $data;
        }

        $channel->update(['name' => $channelName, 'updated_by' => $userId]);
        $data['message'] = "Channel updated successfully.";
        return $data;
    }

    public function delete(int $userId, int $channelId)
    {
        $channel = $this->detail($userId, $channelId);

        if ($channel == null) {
            $data['errors']['message'][] = "Channel not found.";
            return $data;
        }

        $channel->update(['deleted_by' => $userId]);

        $this->clearMessages($userId, $channelId);

        $channel->channelUsers()->delete();
        $channel->delete();

        $data['message'] = "Channel deleted successfully.";
        return $data;
    }

    public function clearMessages(int $userId, int $channelId)
    {
        $channel = $this->detail($userId, $channelId);

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
}
