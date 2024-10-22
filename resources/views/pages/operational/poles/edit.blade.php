@extends('layouts.template')
@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
    @endpush

    <div class="d-flex flex-column flex-lg-row" x-data="generatePoles()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="save()">
                    <div class="card-body p-12">

                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="diameter" class="required form-label">Cabang</label>
                                <select name="branch_id" id="selectedBranch"
                                        class="form-select form-select-solid branches-select2">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="diameter" class="required form-label">Diameter</label>
                                <input type="text" id="diameter" name="diameter" class="form-control form-control-solid"
                                       placeholder="Diameter" value="{{ $pole->diameter }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="length" class="required form-label">Panjang</label>
                                <input type="text" id="length" name="length" class="form-control form-control-solid"
                                       placeholder="Panjang" value="{{ $pole->length }}"/>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="region" class="required form-label">Wilayah</label>
                                <input type="text" id="region" name="region" class="form-control form-control-solid"
                                       placeholder="Wilayah" value="{{ $pole->region }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Kode Tiang</label>
                                <input type="text" id="code" name="code" class="form-control form-control-solid"
                                       placeholder="Kode Tiang" value="{{ $pole->code }}"/>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Tanggal Cut Off</label>
                                <input type="date" id="cut_off_date" name="cut_off_date"
                                       class="form-control form-control-solid"
                                       placeholder="Kode Tiang" value="{{ $pole->cut_off_date }}"/>
                            </div>
                        </div>
                        <div class="row mb-10 align-items-center">
                            <div class="col-lg-3">
                                <label for="name" class="required form-label">Lattitude</label>
                                <input type="text" id="lat" name="lat" class="form-control form-control-solid"
                                       x-model="lat"
                                       placeholder="Lattitude"/>
                            </div>
                            <div class="col-lg-3">
                                <label for="name" class="required form-label">Longitude</label>
                                <input type="text" id="long" name="long" class="form-control form-control-solid"
                                       placeholder="Longitude" x-model="long"/>
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

        function generatePoles() {
            return {
                isLoading: false,
                buttonLoading: false,
                map: null,
                lat: "{{ $pole->lat }}",
                long: "{{ $pole->long }}",
                marker: null,
                form: document.getElementById('form'),
                id: "{{ $pole->id }}",
                async init() {
                    await this.selectedBranch();
                    await this.getBranchData();
                    this.map = L.map('map').setView([-5.2360628, 112.8290825], 4);
                    this.mapTileLayer(this.map);
                    this.map.invalidateSize();
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/operational/poles/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan')
                        window.location.href = "/operational/poles";
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
                    }).addTo(this.map).bindPopup(`<b>Lokasi Tiang</b>`).openPopup();

                },
                async getBranchData() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/operational/poles/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedBranch() {
                    const selectedBranch = $('#selectedBranch');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/operational/poles/branch/selected/${this.id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedBranch.append(option).trigger('change');

                        selectedBranch.trigger({
                            type: 'select2:select',
                            params: {
                                results: response
                            }
                        });
                    });
                },
            }
        }
    </script>
@endpush