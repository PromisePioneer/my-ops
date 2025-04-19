<script>
    function areaData() {
        return {
            editPermission: "{{ request()->user()->can('Edit Data Area') }}",
            deletePermission: "{{ request()->user()->can('Hapus Data Area') }}",
            isLoading: false,
            areas: [],
            search: '',
            selectedCheckBox: [],
            selectAll: false,
            singleChecked: false,
            modalForm: new bootstrap.Modal(document.getElementById('modal-area')),
            form: document.getElementById('form-area'),
            formDelete: document.getElementById('form-delete'),
            buttonLoading: false,
            editVal: '',
            async init() {
                await this.getMainBranches();
                await this.getArea();
                await this.getDepartments();
            },
            add() {
                this.editVal = '';
            },
            async getArea() {
                const resp = await axios.get('/master/common/area/data')
                this.areas = resp.data;
            },
            async searchData() {
                try {
                    const branchId = $('#branch_id_filter').val();
                    const resp = await axios.get(`/master/common/area/search/`, {
                        params: {
                            search: this.search,
                            branch_id: branchId
                        },
                        headers: {'Content-Type': 'application/json'}
                    });
                    this.areas = resp.data;
                } catch (error) {
                    console.log(error);
                }
            },
            async filter() {
                this.isLoading = true;
                try {
                    const branchId = $('#branch_id_filter').val();
                    const resp = await axios.get('/master/common/area/filter', {
                        params: {
                            branch_id: branchId,
                        }
                    });
                    this.areas = resp.data;
                } catch (e) {
                    console.log(e)
                } finally {
                    this.isLoading = false;
                }
            },
            async paginate(url) {
                if (url) {
                    const branchId = $('#branch_id_filter').val();
                    const resp = await axios.get(`${url}`, {
                        params: {
                            branch_id: branchId
                        }
                    });
                    this.areas = resp.data
                }
            },
            async save(id = null) {
                this.buttonLoading = true;
                try {
                    if (!id) {
                        await axios.post('/master/common/area/', new FormData(this.form))
                    } else {
                        await axios.post(`/master/common/area/${id}`, new FormData(this.form))
                    }
                    await showAlert('success', 'Data berhasil disimpan')
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
            async edit(id) {
                const resp = await axios.get(`/master/common/area/${id}`);
                this.editVal = resp.data;
                await this.getMainBranches();
                await this.selectedBranch();
                await this.selectedDepartment();
            },
            async getDepartments() {
                $(".departments-select2").select2({
                    placeholder: 'Pilih Departemen',
                    allowClear: true,
                    ajax: {
                        url: '/select2/departments-data',
                        dataType: "json",
                        type: "GET",
                        data: params => ({search: params.term}),
                        processResults: data => ({results: data}),
                        cache: true
                    }
                });
            },
            async selectedDepartment() {
                const selectedDepartment = $('#selected-department');
                const response = await $.ajax({
                    type: 'GET',
                    dataType: "JSON",
                    url: `/select2/selected-department/${this.editVal.department_id}`,
                });
                const option = new Option(response.name, response.id, true, true);
                selectedDepartment.append(option).trigger('change').trigger({
                    type: 'select2:select',
                    params: {results: response}
                });
            },
            async getMainBranches() {
                $(".main-branches-select2").select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    ajax: {
                        url: '/select2/main-branches-data',
                        dataType: "json",
                        type: "GET",
                        data: params => ({search: params.term}),
                        processResults: data => ({results: data}),
                        cache: true
                    }
                });
            },
            async selectedBranch() {
                const selectedBranch = $('#selected-branch');
                const response = await $.ajax({
                    type: 'GET',
                    dataType: "JSON",
                    url: `/select2/selected-branch/${this.editVal.branch_id}`,
                });
                const option = new Option(response.name, response.id, true, true);
                selectedBranch.append(option).trigger('change').trigger({
                    type: 'select2:select',
                    params: {results: response}
                });
            },
            toggleAllCheckBox() {
                if (Number(this.deletePermission) === 1) {
                    this.selectAll = !this.selectAll;
                    this.singleChecked = false;
                    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    this.selectedCheckBox = [];
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = this.selectAll;
                        if (this.selectAll) {
                            this.selectedCheckBox.push(checkbox.value);
                        }
                    });
                    this.selectedCheckBox.shift();
                }
            },
            selectCheckBox(event) {
                const checkboxId = event.target.value;
                if (event.target.checked) {
                    this.selectedCheckBox.push(checkboxId);
                } else {
                    const index = this.selectedCheckBox.indexOf(checkboxId);
                    if (index !== -1) {
                        this.selectedCheckBox.splice(index, 1);
                    }
                }
            },
            async destroy() {
                showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                    try {
                        await axios.post(`/master/common/area/destroy`, new FormData(this.formDelete));
                        await showAlert('success', 'Data sukses dihapus');
                        await this.init();
                    } catch (error) {
                        console.error(error);
                        await showAlert('error', 'Terjadi kesalahan');
                    }
                });
            },
        }
    }
</script>
