@extends('layouts.template')
@section('page-title', 'Invoice Manager')
@section('content')
    <div class="d-flex flex-column flex-lg-row" x-data="generateInvoice">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card">
                <div class="card-body p-12">
                    <form id="form" @submit.prevent="generateInvoice()">
                        <div class="d-flex flex-column align-items-start flex-xxl-row">
                            <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                 data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice date">
                                <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Date:</div>
                                <div class="position-relative d-flex align-items-center w-150px">
                                    <input type="date" class="form-control form-control-white fw-bolder pe-5"
                                           placeholder="Select date" name="invoice_date" id="invoiceDate"/>
                                </div>
                            </div>
                            <div class="d-flex flex-center flex-equal fw-row text-nowrap order-1 order-xxl-2 me-4"
                                 data-bs-toggle="tooltip" data-bs-trigger="hover" title="Enter invoice number">
                                <span class="fs-2x fw-bolder text-gray-800">Invoice #</span>
                                <input type="text" name="invoice_number"
                                       class="form-control form-control-flush fw-bolder text-muted fs-3 w-125px"
                                       value="20240001"/>
                            </div>
                            <div class="d-flex align-items-center justify-content-end flex-equal order-3 fw-row"
                                 data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice due date">
                                <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Due Date:</div>
                                <div class="position-relative d-flex align-items-center w-150px">
                                    <input type="date" class="form-control form-control-white fw-bolder pe-5"
                                           placeholder="Select date" name="due_date" id="dueDate"/>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-12">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Pelanggan</label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               value="Nama Lengkap : {{ $fab->contact->full_name }}" disabled/>
                                    </div>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               value="Email : {{$fab->contact->email }}" disabled/>
                                    </div>
                                    <div class="mb-5">
                                        <input class="form-control form-control-solid" disabled
                                               value="NPWP : {{ $fab->contact->npwp }}"/>
                                    </div>
                                    <div class="form-group row mb-6">
                                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Kategori
                                            Layanan</label>
                                        <div class="col-lg-11 fv-row">
                                            <select name="account_id" class="form-select form-select-solid akunSearch"
                                                    data-placeholder="Select an option">
                                                <option selected>Pilih Kategori (Sesuai Dengan Layanan)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-20">
                                <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                    <thead>
                                    <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                        <th class="min-w-300px w-475px">Deskripsi</th>
                                        <th class="min-w-100px w-100px">Harga</th>
                                        <th class="min-w-150px w-150px">Jumlah</th>
                                        <th class="min-w-100px w-150px text-end">Total</th>
                                        <th class="min-w-75px w-75px text-end">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template x-for="(field,index) in fields " :key="index">

                                        <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                            <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                                <textarea type="text" class="form-control form-control-solid mb-2"
                                                          x-model="field.description"
                                                          :name="`data[${index}][description]`" placeholder="Deskripsi"
                                                          data-kt-autosize="true"></textarea>
                                            </td>
                                            <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                                <input class="form-control form-control-solid" type="number" min="1"
                                                       x-model="field.qty" :name="`data[${index}][qty]`" placeholder="1"
                                                       value="1" @change="calculateTotal(index)"/>
                                            </td>
                                            <td style='text-align:center; vertical-align:middle'>
                                                <input class="form-control form-control-solid" type="number" min="1"
                                                       x-model="field.unit_price" :name="`data[${index}][unit_price]`"
                                                       placeholder="0" value="0" @change="calculateTotal(index)"/>
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
                                                <button type="button"
                                                        class="btn btn-sm btn-icon btn-active-color-primary"
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
                                    <tr class="border-top border-top-dashed align-top fs-6 fw-bolder text-gray-700">
                                        <th class="text-primary">
                                            <button type="button" class="btn btn-link py-1" @click="add()">Tambah
                                            </button>
                                        {{--                                        </th>--}}
                                        {{--                                        <th colspan="2" class="border-bottom border-bottom-dashed ps-0">--}}
                                        {{--                                            <div class="d-flex flex-column align-items-start">--}}
                                        {{--                                                <div class="fs-5">Subtotal</div>--}}
                                        {{--                                                <button class="btn btn-link py-1" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Coming soon">Add tax</button>--}}
                                        {{--                                                <button class="btn btn-link py-1" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Coming soon">Add discount</button>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </th>--}}
                                        {{--                                        <th colspan="2" class="border-bottom border-bottom-dashed text-end">$--}}
                                        {{--                                            <span data-kt-element="sub-total" x-text="formatNumber(calculateTotalAll())">0.00</span></th>--}}
                                    </tr>
                                    <tr class="align-top fw-bolder text-gray-700">
                                        <th></th>
                                        <th colspan="2" class="fs-4 ps-0">Grand Total</th>
                                        <th colspan="2" class="text-end fs-4 text-nowrap">
                                            <span x-text="formatNumber(calculateTotalAll())">0.00</span>
                                            <input type="hidden" name="grand_total" x-model="calculateTotalAll()"/>
                                        </th>

                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row mb-10">
                                <div class="col-lg-6">
                                    <div class="mb-0">
                                        <label class="form-label fs-6 fw-bolder text-gray-700 required">BAA</label>
                                        <input class="form-control form-control-solid" type="file" name="baa_file"
                                               accept="application/pdf"/>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-0">
                                        <label class="form-label fs-6 fw-bolder text-gray-700 required">Kontrak
                                            Kerjasama</label>
                                        <input class="form-control form-control-solid" type="file"
                                               name="cooperative_contract_file" accept="application/pdf"/>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bolder text-gray-700">Catatan</label>
                                <textarea name="description" class="form-control form-control-solid" rows="3"
                                          placeholder="Thanks for your business"></textarea>
                            </div>
                        </div>

                        <div class="float-end">
                            <a class="btn btn-sm btn-light" href="{{ url('income-transactions/fab/') }}">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Generate Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')

    <script>
        $("#invoiceDate").flatpickr();
        $("#dueDate").flatpickr();

        const form = document.getElementById('form');
        document.addEventListener('alpine:init', () => {
            Alpine.data('generateInvoice', () => ({
                $fabId: '{{ $fab->id }}',
                fields: [{
                    description: '',
                    qty: '',
                    unit_price: '',
                    total_price: '',
                }],
                async init() {
                    $(".akunSearch").select2({
                        ajax: {
                            url: '/income-transactions/invoice/account/data',
                            dataType: "json",
                            type: "GET",
                            data: function (params) {
                                return {
                                    search: params.term
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    });
                },
                async generateInvoice() {
                    try {
                        const data = new FormData(form);
                        data.append('fields', JSON.stringify(this.fields));
                        this.buttonLoading = true;
                        await axios.post(`/income-transactions/invoice/generate-after-fab/${this.$fabId}`, new FormData(form))
                            .then((response) => {
                                Swal.fire({
                                    title: "Berhasil",
                                    icon: "success"
                                }).then(() => {
                                    window.location.href = '{{ url('/income-transactions/invoice/') }}';
                                        this.buttonLoading = false;
                                    });
                                });
                    } catch (error) {
                        this.buttonLoading = false;
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => {
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast.blade.php-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            };

                            toastr.error(`${respError[err][0]}`);
                        });
                    }
                },
                add() {
                    this.fields.push({
                        description: '',
                        qty: '',
                        unit_price: '',
                        total_price: '',
                    });
                },
                calculateTotal(index) {
                    console.log(index)
                    const quantity = this.fields[index].qty;
                    const unitPrice = this.fields[index].unit_price;
                    this.fields[index].total_price = (quantity * unitPrice).toFixed(2);
                },
                calculateTotalAll() {
                 return this.fields.reduce((total, field) => total + (field.qty * field.unit_price), 0);
                },
                formatNumber(curr){
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
                removeField(index) {
                    if(this.fields.length > 1){
                        this.fields.splice(index, 1);
                    }
                },

            }));
        });
    </script>
@endpush
