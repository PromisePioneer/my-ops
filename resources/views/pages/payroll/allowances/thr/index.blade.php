@extends('layouts.template')
@section('page-title', 'Tunjangan Hari Raya')
@section('content')
    <div x-data="thrAllowanceData()">
        <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <ol class="lh-lg fw-semibold">
                    <li>
                        Pembayaran Tunjangan Hari Raya Keagamaan kepada karyawan dilakukan selambat - selambatnya 7
                        (tujuh) hari sebelum Hari Raya Keagamaan. Pemberiannya disesuaikan dengan Hari Raya Keagamaan
                        masing-masing karyawan.
                    </li>
                    <li>
                        Perusahaan wajib memberi THR kepada karyawan tidak tetap. Hanya saja, jumlah THR-nya
                        berbeda-beda sesuai masa bakti mereka. Karyawan tidak tetap yang telah selama 12 bulan atau
                        lebih berhak menerima THR sebesar satu bulan upah.
                    </li>
                    <li>
                        Sementara itu, karyawan yang masih bekerja kurang dari 12 bulan menerima THR secara
                        proporsional. Perhitungannya sebagai berikut: masa kerja (dalam hitungan bulan)/12 bulan x satu
                        bulan upah.
                    </li>
                    <li>
                        Pemberian THR dihitung sesuai dengan ketentuan Peraturan Perundang-undangan yang berlaku.
                    </li>
                </ol>
            </div>

            <button type="button"
                    class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                    data-bs-dismiss="alert">
                <i class="ki-duotone ki-cross fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
            </button>
        </div>

        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <button class="btn btn-light-primary btn-sm" @click="thrDataShow = !thrDataShow">
                        <i class="fas fa-eye"></i>
                        <span>Lihat Data THR</span>
                    </button>
                </div>
                <form id="generate-thr" @submit.prevent="generateTHR()">
                    <div class="row align-items-center justify-content-center py-4">
                        <div class="mb-4 w-50">
                            <label for="name" class="required form-label">Agama</label>
                            <select name="religion" class="form-select form-select-solid mb-4" data-control="select2"
                                    data-placeholder="Pilih Agama">
                                <option></option>
                                <option value="Islam">Islam</option>
                                <option value="Islam">Kristen</option>
                                <option value="Islam">Hindu</option>
                                <option value="Islam">Buddha</option>
                                <option value="Islam">Konghuchu</option>
                            </select>

                            <label for="name" class="required form-label">Tanggal</label>
                            <input type="date" id="date" name="date" class="form-control form-control-solid date"
                                   placeholder="Tanggal"/>

                            <div class="d-flex justify-content-end mt-7">
                                <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                                    <i class="ki-duotone ki-click fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                    <span x-text="buttonLoading ? 'Loading...' : 'Generate THR'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-xl-stretch mb-5 mb-xl-8" x-show="thrDataShow" x-cloak x-transition>
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h1>Tunjangan Hari Raya</h1>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                            <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                                   class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
                    <form id="form-delete" @submit.prevent="destroy()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <i class="ki-duotone ki-trash-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            Hapus
                        </button>
                    </form>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Karyawan</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Agama</th>
                                <th class="min-w-125px">Status</th>
                                <th class="min-w-125px">Gaji Pokok</th>
                                <th class="min-w-125px">Masa Bakti</th>
                                <th class="min-w-125px">Total THR</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && thrAllowances.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="thrAllowance in thrAllowances?.data"
                                      :key="thrAllowance.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <a :href="`/manage-users/users/detail/${thrAllowance.user_id}`"
                                           x-text="thrAllowance.user_name"></a>
                                    </td>
                                    <td x-text="thrAllowance.date"></td>
                                    <td x-text="thrAllowance.religion"></td>
                                    <td x-text="thrAllowance.employee_status"></td>
                                    <td x-text="thrAllowance.fixed_salary"></td>
                                    <td x-text="thrAllowance.period_of_service"></td>
                                    <td x-text="thrAllowance.total_thr"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ url('payroll/setting/') }}" class="btn btn-light-info btn-sm">
                            <i class="ki-duotone ki-black-left"></i>
                        </a>
                        <ul class="pagination float-end mb-4 mt-4">
                            <template x-for="pagination in thrAllowances.links">
                                <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                    <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                            x-html="pagination.label">
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function thrAllowanceData() {
            return {
                isLoading: false,
                buttonLoading: false,
                thrAllowances: [],
                startIndex: null,
                thrDataShow: false,
                search: '',
                formThr: document.getElementById('generate-thr'),
                async init() {
                    await this.getThrData();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/payroll/setting/allowances/thr/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.thrAllowances = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async generateTHR() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/payroll/setting/allowances/thr', new FormData(this.formThr))
                        await showAlert('success', 'Data berhasil disimpan')
                        await this.init();
                        this.thrDataShow = true;
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getThrData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/payroll/setting/allowances/thr/data');
                        this.thrAllowances = resp.data;
                        this.startIndex = this.thrAllowances.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush