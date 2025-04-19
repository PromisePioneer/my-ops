<?php

namespace App\Support\Master\Common\Branch\Interface;

use Illuminate\Http\Request;

interface BranchServiceInterface
{
    public function data();

    public function search(Request $request);

    public function getMainBranches(Request $request);

    public function selectedBranch(?int $branchId);
}
