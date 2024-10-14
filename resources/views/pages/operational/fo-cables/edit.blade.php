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
                                <label for="name" class="required form-label">Segmen</label>
                                <input type="text" class="form-control form-control-solid" name="segment_id"
                                       id="segment_id" placeholder="Segmen" value="{{ $FOCable->segment_id }}">
                            </div>

                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Cabang</label>
                                <select name="branch_id" id="selectedBranch"
                                        class="form-select form-select-solid branch-select2">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Letak Kabel</label>
                                <select name="cable_placement" id="cable_placement"
                                        class="form-select form-select-solid">
                                    <option value="Udara" {{ $FOCable->cable_placement === 'Udara' ? 'selected' : '' }}>
                                        Udara
                                    </option>
                                    <option value="Underground" {{ $FOCable->cable_placement === 'Underground' ? 'selected' : '' }}>
                                        Darat
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Panjang Kabel</label>
                                <input type="number" class="form-control form-control-solid" name="length"
                                       id="length" placeholder="Panjang Kabel" value="{{ $FOCable->length }}">
                            </div>
                        </div>


                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Jumlah Core</label>
                                <input type="text" class="form-control form-control-solid" name="total_core"
                                       placeholder="Jumlah Core" value="{{ $FOCable->total_core }}">
                            </div>


                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Jumlah Core Terpakai</label>
                                <input type="text" class="form-control form-control-solid" name="used_core"
                                       placeholder="Jumlah Core Terpakai" value="{{ $FOCable->used_core }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Klasifikasi</label>
                                <select name="classification" id="classification" class="form-select form-select-solid">
                                    <option value="Backbone" {{ $FOCable->classification === 'Backbone' ? 'selected' : '' }}>
                                        Backbone
                                    </option>
                                    <option value="Backhaul" {{ $FOCable->classification === 'Backhaul' ? 'selected' : '' }}>
                                        Backhaul
                                    </option>
                                    <option value="Fronthaul" {{ $FOCable->classification === 'Fronthaul' ? 'selected' : '' }}>
                                        Fronthaul
                                    </option>
                                    <option value="Akses" {{ $FOCable->classification === 'Akses' ? 'selected' : '' }}>
                                        Akses
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-6 mb-10">
                                <label for="name" class="required form-label">Tanggal Cutoff</label>
                                <input type="date" class="form-control form-control-solid date" name="cut_off_date"
                                       id="cut_off_date" value="{{ $FOCable->cut_off_date }}"
                                       placeholder="Tanggal CutOff">
                            </div>
                        </div>


                        <div class="col-lg-6 mb-10">
                            <label for="name" class="required form-label">Jalur Kabel</label>
                            <input type="text" class="form-control form-control-solid" name="cable_address"
                                   placeholder="Jalur Kabel" value="{{ $FOCable->cable_address }}">
                        </div>


                        <div class="row mb-10 align-items-center">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Latitude (Titik Awal)</label>
                                <input type="text" id="starting_point_lat" name="starting_point_lat"
                                       class="form-control form-control-solid" x-model="startingPointLat"
                                       placeholder="Latitude (Titik Awal)"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Longitude (Titik Awal)</label>
                                <input type="text" id="starting_point_long" name="starting_point_long"
                                       class="form-control form-control-solid"
                                       placeholder="Longitude (Titik Akhir)" x-model="startingPointLong"/>
                            </div>
                        </div>

                        <div class="row mb-10 align-items-center">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Latitude (Titik Akhir)</label>
                                <input type="text" id="ending_point_lat" name="ending_point_lat"
                                       class="form-control form-control-solid" x-model="endingPointLat"
                                       placeholder="Latitude (Titik Awal)"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Longitude (Titik Akhir)</label>
                                <input type="text" id="ending_point_long" name="ending_point_long"
                                       class="form-control form-control-solid"
                                       placeholder="Longitude (Titik Akhir)" x-model="endingPointLong"
                                />
                            </div>
                        </div>

                        <div class="float-end row mb-4">
                            <button type="button" @click="checkCoords()" class="btn btn-primary btn-sm">Check</button>
                        </div>


                        <div id="map" style="z-index: 9999; height: 500px; margin-top: 90px"></div>
                    </div>

                    <div class="float-end">
                        <a href="{{ url('/operational/fo-cables') }}" class="btn btn-sm btn-light">Cancel</a>
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
                id: "{{ $FOCable->id }}",
                polyline: null,
                startingPointLat: "{{ $FOCable->starting_point_lat }}",
                startingPointLong: "{{ $FOCable->starting_point_long }}",
                endingPointLat: "{{ $FOCable->ending_point_lat }}",
                endingPointLong: "{{ $FOCable->ending_point_long }}",
                form: document.getElementById('form'),
                async init() {
                    await this.selectedBranchData();
                    await this.getBranchData();
                    this.map = L.map('map').setView([-5.2360628, 112.8290825], 4);
                    this.mapTileLayer(this.map);
                    this.map.invalidateSize();
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
                    const latlngs = [[this.startingPointLat, this.startingPointLong], [this.endingPointLat, this.endingPointLong]];

                    if (this.polyline) {
                        this.map.removeLayer(this.polyline);
                    }

                    this.polyline = L.polyline(latlngs, {color: 'red'}).addTo(this.map);
                    this.map.fitBounds(this.polyline.getBounds());
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
                async selectedBranchData() {
                    const selectedBranch = $('#selectedBranch');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/operational/fo-cables/branch/selected/${this.id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedBranch.append(option).trigger('change');

                        selectedBranch.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    });
                }
            }
        }
    </script>
@endpush