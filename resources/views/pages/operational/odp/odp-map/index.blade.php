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

    @include('pages.operational.odp.header')

    <div class="d-flex flex-column flex-xl-row" x-data="odpMap()">
        <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h2 class="mb-0">Filter</h2>
                    </div>
                </div>
                <form id="form-filter" @submit.prevent="filter()">
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column text-gray-600">
                            {{--                                                        <div class="d-flex align-items-center py-2">--}}
                            {{--                                                            <select class="form-select form-select-solid branch-select2"--}}
                            {{--                                                                    name="branch_id" id="branch_id">--}}
                            {{--                                                            </select>--}}
                            {{--                                                        </div>--}}
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
                        <a @click="download()" id="export" class="btn btn-primary btn-sm">Export</a>
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
    <script src="{{ url('assets/plugins/custom/leaflet/leaflet.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tokml@0.4.0/tokml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>


    <script>
        const x = document.getElementById("demo");
    </script>
    <script>
        $('.date').flatpickr();

        function odpMap() {
            return {
                odpData: [],
                months: [],
                map: null,
                coords: [],
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
                getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(this.showPosition);
                    } else {
                        x.innerHTML = "Geolocation is not supported by this browser.";
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
                            iconUrl: 'https://cdn4.iconfinder.com/data/icons/small-n-flat/24/map-marker-512.png',
                            iconSize: [20, 20]
                        }).addTo(map).bindPopup(
                            `<b> ${odp.name} </b>
                            <br>
                            Tanggal CutOff <b>${odp.cut_off_date}</b> <br>
                            `
                        ).openPopup()
                    });
                },
                async getODPData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/operational/odp-map/data');
                        this.odpData = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                download() {

                    const geojsonFeature = {
                        "type": "FeatureCollection",
                        "features": this.odpData.map(odp => {
                            return {
                                "type": "Feature",
                                "properties": {
                                    "name": odp.name,
                                },
                                "geometry": {
                                    "type": "Point",
                                    "coordinates": [odp.long, odp.lat]
                                }
                            };
                        })
                    };

                    const geoJsonLayer = L.geoJSON(geojsonFeature);

                    const kml = tokml(geoJsonLayer.toGeoJSON());

                    const zip = new JSZip();
                    zip.file("doc.kml", kml);

                    zip.generateAsync({type: "blob"})
                        .then(function (content) {
                            const link = document.createElement('a');
                            link.href = URL.createObjectURL(content);
                            link.download = 'map_data.kmz';
                            link.click();
                        });

                },
                async filter() {
                    if (this.map) {
                        this.map.remove();
                    }
                    const year = document.getElementById('year')?.value ?? '';
                    const month = document.getElementById('month')?.value ?? '';
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/operational/odp-map/filter', {
                            params: {
                                month: month,
                                year: year,
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