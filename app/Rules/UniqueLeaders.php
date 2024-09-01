<?php

namespace App\Rules;

use App\Models\Branch;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class UniqueLeaders implements ValidationRule
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->validateDirector($fail);
        $this->validateGeneralManager($fail);
        $this->validateFinanceManager($fail);
        $this->validateBranchManager($fail);
        $this->validateOperationalManager($fail);
        $this->validatePicNoc($fail);
        $this->validatePicCustomerService($fail);
    }

    private function validateDirector(Closure $fail): void
    {
        $director = User::role('Director')->where('branch_id', null)->first();

        if ($director?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'Director') {
            $fail('Direktur tidak boleh berada di cabang!');
        }

        if ($director !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'Director') {
            $fail("Direktur sudah terdaftar atas nama {$director->name}!");
        }
    }

    private function validateGeneralManager(Closure $fail): void
    {
        $generalManager = User::role('General Manager')->where('branch_id', null)->first();

        if ($generalManager?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'General Manager') {
            $fail('General Manager tidak boleh berada di cabang!');
        }

        if ($generalManager !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'General Manager') {
            $fail("General Manager sudah terdaftar atas nama {$generalManager->name}!");
        }
    }

    public function validateFinanceManager(Closure $fail): void
    {
        $financeManager = User::role('FA & Tax Manager')->where('branch_id', null)->first();

        if ($financeManager?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'FA & Tax Manager') {
            $fail('Manager Keuangan tidak boleh berada di cabang!');
        }

        if ($financeManager !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'FA & Tax Manager') {
            $fail("Manager Keuangan sudah terdaftar dengan atas nama {$financeManager->name} ");
        }
    }

    private function validateBranchManager(Closure $fail): void
    {
        $branchManager = User::role('Branch Manager')->where('branch_id', $this->request->branch_id)->first();

        $branch = Branch::where('id', $this->request->branch_id)->first();

        if (isset($branchManager->id) === isset($this->request->route('user')->id)) {
            return;
        }

        if ($this->request->placement === 'Pusat' && $this->request->role[0] === 'Branch Manager') {
            $fail('Manager Cabang tidak boleh berada di pusat');
        }

        if (isset($branchManager->id) && $branch !== null) {
            $fail("Cabang {$branch?->name} sudah mempunyai manager!");
        }
    }

    public function validateOperationalManager(Closure $fail): void
    {
        $operationalManager = User::role('Operational Manager')->where('branch_id', null)->first();

        if ($operationalManager?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'Operational Manager') {
            $fail('Operational Manager tidak boleh berada di cabang!');
        }

        if ($operationalManager !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'Operational Manager') {
            $fail("Manager Operasional sudah terdaftar atas nama {$operationalManager->name}!");
        }
    }

    private function validatePicNoc(Closure $fail): void
    {
        $picNoc = User::role('NOC Supervisor')->where('branch_id', null)->first();

        if ($picNoc?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'NOC Supervisor') {
            $fail('NOC Supervisor tidak boleh berada di cabang!');
        }

        if ($picNoc !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'NOC Supervisor') {
            $fail("NOC Supervisor sudah terdaftar atas nama {$picNoc->name}!");
        }
    }

    private function validatePicCustomerService(Closure $fail): void
    {
        $picCustomerService = User::role('Customer Service Leader')->where('branch_id', null)->first();

        if ($picCustomerService?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'Customer Service Leader') {
            $fail('Customer Service Leader tidak boleh berada di cabang!');
        }

        if ($picCustomerService !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'Customer Service Leader') {
            $fail("Customer Service Leader sudah terdaftar atas nama {$picCustomerService->name}!");
        }
    }
}
