@extends('layouts.template')
@section('page-title', 'Dashboard')
@section('breadcrumbs', 'Home')
@section('content')
    <div x-data="dashboard()">
        <div class="row g-5 g-xl-8">
            <div class="col-xl-6">
                <a href="#" class="card bg-dark hoverable card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-body">
                        <h1 class="text-gray-100 fw-bolder" x-text="countTotal.totalEmp"></h1>
                        <div class="text-gray-100 fw-bolder fs-2 mb-2 mt-5">Karyawan Aktif</div>
                        <div class="text-gray-100 fw-bolder">Semua Karyawan Aktif</div>
                    </div>
                </a>
            </div>
            <div class="col-xl-6">
                <a href="#"
                   class="card bg-dark hoverable card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-body">
                        <h1 class="text-gray-100 fw-bolder" x-text="countTotal.totalBranch"></h1>
                        <div class="text-white fw-bolder fs-2 mb-2 mt-5">Cabang</div>
                        <div class="fw-bold text-white">Total Cabang</div>
                    </div>
                </a>
            </div>
        </div>


        <div class="row g-5 g-xl-8">
            <div class="col-xl-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Statistik</h3>
                        <div class="card-toolbar">
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="kt_chartjs_1" class="mh-400px"></canvas>
                    </div>
                </div>


            </div>
        </div>

    </div>
@endsection



@push('script')
    <script>


        const ctx = document.getElementById('kt_chartjs_1');

        const primaryColor = KTUtil.getCssVariableValue('--bs-primary');
        const dangerColor = KTUtil.getCssVariableValue('--bs-danger');
        const successColor = KTUtil.getCssVariableValue('--bs-success');

        const fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');

        const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        const data = {
            labels: labels,
            datasets: []
        };

        // Chart config
        const config = {
            type: 'bar',
            data: data,
            options: {
                plugins: {
                    title: {
                        display: false,
                    }
                },
                responsive: true,
                interaction: {
                    intersect: false,
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                }
            },
            defaults: {
                global: {
                    defaultFont: fontFamily
                }
            }
        };

        const myChart = new Chart(ctx, config);
    </script>
    <script>
        function dashboard() {
            return {
                countTotal: [],
                async init() {
                    const resp = await axios.get('/summary');
                    this.countTotal = resp.data;
                }
            }
        }
    </script>
@endpush
