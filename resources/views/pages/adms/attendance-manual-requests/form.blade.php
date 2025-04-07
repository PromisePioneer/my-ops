@extends('layouts.template')
@section('page-title', 'Pengajuan Absensi Manual')
@section('breadcrumbs', 'Data Absensi - Riwayat Absensi - Pengajuan Absensi Manual - Tambah')
@push('styles')
    <style>
        img.imgPreview {
            max-width: 250px;
            max-height: 250px;
        }
    </style>
@endpush
@section('content')
    <div x-data="generateAttendanceManualRequestsData()">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6"
                   href="{{ url('adms/attendances-summary/attendance-manual-requests/') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Tanggal</label>
                                <input type="text" name="date"
                                       class="form-control form-control-lg form-control-solid date-picker"
                                       placeholder="Tanggal mulai - selesai"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Karyawan (Bisa Lebih Dari 1)</label>
                                <select name="users[]" id="selected-users"
                                        class="form-select form-select-solid users-select2" multiple>
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-form-label required fw-bold fs-6">Alasan</label>
                            <textarea name="reason" id="reason" class="form-control form-control-solid"
                                      data-kt-autosize="true">{{ $attendanceManualRequest->reason ?? '' }}</textarea>
                        </div>


                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Lampiran</label>
                                <input type="file" class="form-control form-control-solid" @change="previewFile"
                                       accept="image/*" x-ref="myFile" name="attachment[]" id="attachment" multiple>
                                <span
                                    class="text-danger">Wajib ada timestamp!, tidak ada timestamp pengajuan akan ditolak!</span>
                            </div>
                            @if(isset($attendanceManualRequest->id))
                                <div class="row col-md-6">
                                    <label class="col-form-label required fw-bold fs-6">Lampiran Sebelumnya</label>
                                    <template x-for="attachment in attachments">
                                        <div class="col-md-4">
                                            <img
                                                :src="getImageURL(attachment.attachment)"
                                                class="img-fluid"
                                                alt="" @click="removeAttachmentFromRemoteData(attachment.id)">
                                        </div>
                                    </template>
                                </div>
                            @else
                                <div class="row col-md-6">
                                    <label
                                        :class="`${imgsrc.length > 0 ? 'col-form-label required fw-bold fs-6' : 'd-none'}`">
                                    </label>
                                    <template x-for="(src, index) in imgsrc" :key="index">
                                        <div class="col-md-4">
                                            <img :src="src" class="img-fluid" @click="removeImage(index)">
                                        </div>
                                    </template>
                                </div>
                            @endif
                            @if(isset($attendanceManualRequest->id))
                                <div class="row col-md-6">
                                    <label
                                        :class="`${imgsrc.length > 0 ? 'col-form-label required fw-bold fs-6' : 'd-none'}`">
                                        Preview (Klik Untuk Menghapus)
                                    </label>
                                    <template x-for="(src, index) in imgsrc" :key="index">
                                        <div class="col-md-4">
                                            <img :src="src" class="img-fluid" @click="removeImage(index)">
                                        </div>
                                    </template>
                                </div>
                            @endif
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
    <script>

        const startDate = "{{ $attendanceManualRequest->start_date ?? '' }}";
        const endDate = "{{ $attendanceManualRequest->end_date ?? '' }}";

        $(document).ready(function () {
            flatpickr(".date-picker", {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: [`${startDate}`, `${endDate}`],
            });
        });

        function generateAttendanceManualRequestsData() {
            return {
                id: "{{ $attendanceManualRequest->id ?? '' }}",
                workTimeId: "{{ $attendanceManualRequest->work_time_id ?? '' }}",
                buttonLoading: false,
                form: document.getElementById('form'),
                users: [],
                imgsrc: [],
                attachments: [],
                async init() {
                    await this.getUsers();
                    await this.selectedUsers();
                    await this.selectedAttachment();
                },
                async getUsers() {
                    $(".users-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Karyawan",
                        ajax: {
                            url: '/select2/users-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedAttachment() {
                    if (!this.id) return;
                    try {
                        const resp = await axios.get(`/adms/attendances-summary/attendance-manual-requests/selected-attachments/${this.id}`);
                        this.attachments = resp.data;
                    } catch (e) {
                        console.log(e)
                    }
                },
                previewFile() {
                    let files = this.$refs.myFile.files;
                    if (!files.length) return;

                    Array.from(files).forEach(file => {
                        if (!file.type.startsWith('image/')) return;

                        let reader = new FileReader();
                        reader.onload = e => {
                            this.imgsrc.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    });
                },
                async selectedUsers() {
                    if (!this.id) return;
                    const selectedUser = $('#selected-users');
                    const response = await axios.get(`/adms/attendances-summary/attendance-manual-requests/selected-users/${this.id}`);
                    response.data.forEach(user => {
                        const option = new Option(user.name, user.id, true, true);
                        selectedUser.append(option).trigger('change').trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    })
                },
                async save() {
                    try {
                        this.buttonLoading = true;
                        if (!this.id) {
                            await axios.post('/adms/attendances-summary/attendance-manual-requests/', new FormData(this.form));
                        } else {
                            await axios.post(`/adms/attendances-summary/attendance-manual-requests/update/${this.id}`, new FormData(this.form));
                        }

                        await showAlert('success', 'Data sukses disimpan');
                        window.location.href = '/adms/attendances-summary/attendance-manual-requests';
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                removeImage(index) {
                    const input = document.getElementById('attachment');
                    const fileListArr = Array.from(input.files);
                    fileListArr.splice(index, 1);
                    this.imgsrc.splice(index, 1);
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        return "{{ asset('assets/media/placeholders/ktp.png') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
                async removeAttachmentFromRemoteData(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/adms/attendances-summary/attendance-manual-requests/remove-attachment/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush
