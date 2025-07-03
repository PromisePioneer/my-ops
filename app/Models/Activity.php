<?php

namespace App\Models;

use Laravel\Scout\Searchable;

class Activity extends \Spatie\Activitylog\Models\Activity
{

    use Searchable;

    public function __construct()
    {
        parent::__construct();
    }

    public function toSearchableArray(): array
    {
        return [
            'event' => $this->event,
            'users.name' => '',
            'description' => $this->description,
        ];
    }
}
