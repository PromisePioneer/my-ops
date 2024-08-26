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
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tanggal awal :</div>
                                    <div class="position-relative d-flex align-items-center w-150px">
                                        <input type="date" class="form-control form-control-white fw-bolder pe-5 date"
                                               placeholder="Tanggal awal" name="start_date" id="start_date"
                                               value="{{ $sp->start_date }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tanggal akhir :</div>
                                    <div class="position-relative d-flex align-items-center w-150px">
                                        <input type="date" class="form-control form-control-white fw-bolder pe-5 date"
                                               placeholder="Tanggal akhir" name="end_date" id="end_date"
                                               value="{{ $sp->end_date }}"/>
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
                                            data-placeholder="Select an option" id="selectedUser">
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
                                            <option value="0" selected>Pilih</option>
                                            <option value="SP-1" {{ $sp->sp_type === 'SP-1' ? 'selected' : '' }}>SP-1
                                            </option>
                                            <option value="SP-2" {{ $sp->sp_type === 'SP-2' ? 'selected' : '' }}>SP-2
                                            </option>
                                            <option value="SP-3" {{ $sp->sp_type === 'SP-3' ? 'selected' : '' }}>SP-3
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 required">Alasan SP</label>
                                    <input class="form-control form-control-solid" type="text" name="reason"
                                           placeholder="Alasan" value="{{ $sp->reason }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-bolder text-gray-700">Catatan</label>
                            <textarea name="description" id="description" class="form-control form-control-solid"
                                      rows="3"
                                      placeholder="Thanks for your business">{{ $sp->description }}</textarea>
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
        tinymce.init({
            selector: 'textarea#description',
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
            editimage_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,
            image_class_list: [
                {title: 'None', value: ''},
                {title: 'Some class', value: 'class-name'}
            ],
            importcss_append: true,
            height: 600,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image table',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });

        function generateSP() {
            return {
                spId: '{{ $sp->id  }}',
                form: document.getElementById('form'),
                buttonLoading: false,
                async init() {
                    await this.getUserData();
                    await this.selectedUserData();
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
                async selectedUserData() {
                    const selectedUser = $('#selectedUser');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/manage-users/sp/users/data/selected/${this.spId}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedUser.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
            }
        }
    </script>
@endpush