@extends('layouts.template')
@section('page-title', 'Ubah Bonus Project')
@section('content')
    <div class="d-flex flex-column flex-lg-row" x-data="generateProjectBonus()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="update()">
                    <div class="card-body p-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap me-3">Tanggal Aktif:</div>
                                    <div class="position-relative d-flex align-items-center w-150px">
                                        <input type="date" class="form-control form-control-solid fw-bolder pe-5"
                                               placeholder="Tanggal Aktif" name="date_active" id="dueDate"
                                               value="{{ $projectBonus->date_active }}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="row gx-10 mb-5">
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Pelanggan</label>
                                <div class="mb-5">
                                    <input type="text" class="form-control form-control-solid"
                                           placeholder="Nama Pelanggan" name="customer_name"
                                           id="customer_name" value="{{ $projectBonus->customer_name }}">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mb-20">
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-300px w-475px">Karyawan</th>
                                    <th class="min-w-75px w-75px text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(field,index) in fields" :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                            <select x-model="field.user_id" :id="`selectedUser-${index}`"
                                                    :name="`data[${index}][user_id]`"
                                                    class="form-select form-select-solid users-select2">
                                                <option value="0">Pilih</option>
                                            </select>
                                        </td>
                                        <td class="pt-5 text-end" style='text-align:center; vertical-align:middle'>
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    @click="removeField(index)">
                                                    <span class="svg-icon svg-icon-3">
                                                        <i class="bi bi-trash"></i>
                                                    </span>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                                <tfoot>
                                <tr class="border-top border-top-dashed align-top fs-6 fw-bolder text-gray-700">
                                    <th class="text-primary">
                                        <button type="button" class="btn btn-link py-1" @click="add()">Tambah</button>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 required">BAA</label>
                                    <input class="form-control form-control-solid" type="file" name="baa"
                                           accept="application/pdf"/>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 required">
                                        BAST
                                    </label>
                                    <input class="form-control form-control-solid" type="file"
                                           name="bast" accept="application/pdf"/>
                                </div>
                            </div>
                        </div>
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-bolder text-gray-700 required">Deskripsi Pekerjaan</label>
                            <textarea name="work_description" class="form-control form-control-solid" rows="3"
                                      placeholder="Dekripsi Pekerjaan">{{ $projectBonus->work_description }}</textarea>
                        </div>
                    </div>

                    <div class="float-end">
                        <a href="{{ url('/payroll/setting/benefit/project-bonus') }}" class="btn btn-sm btn-light">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $("#invoiceDate").flatpickr();
        $("#dueDate").flatpickr();

        function generateProjectBonus() {
            return {
                buttonLoading: false,
                form: document.getElementById('form'),
                id: "{{ $projectBonus->id }}",
                userHasBonusProject: [],
                fields: [{
                    user_id: '',
                }],
                async init() {
                    await this.getUserData();
                    await this.getUserHasBonusProject();
                },
                add() {
                    this.$nextTick(() => {
                        this.getUserData();
                    })
                    this.fields.push({
                        user_id: '',
                    });
                },

                async getUserHasBonusProject() {
                    const resp = await axios.get(`/payroll/setting/benefit/project-bonus/user-has-project-bonus/${this.id}`);
                    this.fields = resp.data;

                    try {
                        this.$nextTick(() => {
                            this.fields.forEach((field, index) => {
                                const selectedUser = $(`#selectedUser-${index}`);
                                $.ajax({
                                    type: 'GET',
                                    dataType: "JSON",
                                    url: `/payroll/setting/benefit/project-bonus/user-has-project-bonus/show/${field.user_id}`,
                                }).then(function (response) {
                                    const option = new Option(response.user.name, response.id, true, true);
                                    selectedUser.append(option).trigger('change');
                                    selectedUser.trigger({
                                        type: 'select2:select',
                                        params: {
                                            results: response
                                        }
                                    });
                                    console.log(response)
                                    field.user_id = response.user_id;
                                });

                            });

                        })
                    } catch (e) {
                        console.log(e)
                    }
                },

                async update() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/payroll/setting/benefit/project-bonus/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '{{ url('payroll/setting/benefit/project-bonus/') }}'
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getUserData() {
                    this.$nextTick(() => {
                        $(".users-select2").select2({
                            placeholder: "Pilih Karyawan",
                            ajax: {
                                url: '/payroll/setting/benefit/project-bonus/user/data',
                                dataType: "json",
                                type: "GET",
                                data: params => ({search: params.term}),
                                processResults: data => ({results: data}),
                                cache: true
                            }
                        });
                    })
                },
                removeField(index) {
                    if (this.fields.length > 1) {
                        this.fields.splice(index, 1);
                    }
                },
            }
        }
    </script>
@endpush
