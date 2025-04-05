<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe(Channel $channel, Request $request)
    {
        $currentUser = \Auth::user();

        if ($currentUser->subscriptions()->active()->where('channel_id', $channel->id)->exists()) {
            return response()->json(['error' => 'You already subscribed to this channel.'], 403);
        }
        if (!$channel->is_free && $channel->user_id !== $currentUser->id) {
            if ($currentUser->balance < $channel->subscription_price) {
                return response()->json(['error' => 'Insufficient funds. Please top up your wallet.'], 402);
            }
            $channelName = $channel->name;
            $channelLink = route('account.channels.show', $channel->id);

            TransactionService::transfer($currentUser, $channel->user, $channel->subscription_price, "Subscription payment on channel <a href='$channelLink'>$channelName</a>");
        }

        $currentUser->subscriptions()->create([
            'channel_id' => $channel->id,
            'amount' => $channel->subscription_price
        ]);

        return response()->json([
            'success' => 'You have subscribed to this channel.',
            'channel' => $channel
        ], 200);
    }

    public function unsubscribe(Channel $channel, Request $request)
    {
        $currentUser = \Auth::user();

        if (!$currentUser->subscriptions()->active()->where('channel_id', $channel->id)->exists()){
            return response()->json(['error' => 'Subscription not found'], 404);
        }
        $currentUser->subscriptions()->where('channel_id', $channel->id)->delete();
        return response()->json(['success' => 'You have unsubscribed from this channel.', 'channel'=>$channel], 200);
    }

    public function getSubscriptionsByChannel()
    {
        $user = auth()->user();
        $channels = $user->channels;

        $data = [];

        foreach ($channels as $channel) {
            $subscriptions = $channel->subscriptions()
                ->where('subscriptions.created_at', '>=', now()->subDays(15))
                ->select(
                    \DB::raw('DATE(subscriptions.created_at) as date'),
                    \DB::raw('COUNT(subscriptions.id) as count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $subscriptionData = $subscriptions->map(function ($subscription) {
                return [
                    'x' => $subscription->date,
                    'y' => $subscription->count,
                ];
            });

            $data[] = [
                'label' => $channel->name,
                'data' => $subscriptionData->toArray(),
            ];
        }

        return response()->json($data);
    }
}
