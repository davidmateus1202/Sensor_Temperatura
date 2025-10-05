<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('sensor', function ($user, $id) {
    return true;
});
