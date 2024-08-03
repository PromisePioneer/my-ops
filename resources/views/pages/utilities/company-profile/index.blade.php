@extends('layouts.template')
@section('page-title', 'Data Profil Perusahaan')
@section('content')

    <div class="d-flex flex-column flex-lg-row" x-data="companyProfileData()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card">
                <div class="card-body p-12">
                    <form id="form" @submit.prevent="save()" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Nama Perusahaan
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               name="name" placeholder="input nama perusahaan"
                                               value="{{ $companyProfile->name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">NPWP</label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               name="npwp" placeholder="input NPWP" value="{{ $companyProfile->npwp }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-12">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Alamat Perusahaan
                                    </label>
                                    <div class="mb-5">
                                        <textarea type="text" class="form-control form-control-solid"
                                                  name="address" placeholder="input alamat lengkap perusahaan"
                                                  data-kt-autosize>{{ $companyProfile->address }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Bank
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               name="bank" placeholder="input bank" value="{{ $companyProfile->bank }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Nomor Rekening
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               name="bank_account_number" placeholder="input nomor rekening"
                                               value="{{$companyProfile->bank_account_number}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Atas Nama
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               name="bank_account_name" placeholder="input bank"
                                               value="{{ $companyProfile->bank_account_name }}">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="float-end">
                            <a href="{{ url('transaction/bast') }}" class="btn btn-sm btn-light">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary"
                                    :disabled="buttonLoading"
                                    x-text="buttonLoading ? 'Loading...' : 'Generate Company Profile'">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function companyProfileData() {
            return {
                buttonLoading: false,
                id: {{ $companyProfile->id }},
                form: document.getElementById('form'),
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/utility/company-profile/update/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.reload();
                        });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }

                }
            }
        }
    </script>
@endpush
