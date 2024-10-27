@extends('layouts.template')
@section('page-title', 'BoQ Manager')
@section('content')
    <style>
        .modal-open .select2-container--bootstrap5 .select2-dropdown {
            z-index: 1020 !important;
        }
    </style>
    <div class="d-flex flex-column flex-lg-row" x-data="generateBoQ()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="generateBoQ()">
                    <div class="card-body">
                        <div class="row gx-10 mb-5">
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Judul</label>
                                <div class="mb-5">
                                    <input type="text" class="form-control form-control-solid required" name="title"
                                           id="title"
                                           placeholder="Judul BoQ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row mb-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Tanggal</label>
                                    <div class="col-lg-11 fv-row">
                                        <input type="date" class="form-control form-control-solid fw-bolder pe-5 date"
                                               placeholder="Tanggal" name="date" id="date"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed"></div>
                        <div class="mb-0 mt-4 mb-4 text-center fw-bold text-uppercase text-decoration-underline fs-2">
                            Daftar Barang
                        </div>
                        <div class="table-responsive">
                            <table class="table g-5 gs-0 mb-0 fw-bold" data-kt-element="items">
                                <thead>
                                <tr>
                                </tr>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-300px w-475px required">Nama Barang</th>
                                    <th class="min-w-100px w-100px required">Merk</th>
                                    <th class="min-w-150px w-150px required">Jumlah</th>
                                    <th class="min-w-150px w-150px required">Satuan</th>
                                    <th class="min-w-150px w-150px required">Harga Satuan</th>
                                    <th class="min-w-150px w-150px required">Tanggal</th>
                                    <th class="min-w-150px w-150px required">Keterangan</th>
                                    <th class="min-w-100px w-150px required">Total</th>
                                    <th class="min-w-75px w-75px text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(field,index) in boqCommodities" :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="ps-0 text-center" style='text-align:center; vertical-align:middle'>
                                            <input type="text" class="form-control form-control-solid mb-2"
                                                   x-model="field.name" :name="`data[${index}][name]`"
                                                   placeholder="Nama Barang">
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <input type="text" class="form-control form-control-solid" min="1"
                                                   x-model="field.merk" :name="`data[${index}][merk]`"
                                                   placeholder="Merk"/>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <input type="number" class="form-control form-control-solid" min="1"
                                                   x-model="field.qty" :name="`data[${index}][qty]`" placeholder="1"
                                                   value="1" @change="calculateTotal(index)"/>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <select :name="`data[${index}][unit_type_id]`"
                                                    class="form-select form-select-solid unit-type-select2">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.unit_price" :name="`data[${index}][unit_price]`"
                                                   placeholder="0" value="0" @change="calculateTotal(index)"/>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <input type="date" :name="`data[${index}][used_estimation]`"
                                                   class="form-control form-control-solid date"
                                                   placeholder="Tanggal Estimasi">
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <textarea :name="`data[${index}][description]`"
                                                      class="form-control form-control-solid"
                                                      data-kt-autosize="true"></textarea>
                                        </td>
                                        <td class="pt-8 text-end text-nowrap"
                                            style='text-align:center; vertical-align:middle'>
                                            <span x-model="field.total_price"
                                                  x-text="formatNumber(field.unit_price * field.qty)">
                                            </span>
                                            <input type="hidden" :name="`data[${index}][total_price]`"
                                                   x-model="Number(field.unit_price * field.qty)">
                                        </td>
                                        <td class="pt-5 text-end" style='text-align:center; vertical-align:middle'>
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    @click="removeField(index)">
                                                    <span class="svg-icon svg-icon-3">
                                                        <i class="bi bi-trash"></i>
                                                    </span>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>

                        <div class="border-top border-top-dashed align-top fs-6 fw-bolder text-gray-700 mb-10">
                            <div class="text-primary pt-5">
                                <button type="button" class="btn btn-link py-1" @click="add()">Tambah</button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <h1 class="fs-4 ps-0 text-end mx-10">Grand Total</h1>
                            <h1 class="text-end fs-4 text-nowrap">
                                <span x-text="formatNumber(calculateTotalAll())">0.00</span>
                                <input type="hidden" name="grand_total" x-model="calculateTotalAll()"/>
                            </h1>
                        </div>

                        <div class="separator separator-dashed"></div>
                        <div class="mb-0 mt-4 mb-4 text-center fw-bold text-uppercase text-decoration-underline fs-2">
                            Daftar Rencana Waktu Pekerjaan
                        </div>
                        <div class="table-responsive">
                            <table class="table g-5 gs-0 mb-0 fw-bold" data-kt-element="items">
                                <thead>
                                <tr>
                                </tr>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-300px w-475px">Pekerjaan</th>
                                    <th class="min-w-100px w-100px">Qty</th>
                                    <th class="min-w-150px w-150px">Satuan</th>
                                    <th class="min-w-150px w-150px">Tgl. awal</th>
                                    <th class="min-w-150px w-150px">Tgl. Akhir</th>
                                    <th class="min-w-150px w-150px">PIC</th>
                                    <th class="min-w-150px w-150px">Total Teknisi</th>
                                    <th class="min-w-75px w-75px text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(field,index) in projectTimeline" :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="ps-0 text-center" style='text-align:center; vertical-align:middle'>
                                            <input type="text" class="form-control form-control-solid mb-2"
                                                   x-model="field.ProjectTimelineName"
                                                   :name="`projectTimeline[${index}][name]`"
                                                   placeholder="Nama Pekerjaan">
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <input type="number" class="form-control form-control-solid" min="1"
                                                   x-model="field.qty" :name="`projectTimeline[${index}][qty]`"
                                                   placeholder="1"
                                                   value="1"/>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <select :name="`projectTimeline[${index}][unit_type_id]`"
                                                    class="form-select form-select-solid unit-type-select2">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <input class="form-control form-control-solid date" type="date" min="1"
                                                   x-model="field.start_date"
                                                   :name="`projectTimeline[${index}][start_date]`"
                                                   placeholder="Tanggal Awal"/>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <input class="form-control form-control-solid date" type="date" min="1"
                                                   x-model="field.end_date"
                                                   :name="`projectTimeline[${index}][end_date]`"
                                                   placeholder="Tanggal Akhir"/>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <select :name="`projectTimeline[${index}][pic]`"
                                                    class="form-select form-select-solid users-select2">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td class="pt-8 text-end text-nowrap"
                                            style='text-align:center; vertical-align:middle'>
                                            <input class="form-control form-control-solid" type="text" min="1"
                                                   x-model="field.technician"
                                                   :name="`projectTimeline[${index}][technician]`"
                                                   placeholder="Total Teknisi" value="0"/>
                                        </td>
                                        <td class="pt-5 text-end" style='text-align:center; vertical-align:middle'>
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    @click="removeField(index)">
                                                    <span class="svg-icon svg-icon-3">
                                                        <i class="bi bi-trash"></i>
                                                    </span>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>

                        <div class="border-top border-top-dashed align-top fs-6 fw-bolder text-gray-700 mb-10">
                            <div class="text-primary pt-5">
                                <button type="button" class="btn btn-link py-1" @click="addProjectProjectTimeLine()">
                                    Tambah
                                </button>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-15"></div>


                        <div class="mb-10">
                            <div class="col-md-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Lampiran</label>
                                <input type="file" class="form-control form-control-solid" name="attachment"
                                       id="attachment" accept="application/pdf">
                            </div>
                        </div>
                    </div>


                    <div class="float-end">
                        <a href="{{ url('/inventory/boq') }}" class="btn btn-sm btn-light">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-light-primary" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Generate BoQ'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('components.toast')
