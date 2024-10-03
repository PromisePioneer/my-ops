@extends('layouts.template')
@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
        <style>
            #map {
                height: 1000px;
                width: 100%;
            }
        </style>
    @endpush

    @include('pages.master.odp.header')

    <div class="d-flex flex-column flex-xl-row" x-data="odpMap()">
        <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h2 class="mb-0">Data Karyawan</h2>
                    </div>
                </div>
                <form id="form-filter" @submit.prevent="filter()">
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column text-gray-600">
                            {{--                            <div class="d-flex align-items-center py-2">--}}
                            {{--                                <select class="form-select form-select-solid branch-select2"--}}
                            {{--                                        name="branch_id" id="branch_id">--}}
                            {{--                                </select>--}}
                            {{--                            </div>--}}
                            <div class="d-flex align-items-center py-2">
                                <input type="number" name="year" id="year" class="form-control form-control-solid"
                                       placeholder="Filter Berdasarkan Tahun">
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <select class="form-select form-select-solid"
                                        name="month" id="month" data-control="select2"
                                        data-placeholder="Pilih Bulan">
                                    <option></option>
                                    <template x-for="month in months" :key="index">
                                        <option :value="month.number" x-text="month.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pt-4 text-end">
                        <button type="submit" class="btn btn-light btn-active-primary btn-sm">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex-lg-row-fluid ms-lg-10">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <button type="button" class="btn btn-primary btn-sm">Export</button>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body py-3">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/tokml@0.4.0/tokml.min.js"></script>
    <script>
        $('.date').flatpickr();

        function odpMap() {
            return {
                odpData: [],
                months: [],
                map: null,
                async init() {
                    await this.getODPData();
                    await this.getMonth();

                    if (this.odpData.length > 0) {
                        this.map = L.map('map').setView([this.odpData[0].lat, this.odpData[0].long], 16);
                        this.map.invalidateSize();
                        this.mapTileLayer(this.map);
                        await this.odpMarker(this.map);
                    }
                },
                async getMonth() {
                    this.months.push(
                        {name: "Januari", number: '01'},
                        {name: "Februari", number: '02'},
                        {name: "Maret", number: '3'},
                        {name: "April", number: '04'},
                        {name: "Mei", number: '05'},
                        {name: "Juni", number: '06'},
                        {name: "Juli", number: '07'},
                        {name: "Agustus", number: '08'},
                        {name: "September", number: '09'},
                        {name: "Oktober", number: '10'},
                        {name: "November", number: '11'},
                        {name: "Desember", number: '12'},
                    )
                },
                mapTileLayer(map) {
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);
                },
                async odpMarker(map) {
                    this.odpData.forEach((odp) => {
                        L.marker(L.latLng(odp.lat, odp.long), {
                            iconSize: [20, 20]
                        }).addTo(map).bindPopup(`<b>${odp.name}</b>`).openPopup()
                    });
                },
                async getODPData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/odp-map/data');
                        this.odpData = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    if (this.map) {
                        this.map.remove();
                    }
                    const year = document.getElementById('year')?.value ?? '';
                    const month = document.getElementById('month')?.value ?? '';
                    const active = document.getElementById('active')?.value;
                    this.isLoading = true;
                    try {
                        this.map = null;
                        const resp = await axios.get('/master/odp-map/filter', {
                            params: {
                                month: month,
                                year: year,
                                // branch_id: branch_id,
                            }
                        });
                        this.odpData = resp.data;


                        if (this.odpData.length > 0) {
                            this.map = L.map('map').setView([this.odpData[0].lat, this.odpData[0].long], 16);
                            this.map.invalidateSize();
                            this.mapTileLayer(this.map);
                            await this.odpMarker(this.map);
                        }
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
            }
        }
    </script>
@endpush