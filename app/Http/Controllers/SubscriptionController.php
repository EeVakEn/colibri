<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe(Channel $channel, Request $request)
    {
        $currentUser = \Auth::user();
        if ($currentUser->subscriptions()->active()->where('channel_id', $channel->id)->exists()){
            return response()->json(['error' => 'You already subscribed to this channel.'], 403);
        }
        if (!$channel->is_free){
            // TODO: Order with debit channel  subscription price
            return response()->json(['error' => 'Paid channel subs coming soon'], 403);
        }
        $currentUser->subscriptions()->create(['channel_id'=>$channel->id, 'amount'=>$channel->subscription_price]);
        return response()->json(['success' => 'You have subscribed to this channel.', 'channel'=>$channel], 200);
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
}