@endsection
@push('script')
    <script>
        function generateBoQ() {
            return {
                buttonLoading: false,
                form: document.getElementById('form'),
                boqCommodities: [{
                    name: '',
                    merk: '',
                    qty: '',
                    unit_type_id: '',
                    unit_price: '',
                    total_price: '',
                    used_estimation: '',
                    description: '',
                }],
                projectTimeline: [{
                    projectTimelineName: '',
                    qty: '',
                    start_date: '',
                    end_date: '',
                    pic: '',
                    technician: '',
                }],

                async init() {
                    this.$nextTick(async () => {
                        await this.getUsersData();
                        await this.getUnitTypeData();
                        $(".date").flatpickr();
                    });
                },
                async getUnitTypeData() {
                    $(".unit-type-select2").select2({
                        allowClear: true,
                        placeholder: "Satuan",
                        ajax: {
                            url: '/inventory/boq/unit-type/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getUsersData() {
                    $(".users-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Teknisi",
                        ajax: {
                            url: '/inventory/boq/users/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async generateBoQ() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/boq/`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/inventory/boq';
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                add() {
                    this.$nextTick(() => {
                        $(".date").flatpickr();
                        this.getUnitTypeData();
                    })

                    this.boqCommodities.push({
                        name: '',
                        merk: '',
                        qty: '',
                        unit_type_id: '',
                        unit_price: '',
                        total_price: '',
                        used_estimation: '',
                        description: '',
                    });
                },
                addProjectProjectTimeLine() {
                    this.$nextTick(() => {
                        $(".date").flatpickr();
                        this.getUnitTypeData();
                        this.getUsersData();
                    })

                    this.projectTimeline.push({
                        projectTimelineName: '',
                        qty: '',
                        start_date: '',
                        end_date: '',
                        pic: '',
                        technician: '',
                    });
                },
                removeField(index) {
                    if (this.boqCommodities.length > 1) {
                        this.boqCommodities.splice(index, 1);
                    }
                },
                calculateTotal(index) {
                    const quantity = this.boqCommodities[index].qty;
                    const unitPrice = this.boqCommodities[index].unit_price;
                    this.boqCommodities[index].total_price = (quantity * unitPrice).toFixed(2);
                },
                calculateTotalAll() {
                    return this.boqCommodities.reduce((total, field) => total + (field.qty * field.unit_price), 0);
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });
                    return IDR.format(curr);
                },
            }
        }
    </script>
@endpush
