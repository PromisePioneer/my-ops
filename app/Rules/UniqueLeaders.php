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

    private function validateDirector(Closure $fail)
    {
        $director = User::role('Direktur')->where('branch_id', null)->first();

        if ($director?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'Manager Operasional') {
            $fail('Direktur tidak boleh berada di cabang!');
        }

        if ($director !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'Direktur') {
            $fail("Direktur sudah terdaftar atas nama {$director->name}!");
        }
    }

    private function validateGeneralManager(Closure $fail): void
    {
        $generalManager = User::role('General Manager')->where('branch_id', null)->first();

        if ($generalManager?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'Manager Operasional') {
            $fail('General Manager tidak boleh berada di cabang!');
        }

        if ($generalManager !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'General Manager') {
            $fail("General Manager sudah terdaftar atas nama {$generalManager->name}!");
        }
    }

    public function validateFinanceManager(Closure $fail): void
    {
        $financeManager = User::role('Manager Keuangan')->where('branch_id', null)->first();


        if ($financeManager?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'Manager Keuangan') {
            $fail('Manager Keuangan tidak boleh berada di cabang!');
        }

        if ($financeManager !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'Manager Keuangan') {
            $fail("Manager Keuangan sudah terdaftar dengan atas nama {$financeManager->name} ");
        }
    }

    private function validateBranchManager(Closure $fail): void
    {
        $branchManager = User::role('Manager Cabang')->where('branch_id', $this->request->branch_id)->first();

        $branch = Branch::where('id', $this->request->branch_id)->first();


        if (isset($branchManager->id) === isset($this->request->route('user')->id)) {
            return;
        }


        if ($this->request->placement === 'Pusat' && $this->request->role[0] === 'Manager Cabang') {
            $fail('Manager Cabang tidak boleh berada di pusat');
        }

        if (isset($branchManager->id) && $branch !== null) {
            $fail("Cabang {$branch?->name} sudah mempunyai manager!");
        }
    }

    public function validateOperationalManager(Closure $fail): void
    {
        $operationalManager = User::role('Manager Operasional')->where('branch_id', null)->first();

        if ($operationalManager?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'Manager Operasional') {
            $fail('Manager Operasional tidak boleh berada di cabang!');
        }

        if ($operationalManager !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'Manager Operasional') {
            $fail("Manager Operasional sudah terdaftar atas nama {$operationalManager->name}!");
        }
    }

    private function validatePicNoc(Closure $fail): void
    {
        $picNoc = User::role('PIC NOC')->where('branch_id', null)->first();

        if ($picNoc?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'Manager Operasional') {
            $fail('PIC NOC tidak boleh berada di cabang!');
        }

        if ($picNoc !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'PIC NOC') {
            $fail("PIC NOC sudah terdaftar atas nama {$picNoc->name}!");
        }
    }

    private function validatePicCustomerService(Closure $fail): void
    {
        $picCustomerService = User::role('PIC Customer Service')->where('branch_id', null)->first();

        if ($picCustomerService?->id === $this->request->route('user')?->id) {
            return;
        }

        if ($this->request->placement === 'Cabang' && $this->request->role[0] === 'Manager Operasional') {
            $fail('PIC Customer Service tidak boleh berada di cabang!');
        }

        if ($picCustomerService !== null && $this->request->placement === 'Pusat' && $this->request->role[0] === 'PIC Customer Service') {
            $fail("PIC Customer Service sudah terdaftar atas nama {$picCustomerService->name}!");
        }
    }
}
