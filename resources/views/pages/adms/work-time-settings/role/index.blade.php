<div x-data="roleDefaultWorkTimeData()">
    @include('pages.adms.work-time-settings.role.form')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
            <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                   class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
        </div>
        <div>
            <h4>
                Jam Kerja Bawaan : <span x-text="defaultWorkTime ?? '-'"></span>
            </h4>
        </div>
    </div>
    <div class="py-5">
        <div class="table-responsive">
            <table class="table align-middle table-bordered fs-6 gy-5 table-striped" id="kt_table_users">
                <thead>
                <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Jabatan</th>
                    <th class="min-w-125px">Jam Kerja Jabatan</th>
                </thead>
                <tbody class=" fw-bold text-center">
                <template x-if="isLoading">
                    <tr>
                        <td colspan="4">
                            <div style="text-align: center;">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
                <template x-if="!isLoading && shifts.data?.length === 0">
                    <tr>
                        <td colspan="4">
                            <center>Data Tidak Ditemukan</center>
                        </td>
                    </tr>
                </template>
                <template x-for="(shift, index) in shifts?.data" :key="shift.id">
                    <tr>
                        <td x-text="shift.name"></td>
                        <td>
                            <template x-if="shift.work_time === null">
                                <button class="btn btn-sm btn-light-primary" type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-role-work-time"
                                        :disabled="Number(createPermission) !== 1"
                                        @click="edit(shift.id, null)"
                                >
                                    <x-icons.add-item/>
                                </button>
                            </template>
                            <div class="d-flex align-items-center justify-content-around">
                                <button class="btn btn-link" x-text="shift.work_time"
                                        @click="edit(shift.id, shift.work_time_id)"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-role-work-time"
                                        :disabled="Number(createPermission) !== 1"
                                ></button>
                                <template x-if="shift.work_time !== null">
                                    <button class="btn btn-sm btn-light-danger" type="button" :disabled="Number(resetPermission) !== 1"
                                            @click="resetWorkTime(shift.id,shift.work_time_id)">
                                        <x-icons.close/>
                                        Reset
                                    </button>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end align-items-center mt-4">
            <ul class="pagination">
                <template x-for="pagination in shifts?.links">
                    <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                        <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                x-html="pagination.label">
                        </button>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>


@push('script')
    <script>
        function roleDefaultWorkTimeData() {
            return {
                isLoading: true,
                shifts: [],
                selectedCheckBox: [],
                editVal: '',
                buttonLoading: false,
                selectAll: false,
                singleChecked: false,
                search: '',
                defaultWorkTime: null,
                form: document.getElementById('form-role-work-time'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-role-work-time')),
                createPermission: "{{ request()->user()->can('Tambah Data Jam Kerja Berdasarkan Jabatan') }}",
                resetPermission: "{{ request()->user()->can('Reset Data Jam Kerja Berdasarkan Jabatan') }}",
                async init() {
                    await select2('.roles-select2', 'Pilih Jabatan', '/select2/roles-data');
                    await select2('.work-times-select2', 'Pilih Jam Kerja', '/select2/work-times-data');
                    await this.getShiftsData();
                    await this.getDefaultWorkTime();
                },
                async getShiftsData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/adms/work-time-settings/role/data');
                        this.shifts = resp.data;
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/adms/work-time-settings/role/search', {
                            params: {
                                search: this.search
                            }
                        });
                        this.shifts = resp.data;
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getDefaultWorkTime() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/operational/work-time/show-default-work-time');
                        this.defaultWorkTime = resp.data;
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/adms/work-time-settings/role/store', new FormData(this.form));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.form.reset();
                        this.modalForm.hide();
                        await this.init();
                    } catch (error) {

                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(roleId, workTimeId = null) {
                    try {
                        await selectedValue('selected-role', `/select2/selected-role/${roleId}`);
                        await selectedValue('selected-role-default-work-time', `/select2/selected-work-time/${workTimeId}`);
                        const resp = await axios.get(`/adms/work-time-settings/role/show/${roleId}/${workTimeId}`);
                        this.editVal = resp.data;
                    } catch (error) {
                        console.log(error)
                    }
                },
                async paginationEndPoint(url) {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(url);
                        this.shifts = resp.data;
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async resetWorkTime(roleId, workTimeId) {
                    showConfirmModal("Anda yakin?", "Data akan direset.", "Ya, Reset!", async () => {
                        try {
                            await axios.delete(`/adms/work-time-settings/role/reset/${roleId}/${workTimeId}`);
                            await showAlert('success', 'Data sukses direset');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush
