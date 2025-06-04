<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
use App\Support\Attendances\WorkTime\Repositories\RoleDefaultWorkTimeRepository;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class RoleDefaultWorkTimeService
{
    public function __construct()
    {
        $this->roleDefaultWorkTimeRepository = new RoleDefaultWorkTimeRepository();
    }

    public function data()
    {

    }


    public function search(Request $request)
    {
    }

    public function filter(Request $request)
    {
    }
}
