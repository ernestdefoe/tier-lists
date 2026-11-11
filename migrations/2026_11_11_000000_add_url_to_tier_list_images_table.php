<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/*
 * Pictures held by FoF Upload are tracked here too (name `fof:<uuid>`, with
 * their URL), so a picture uploaded for a list is deleted with it.
 */
return Migration::addColumns('tier_list_images', [
    'url' => ['string', 'length' => 255, 'nullable' => true],
]);
