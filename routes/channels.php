<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
// this is a default channel authorization .
// ______________________________________________________________________


// here we go . . .
Broadcast::channel('supervisor-channel', function ($user) {
    return Auth::guard('web')->check() && Auth::user()->getRoleNames() == 'supervisor';
});

Broadcast::channel('moderator-channel', function ($user) {
    return Auth::guard('web')->check() && Auth::user()->getRoleNames() == 'Moderator';
});

// Broadcast::channel('admin-channel', function ($user) {
//     return Auth::guard('web')->check(); // Check if user is authenticated via 'web' guard
// });

// Broadcast::channel('supplier-channel', function ($user) {
//     return Auth::guard('supplier')->check(); // Check if user is authenticated via 'supplier' guard
// });

// Broadcast::channel('market-channel', function ($user) {
//     return Auth::guard('market')->check(); // Check if user is authenticated via 'market' guard
// });

