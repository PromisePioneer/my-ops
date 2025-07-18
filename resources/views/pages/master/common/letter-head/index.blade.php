@extends('layouts.template')
@section('page-title', 'Kop Surat')
@section('breadcrumbs', 'Master Umum - Kop Surat')
@section('content')
    <div x-data="letterHeadData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.common.letter-head.form')
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            @can('Tambah Data Paket Broadband')
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-letter-head" @click="add()">
                                    <x-icons.add-item/>
                                    Tambah
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
                    <form id="deleteForm" @submit.prevent="destroy()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <x-icons.trash/>
                            Hapus
                        </button>
                    </form>

                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-bordered">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()"
                                               :disabled="Number(deletePermission) !== 1">
                                    </div>
                                </th>
                                <th class="min-w-50">Header</th>
                                <th class="min-w-50">Footer</th>
                                <template x-if="Number(editPermission) === 1">
                                    <th class="min-w-125px">Actions</th>
                                </template>
                            </thead>
                            <template x-if="isLoading">
                                <x-table.loading colspan="5"/>
                            </template>
                            <template x-if="!isLoading && letterHead.data?.length === 0">
                                <x-table.empty colspan="5"/>
                            </template>
                            <template x-for="letter in letterHead?.data" :key="letter.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="letter.id"
                                                   :id="'checkbox-' + letter.id"
                                                   :disabled="Number(deletePermission) !== 1"/>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#">
                                            <div class="symbol-label">
                                                <a href="#" @click="openImage(letter.header)">
                                                    <img :src="getImageURL(letter.header ?? null)"
                                                         alt="Image" class="w-50 img-thumbnail">
                                                </a>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#">
                                            <div class="symbol-label">
                                                <a href="#" @click="openImage(letter.footer)">
                                                    <img :src="getImageURL(letter.footer ?? null)"
                                                         alt="Image" class="w-50 img-thumbnail">
                                                </a>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <template x-if="Number(editPermission) === 1">
                                            <button class="btn btn-light-primary btn-sm mb-4" data-bs-toggle="modal"
                                                    data-bs-target="#modal-letter-head"
                                                    @click="edit(letter.id)">
                                                <x-icons.edit/>
                                            </button>
                                        </template>
                                        <template x-if="letter.is_active === false">
                                            <button class="btn btn-light-info btn-sm"
                                                    @click="setActive(letter.id)">
                                                <x-icons.confirm/>
                                            </button>
                                        </template>
                                        <template x-if="letter.is_active === true">
                                            <span class="text-danger fw-bolder">
                                                (Sedang Aktif)
                                            </span>
                                        </template>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in letterHead.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="paginate(pagination.url)"
                                        x-html="pagination.label">
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.input-mask')
@endsection
@push('script')
    <script>
        function letterHeadData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data Kop Surat') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Kop Surat') }}",
                letterHead: [],
                isLoading: false,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                form: document.getElementById('form-letter-head'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-letter-head')),
                deleteForm: document.getElementById('deleteForm'),
                formFilter: document.getElementById('form-filter'),
                async init() {
                    await this.getBroadbandPacketData();
                    inputMask('price', 'decimal')
                },
                async getBroadbandPacketData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/common/letter-head/data');
                        this.letterHead = resp.data
                        this.startIndex = this.letterHead.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;

                    }
                },
                add() {
                    this.editVal = null;
                },
                async paginate(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.letterHead = resp.data
                    }
                },
                async save(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/master/common/letter-head/store', new FormData(this.form))
                        } else {
                            await axios.post(`/master/common/letter-head/update/${id}`, new FormData(this.form))
                        }

                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        this.modalForm.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/master/common/letter-head/edit/${id}`);
                    this.editVal = resp.data;
                },
                toggleAllCheckBox() {
                    if (Number(this.deletePermission) === 1) {
                        this.selectAll = !this.selectAll;
                        this.singleChecked = false;
                        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                        this.selectedCheckBox = [];
                        checkboxes.forEach((checkbox) => {
                            checkbox.checked = this.selectAll;
                            if (this.selectAll) {
                                this.selectedCheckBox.push(checkbox.value);
                            }
                        });
                        this.selectedCheckBox.shift();
                    }
                },
                selectCheckBox(event) {
                    const checkboxId = event.target.value;
                    if (event.target.checked) {
                        this.selectedCheckBox.push(checkboxId);
                    } else {
                        const index = this.selectedCheckBox.indexOf(checkboxId);
                        if (index !== -1) {
                            this.selectedCheckBox.splice(index, 1);
                        }
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/common/letter-head/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
                async setActive(id) {
                    showConfirmModal("Anda yakin?", "Kop Surat yang aktif akan digunakan di semua menu yang membutuhkan kop surat.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/master/common/letter-head/set-active/${id}`);
                            await showAlert('success', 'Data sukses Dikunci');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                openImage(imagePath) {
                    const lightbox = new FsLightbox();
                    console.log(lightbox);
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        const image = "{{ asset('') }}" + placeholders
                        lightbox.props.sources = [image, image];
                        lightbox.open();
                    } else {
                        const image = "{{ Storage::url('') }}" + imagePath;
                        lightbox.props.sources = [image];
                        lightbox.open();
                    }
                },
            }
        }
    </script>

@endpush
