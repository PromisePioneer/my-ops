@extends('layouts.template')
@section('page-title', 'Tambah Surat Peringatan')
@section('content')
    @push('styles')
        <script src="{{ asset('assets/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
    @endpush
    <div class="d-flex flex-column flex-lg-row" x-data="generateSP()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="save()">
                    <div class="card-body p-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover">

                                </div>
                            </div>
                        </div>
                        <div class="row gx-10 mb-5">
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Tanggal</label>
                                <input type="date" class="form-control form-control-solid fw-bolder pe-5 date"
                                       placeholder="Tanggal" name="start_date" id="start_date"
                                       value="{{ $sp->start_date }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Karyawan</label>
                                <div class="mb-5">
                                    <select name="user_id" id="selected-user"
                                            class="form-select form-select-solid users-select2"
                                            data-placeholder="Select an option">
                                        <option selected>Pilih Karyawan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row mb-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Tipe SP</label>
                                    <select name="sp_type" class="form-select form-select-solid account-select2"
                                            data-placeholder="Select an option">
                                        <option value="0" selected disabled>Pilih</option>
                                        <option value="ST" {{ $sp->sp_type === 'ST' ? 'selected' : '' }}>
                                            ST
                                        </option>
                                        <option value="SP-1" {{ $sp->sp_type === 'SP-1' ? 'selected' : '' }}>
                                            SP-1
                                        </option>
                                        <option value="SP-2" {{ $sp->sp_type === 'SP-2'  ? 'selected' : '' }}>
                                            SP-2
                                        </option>
                                        <option value="SP-3" {{ $sp->sp_typ === 'SP-3'  ? 'selected' : '' }}>
                                            SP-3
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Yang Memberi Sanksi
                                    </label>
                                    <select name="punished_by" class="form-select form-select-solid sp-pic"
                                            id="selected-punished-by">
                                        <option></option>
                                    </select>
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
                                                      placeholder="Deskripsi"
                                                      data-kt-autosize="true" x-text="field"></textarea>
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
    @include('components.select2.script')
    @include('components.toast')
@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function generateSP() {
            return {
                fields: [],
                listOfReason: [],
                spId: '{{ $sp->id  }}',
                userId: "{{ $sp->user_id }}",
                punishedBy: "{{ $sp->punished_by }}",
                form: document.getElementById('form'),
                buttonLoading: false,
                async init() {
                    await this.getListOfReason();
                    await select2('.users-select2', 'Pilih Karyawan', '/select2/users-data')
                    await select2('.sp-pic', 'Pilih yang memberi sanksi', '/manage-users/sp/sp-pic/data')
                    await this.selectedValue();
                },
                async selectedValue() {
                    await selectedValue('selected-user', `/select2/selected-user/${this.userId}`);
                    await selectedValue('selected-punished-by', `/select2/selected-user/${this.punishedBy}`);
                },
                add() {
                    this.fields.push({
                        list_of_reason: '',
                    });
                },
                removeField(index) {
                    if (this.fields.length > 1) {
                        this.fields.splice(index, 1);
                    }
                },
                async getListOfReason() {
                    const resp = await axios.get(`/manage-users/sp/list-of-reason/${this.spId}`);
                    this.listOfReason = resp.data;
                    this.listOfReason.map(val => {
                        this.fields.push(val.list_of_reason);
                    })
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/sp/${this.spId}`, new FormData(this.form))
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
            }
        }
    </script>
@endpush
