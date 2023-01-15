<?php

use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\feedbackChannel;
use App\Broadcasting\newsletterChannel;
use App\Broadcasting\creditChannel;
use App\Broadcasting\cardChannel;
use App\Broadcasting\depositChannel;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('feedback', feedbackChannel::class);
Broadcast::channel('newsletter', newsletterChannel::class);
Broadcast::channel('credit', creditChannel::class);
Broadcast::channel('card', cardChannel::class);
Broadcast::channel('deposit', depositChannel::class);