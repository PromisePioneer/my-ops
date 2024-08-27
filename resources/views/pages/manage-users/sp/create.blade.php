@extends('layouts.template')
@section('page-title', 'Tambah Surat Peringatan')
@section('content')
    @push('styles')
        <script src="{{ asset('assets/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
    @endpush
    <div class="d-flex flex-column flex-lg-row" x-data="generateSP()">
        @include('pages.master.contact.modal.create')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="save()">
                    <div class="card-body p-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tanggal akhir :</div>
                                    <div class="position-relative d-flex align-items-center w-150px">
                                        <input type="date" class="form-control form-control-white fw-bolder pe-5 date"
                                               placeholder="Tanggal" name="date" id="date"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="row gx-10 mb-5">
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Karyawan</label>
                                <div class="mb-5">
                                    <select name="user_id" class="form-select form-select-solid users-select2"
                                            data-placeholder="Select an option">
                                        <option selected>Pilih Karyawan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row mb-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Tipe SP</label>
                                    <div class="col-lg-11 fv-row">
                                        <select name="sp_type" class="form-select form-select-solid account-select2"
                                                data-placeholder="Select an option">
                                            <option value="0" selected disabled>Pilih</option>
                                            <option value="SP-1">SP-1</option>
                                            <option value="SP-2">SP-2</option>
                                            <option value="SP-3">SP-3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mb-20">
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-300px w-475px required">Alasan</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(field,index) in fields " :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td style='text-align:center; vertical-align:middle' width="100%">
                                            <textarea type="text" class="form-control form-control-solid mb-2"
                                                      x-model="field.list_of_reason"
                                                      :name="`data[${index}][list_of_reason]`"
                                                      placeholder="Deskripsi" data-kt-autosize="true"></textarea>
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
                                <tr class="border-top border-top-dashed align-top fs-6 fw-bolder text-gray-700">
                                    <th class="text-primary">
                                        <button type="button" class="btn btn-link py-1" @click="add()">Tambah</button>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="float-end">
                        <a href="{{ url('/manage-users/sp') }}" class="btn btn-sm btn-light">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Generate SP'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        $(".date").flatpickr();

        function generateSP() {
            return {
                fields: [{
                    list_of_reason: '',
                }],
                form: document.getElementById('form'),
                buttonLoading: false,
                async init() {
                    await this.getUserData();
                },
                add() {
                    this.fields.push({
                        description: '',
                        qty: '',
                        unit_price: '',
                        total_price: '',
                    });
                },
                removeField(index) {
                    if (this.fields.length > 1) {
                        this.fields.splice(index, 1);
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/sp`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '{{ url('/manage-users/sp') }}'
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getUserData() {
                    $(".users-select2").select2({
                        ajax: {
                            url: '/manage-users/sp/users/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
            }
        }
    </script>
@endpush