@extends('layouts.template')
@section('page-title', 'Data KOP Surat')
@section('content')

    <div class="d-flex flex-column flex-lg-row" x-data="letterHeadData">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card">
                <div class="card-body p-12">
                    <form id="form" @submit.prevent="save()" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Kop
                                        Header</label>
                                    <div class="mb-5">
                                        <input type="file" class="form-control form-control-solid"
                                               name="header" accept=".jpg,.png,.jpeg">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Kop
                                        Footer</label>
                                    <div class="mb-5">
                                        <input type="file" class="form-control form-control-solid"
                                               name="footer" accept=".jpg,.png,.jpeg">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Kop Header Sebelumnya
                                    </label>
                                    <div class="mb-5">
                                        @if(isset($letterHead->header))
                                            <img class="img-fluid" src="{{ Storage::url($letterHead->header) }}" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Kop Footer Sebelumnya
                                    </label>
                                    <div class="mb-5">
                                        @if(isset($letterHead->footer))
                                            <img class="img-fluid" src="{{ Storage::url($letterHead->footer) }}" alt="">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="float-end">
                            <button type="submit" class="btn btn-sm btn-light-primary"
                                    :disabled="buttonLoading">
                                <i class="ki-duotone ki-click fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                                <span x-text="buttonLoading ? 'Loading...' : 'Generate Kop Surat'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function letterHeadData() {
            return {
                id: "{{ $letterHead->id ?? null }}",
                buttonLoading: false,
                form: document.getElementById('form'),
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/utility/letter-head/update/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            location.reload();
                        })
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
