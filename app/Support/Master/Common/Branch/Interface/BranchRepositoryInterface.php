<?php

namespace App\Support\Master\Common\Branch\Interface;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface BranchRepositoryInterface
{
    public function handle(Request $request): Builder;

    public function getAllBranches(string $search): Collection;

    public function getMainBranches(Request $request): Collection;

    public function getSelectedBranch(?int $branchId): ?Branch;
}
