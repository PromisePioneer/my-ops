@extends('layouts.template')
@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
    @endpush

    <div class="d-flex flex-column flex-lg-row" x-data="generateJointClosureData()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="save()">
                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Kode</label>
                            <select name="code_id"
                                    class="form-select form-select-solid code-select2">
                                <option></option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Kabel</label>
                            <select name="fo_cable_id" class="form-select form-select-solid foCable-select2">
                                <option></option>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Wilayah</label>
                            <input type="text" class="form-control form-control-solid" name="region"
                                   placeholder="Wilayah">
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Tanggal Cut Off</label>
                            <input type="date" class="form-control form-control-solid" name="cut_off_date"
                                   placeholder="Tanggal Cut Off">
                        </div>
                    </div>
                    <div class="row mb-10 align-items-center">
                        <div class="col-lg-3">
                            <label for="name" class="required form-label">Latitude</label>
                            <input type="text" id="lat" name="lat" x-model="lat" class="form-control form-control-solid"
                                   placeholder="Lattitude"/>
                        </div>
                        <div class="col-lg-3">
                            <label for="name" class="required form-label">Longitude</label>
                            <input type="text" id="long" name="long" x-model="long"
                                   class="form-control form-control-solid"
                                   placeholder="Longitude"/>
                        </div>
                        <div class="col-lg-3 align-self-end py-2">
                            <button type="button" @click="checkCoords()" class="btn btn-primary btn-sm">Check</button>
                        </div>
                    </div>

                    <div id="map" style="z-index: 9999; height: 500px; margin-top: 40px"></div>


                    <div class="float-end mt-10">
                        <a href="{{ url('/operational/odp') }}" class="btn btn-sm btn-light">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.toast')

@endsection
@push('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/tokml@0.4.0/tokml.min.js"></script>
    <script>
        $('.date').flatpickr();

        function generateJointClosureData() {
            return {
                isLoading: false,
                buttonLoading: false,
                map: null,
                lat: null,
                long: null,
                polyline: null,
                startingPointLat: null,
                startingPointLong: null,
                endingPointLat: null,
                endingPointLong: null,
                form: document.getElementById('form'),
                async init() {
                    await this.getCodeData();
                    await this.getFoCablesData();
                    this.map = L.map('map').setView([-5.2360628, 112.8290825], 4);
                    this.mapTileLayer(this.map);
                    this.map.invalidateSize();
                },
                async getFoCablesData() {
                    $(".foCable-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Kabel",
                        ajax: {
                            url: '/operational/joint-closures/fo-cables/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/operational/fo-cables', new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan')
                        window.location.href = "/operational/fo-cables";
                        this.form.reset();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                mapTileLayer(map) {
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                    }).addTo(map);
                },
                async checkCoords() {
                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                    }

                    this.map.flyTo(new L.LatLng(this.lat, this.long), 16);
                    this.marker = L.marker(L.latLng(this.lat, this.long), {
                        iconSize: [20, 20]
                    }).addTo(this.map).bindPopup(`<b>Lokasi JC</b>`).openPopup();

                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        allowClear: true,
                        placeholder: 'Pillih Cabang',
                        ajax: {
                            url: '/operational/fo-cables/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getCodeData() {
                    $(".code-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Kode Area",
                        ajax: {
                            url: '/operational/joint-closures/code/data',
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