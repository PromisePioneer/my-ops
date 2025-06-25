@extends('layouts.template')
@section('page-title', 'Tambah User')
@section('content')
    <div x-data="generateUser()">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <div class="card-title">
                    <a class="btn btn-info btn-sm mb-6" href="{{ url('manage-users/users/') }}">Kembali</a>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex align-items-center">

                        <label class="col-form-label required fw-bold fs-6 me-3">NIK</label>
                        <input class="form-control form-control-solid" :value="`${branchCode}${joinDate}${absentId}`"
                               disabled/>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        @if(!Auth::user()->branch_id)
                            <div class="row mb-4">
                                <div class="col-md-6" x-model="userPlacement">
                                    <label class="col-form-label required fw-bold fs-6">Penempatan</label>
                                    <select name="placement" id="selectedPlacement"
                                            class="form-select form-select-solid user-placement-select2"
                                            @change="placementChange()">
                                        <option value="0" selected>Pilih</option>
                                        <option value="Cabang" :selected="userPlacement === 'Cabang'">Cabang</option>
                                        <option value="Pusat" :selected="userPlacement === 'Pusat'">Pusat</option>
                                    </select>
                                </div>
                                <div class="col-lg-6" x-show="userPlacement === 'Cabang'" x-transition x-cloak>
                                    <label class="col-form-label required fw-bold fs-6">Cabang</label>
                                    <select :name="`${userPlacement === 'Cabang' ? 'branch_id' : ''}`"
                                            id="selected-branch"
                                            class="form-select form-select-solid main-branches-select2">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">ID Absen</label>
                                <input type="number" class="form-control form-control-solid" name="absent_id"
                                       id="absent_id" minlength="3" maxlength="3"
                                       placeholder="ID Absen"
                                       value="{{ $user->absent_id ?? $randomAbsentId }}" x-model="absentId">
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Nama</label>
                                <input type="text" name="name"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Nama" value="{{ $user->name ?? '' }}"/>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Email</label>
                                <input type="text" name="email"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="email" value="{{ $user->email ?? '' }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Tanggal Masuk</label>
                                <input type="date" name="join_date" id="join_date"
                                       class="form-control form-control-lg form-control-solid date"
                                       placeholder="Tanggal Masuk" value="{{ $user->join_date ?? '' }}"
                                       @change="formatDate()" x-model="joinDate"/>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Perusahaan</label>
                                <select name="company_id" id="selected-company"
                                        class="form-select form-select-solid companies-select2">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Password</label>
                                <input type="password" name="password" class="form-control form-control-solid"
                                       id="password" placeholder="password">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="col-form-label required fw-bold fs-6">Libur Mingguan</label>
                                <select name="day" id="day" class="form-select form-select-solid">
                                    <option>Pilih Hari Libur</option>
                                    <option
                                        value="Senin" {{ isset($user->weekHoliday?->day) ? $user->weekHoliday?->day === 'Senin' ? 'selected' : '' : '' }}>
                                        Senin
                                    </option>
                                    <option
                                        value="Selasa" {{ isset($user->weekHoliday?->day) ? $user->weekHoliday?->day  === 'Selasa' ? 'selected' : '' : '' }}>
                                        Selasa
                                    </option>
                                    <option
                                        value="Rabu" {{ isset($user->weekHoliday?->day) ? $user->weekHoliday?->day  === 'Rabu' ? 'selected' : '' : '' }}>
                                        Rabu
                                    </option>
                                    <option
                                        value="Kamis" {{ isset($user->weekHoliday?->day) ? $user->weekHoliday->day  === 'Kamis' ? 'selected' : '' : '' }}>
                                        Kamis
                                    </option>
                                    <option
                                        value="Jumat" {{ isset($user->weekHoliday?->day) ? $user->weekHoliday?->day  === 'Jumat' ? 'selected' : '' : '' }}>
                                        Jum'at
                                    </option>
                                    <option
                                        value="Sabtu" {{ isset($user->weekHoliday?->day) ? $user->weekHoliday?->day  === 'Sabtu' ? 'selected' : '' : '' }}>
                                        Sabtu
                                    </option>
                                    <option
                                        value="Minggu" {{ isset($user->weekHoliday?->day) ? $user->weekHoliday?->day  === 'Minggu' ? 'selected' : '' : '' }}>
                                        Minggu
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-6">
                            <label class="col-lg-1 col-form-label required fw-bold fs-6">Role</label>
                            <div class="col-lg-12 fv-row">
                                <div class="row">
                                    <template x-for="role in roles" :key="role.id">
                                        <div class="col-md-4 mt-2">
                                            <input class="form-check-input" type="radio"
                                                   :checked="users?.roles[0]?.id === role?.id" :value="role.name"
                                                   multiple
                                                   name="roles[]"/>
                                            <label class="form-check-label" for="flexCheckChecked">
                                                <span x-text="role.name"></span>
                                            </label>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="separator py-2"></div>

                    <div class="float-end d-flex py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2 btn-sm">Reset</button>
                        <button type="submit" class="btn btn-sm btn-light-primary"
                                :disabled="buttonLoading">
                            <i class="ki-duotone ki-click fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        $('.date').flatpickr();

        function generateUser() {
            return {
                buttonLoading: false,
                roles: null,
                branchCode: "{{ $user->branch?->code ?? null }}",
                joinDate: null,
                users: null,
                id: "{{ $user->id ?? null }}",
                absentId: "{{ $user->absent_id ?? null }}",
                companyId: '{{ $user->company_id ?? null }}',
                form: document.getElementById('form'),
                userPlacement: "{{ $user->placement ?? null }}",
                branchId: "{{ $user->branch_id ?? null }}",
                async init() {
                    await this.getMainBranches();
                    await this.selectedBranch();
                    await this.getCompanies();
                    await this.selectedCompany();
                    await this.getRoleData();
                    await this.getUserData();

                    this.joinDate = "{{ $user->join_date ?? null }}"
                    this.formatDate(this.joinDate);
                    this.branchCode = "{{ $user->branch?->code ?? null }}"
                },
                placementChange() {
                    if (this.userPlacement === 'Pusat') {
                        this.branchCode = '100';
                    }
                },
                async save() {
                    this.buttonLoading = true
                    try {
                        if (!this.id) {
                            await axios.post("{{ url('/manage-users/users') }}", new FormData(this.form))
                        } else {
                            await axios.post(`/manage-users/users/update/${this.id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/manage-users/users';
                        });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                formatDate(joinDate = null) {
                    const joinDateValue = joinDate ?? document.getElementById('join_date')?.value;
                    if (joinDateValue) {
                        const date = new Date(joinDateValue);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        this.joinDate = `${day}${month}${year}`;
                    }
                },
                async getMainBranches() {
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', (e) => {
                        this.branchCode = e.params?.data?.code;
                    });
                },
                async selectedBranch() {
                    if (!this.branchId) return;
                    const selectedBranch = $('#selected-branch');
                    const response = await axios.get(`/select2/selected-branch/${this.branchId}`);
                    const option = new Option(response.data.name, response.data.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response.data}
                    });
                },
                async getCompanies() {
                    $(".companies-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Perusahaan",
                        ajax: {
                            url: '/select2/companies-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
                },
                async selectedCompany() {
                    if (!this.companyId) return;
                    const selectedCompany = $('#selected-company');
                    const response = await axios.get(`/select2/selected-company/${this.companyId}`);
                    const option = new Option(response.data.name, response.data.id, true, true);
                    selectedCompany.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response.data}
                    });
                },
                async getRoleData() {
                    const resp = await axios.get('/manage-users/users/roles/data');
                    this.roles = resp.data;
                },
                async getUserData() {
                    if (!this.id) return;
                    const users = await axios.get(`/manage-users/users/show/${this.id}`);
                    this.users = users.data;
                },
            }
        }
    </script>
@endpush
