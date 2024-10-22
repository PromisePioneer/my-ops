@extends('layouts.template')
@section('page-title', 'Struktur Organisasi')
@section('content')
    <div class="card card-xl-stretch mb-5 mb-xl-8" x-data="organizationChart()">
        <div class="card-body py-14">
            <div class="d-flex align-items-center justify-content-center mb-10">
                <a href="#" class="card bg-dark hoverable mb-5 w-240px">
                    <div class="card-body">
                        <div class="symbol symbol-50px">
                            <img :src="getImageURL(data?.kacab?.profile_pic ?? null)"
                                 @click="$dispatch('lightbox', `${getImageURL(data?.kacab?.profile_pic)}`)"
                                 height="100"/>
                        </div>
                        <div class="text-gray-100 fw-bolder fs-2 mb-2 mt-5" x-text="data.kacab?.name"></div>
                        <div class="fw-bold text-gray-100" x-text="data?.kacab?.roles[0].name"></div>
                    </div>
                </a>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="d-flex bg-dark justify-content-around pt-4 py-1 align-items-center">
                    <p class="text-white fs-1 fw-boldest">KCA</p>
                    <p class="text-white fs-1 fw-boldest">WKCA</p>
                </div>
            </div>
            <div class="row justify-content-around gap-5">
                <div class="col-lg-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">Foto</th>
                                <th class="min-w-125px">NIP</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jabatan</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && data.kca.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(kca, index) in data.kca" :key="index">
                                <tr>
                                    <td>
                                        <div class="symbol-label">
                                            <img :src="getImageURL(kca.profile_pic ?? null)"
                                                 @click="$dispatch('lightbox', `${getImageURL(kca.profile_pic ?? null)}`)"
                                                 class="w-100"/>
                                        </div>
                                    </td>
                                    <td x-text="kca.nip"></td>
                                    <td x-text="kca.name"></td>
                                    <td x-text="kca.roles[0].name"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">Foto</th>
                                <th class="min-w-125px">NIP</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jabatan</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && data.wkca.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(wkca, index) in data.wkca" :key="index">
                                <tr>
                                    <td>
                                        <div class="symbol-label">
                                            <img :src="getImageURL(wkca.profile_pic ?? null)"
                                                 @click="$dispatch('lightbox', `${getImageURL(wkca.profile_pic ?? null)}`)"
                                                 class="w-100"/>
                                        </div>
                                    </td>
                                    <td x-text="wkca.nip"></td>
                                    <td x-text="wkca.name"></td>
                                    <td x-text="wkca.roles[0].name"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="d-flex bg-dark justify-content-around pt-4 py-1 align-items-center">
                    <p class="text-white fs-1 fw-boldest">Front Office</p>
                    <p class="text-white fs-1 fw-boldest">Back Office</p>
                </div>
            </div>
            <div class="row justify-content-around gap-3">
                <div class="col-lg-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">Foto</th>
                                <th class="min-w-125px">NIP</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jabatan</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && data.frontOfficeUser.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(frontOffice, index) in data.frontOfficeUser" :key="index">
                                <tr>
                                    <td>
                                        <div class="symbol-label">
                                            <img :src="getImageURL(frontOffice.profile_pic ?? null)"
                                                 @click="$dispatch('lightbox', `${getImageURL(frontOffice.profile_pic ?? null)}`)"
                                                 class="w-100"/>
                                        </div>
                                    </td>
                                    <td x-text="frontOffice.nip"></td>
                                    <td x-text="frontOffice.name"></td>
                                    <td x-text="frontOffice.roles[0].name"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">Foto</th>
                                <th class="min-w-125px">NIP</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jabatan</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && data.backOfficeUser.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(backOffice, index) in data.backOfficeUser" :key="index">
                                <tr>
                                    <td>
                                        <div class="symbol-label">
                                            <img :src="getImageURL(backOffice.profile_pic ?? null)"
                                                 @click="$dispatch('lightbox', `${getImageURL(backOffice.profile_pic ?? null)}`)"
                                                 class="w-100"/>
                                        </div>
                                    </td>
                                    <td x-text="backOffice.nip"></td>
                                    <td x-text="backOffice.name"></td>
                                    <td x-text="backOffice.roles[0].name"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="d-flex bg-dark justify-content-around pt-4 py-1 align-items-center">
                    <p class="text-white fs-1 fw-boldest">Petugas Lapangan</p>
                </div>
            </div>
            <div class="row justify-content-around gap-3">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">Foto</th>
                            <th class="min-w-125px">NIP</th>
                            <th class="min-w-125px">Nama</th>
                            <th class="min-w-125px">Jabatan</th>
                        </thead>
                        <tbody class="text-gray-600 fw-bold">
                        <template x-if="isLoading">
                            <tr>
                                <td colspan="9">
                                    <div style="text-align: center;">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="!isLoading && data.frontOfficeUser.length === 0">
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                        </template>
                        <template x-for="(fieldWorker, index) in data.fieldWorkerUser" :key="index">
                            <tr>
                                <td>
                                    <div class="symbol-label">
                                        <img :src="getImageURL(fieldWorker.profile_pic ?? null)"
                                             @click="$dispatch('lightbox', `${getImageURL(fieldWorker.profile_pic ?? null)}`)"
                                             class="w-100"/>
                                    </div>
                                </td>
                                <td x-text="fieldWorker.nip"></td>
                                <td x-text="fieldWorker.name"></td>
                                <td x-text="fieldWorker.roles[0].name"></td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function organizationChart() {
            return {
                isLoading: false,
                data: [],
                branchId: "{{ $branch->id }}",
                async init() {
                    await this.getUser();
                },
                async getUser() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/master/branch/structure-orgranization/data/${this.branchId}`)
                        this.data = resp.data;
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = '/assets/media/dummy/dummy-picture.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
            }
        }
    </script>
@endpush
