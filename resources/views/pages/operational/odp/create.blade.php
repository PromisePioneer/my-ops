@extends('layouts.template')
@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
    @endpush

    <div class="d-flex flex-column flex-lg-row" x-data="generateODP()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="save()">
                    <div class="card-body p-12">
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Area</label>
                                <select name="area_id" id="area_id" class="form-select form-select-solid areas-select2">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Nama</label>
                                <input type="text" id="name" name="name" class="form-control form-control-solid"
                                       placeholder="Nama"/>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Klasifikasi</label>
                                <select name="classification" id="classification"
                                        class="form-select form-select-solid">
                                    <option>Pilih Klasifikasi</option>
                                    <option value="AS">AS</option>
                                    <option value="Turunan">Turunan</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Passive Spliter</label>
                                <select name="passive_splitter" id="passive_splitter"
                                        class="form-select form-select-solid">
                                    <option>Pilih Passive Splitter</option>
                                    <option value="ODP">ODP</option>
                                    <option value="FAT">FAT</option>
                                    <option value="ODU">ODU</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Maks. Kapasitas</label>
                                <input type="number" id="max_capacity" name="max_capacity"
                                       class="form-control form-control-solid"
                                       placeholder="Maks. Kapasitas"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Kapasitas Terpakai</label>
                                <input type="number" id="used_capacity" name="used_capacity"
                                       class="form-control form-control-solid"
                                       placeholder="Kode"/>
                            </div>
                        </div>

                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Tanggal CutOff</label>
                                <input type="date" id="cut_off_date" name="cut_off_date"
                                       class="form-control form-control-solid date"
                                       placeholder="Tanggal Cut Off"/>
                            </div>
                        </div>

                        <div class="row mb-10 align-items-center">
                            <div class="col-lg-3">
                                <label for="name" class="required form-label">Lattitude</label>
                                <input type="text" id="lat" name="lat" x-model="lat"
                                       class="form-control form-control-solid"
                                       placeholder="Lattitude"/>
                            </div>
                            <div class="col-lg-3">
                                <label for="name" class="required form-label">Longitude</label>
                                <input type="text" id="long" name="long" x-model="long"
                                       class="form-control form-control-solid"
                                       placeholder="Longitude"/>
                            </div>
                            <div class="col-lg-3 align-self-end py-2">
                                <button type="button" @click="checkCoords()" class="btn btn-primary btn-sm">Check
                                </button>
                            </div>
                        </div>

                        <div id="map" style="z-index: 9999; height: 500px;"></div>
                    </div>

                    <div class="float-end">
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

        function generateODP() {
            return {
                isLoading: false,
                buttonLoading: false,
                map: null,
                lat: null,
                long: null,
                marker: null,
                form: document.getElementById('form'),
                async init() {
                    await this.getAreaData();
                    this.map = L.map('map').setView([-5.2360628, 112.8290825], 4);
                    this.mapTileLayer(this.map);
                    this.map.invalidateSize();
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/operational/odp', new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan')
                        window.location.href = "/operational/odp";
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
                    }).addTo(this.map).bindPopup(`<b>Lokasi ODP</b>`).openPopup();

                },
                async getAreaData() {
                    $(".areas-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Kode Area",
                        ajax: {
                            url: '/operational/odp/odp-area/data',
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