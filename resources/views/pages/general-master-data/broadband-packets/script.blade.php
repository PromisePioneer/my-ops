<script>
    function branchesData() {
        return {
            editPermission: "{{ request()->user()->can('Edit Data Paket Broadband') }}",
            deletePermission: "{{ request()->user()->can('Hapus Data Paket Broadband') }}",
            broadbandPackets: [],
            isLoading: false,
            buttonLoading: false,
            startIndex: null,
            selectedCheckBox: [],
            selectAll: false,
            singleChecked: false,
            search: '',
            editVal: '',
            form: document.getElementById('form-broadband-packet'),
            modalForm: new bootstrap.Modal(document.getElementById('modal-broadband-packet')),
            deleteForm: document.getElementById('deleteForm'),
            formFilter: document.getElementById('form-filter'),
            async init() {
                await this.getBroadbandPacketData();
                await this.getBranchData();
            },
            async getBroadbandPacketData() {
                this.isLoading = true;
                try {
                    const resp = await axios.get('/general-master-data/broadband-packets/data');
                    this.broadbandPackets = resp.data
                    this.startIndex = this.broadbandPackets.from;
                } catch (e) {
                    console.log(e)
                } finally {
                    this.isLoading = false;

                }
            },
            async getBranchData() {
                $(".branches-select2").select2({
                    allowClear: true,
                    placeholder: "Pilih Cabang",
                    ajax: {
                        url: '/general-master-data/broadband-packets/branches/data',
                        dataType: "json",
                        type: "GET",
                        data: params => ({search: params.term}),
                        processResults: data => ({results: data}),
                        cache: true
                    }
                });
            },
            async searchData() {
                const branchId = $('#branch_id_filter').val()
                try {
                    const resp = await axios.get('/general-master-data/broadband-packets/search', {
                        params: {
                            branch_id: branchId,
                            search: this.search
                        },
                        headers: {'Content-Type': 'application/json'}
                    });
                    this.broadbandPackets = resp.data;
                } catch (error) {
                    console.log(error);
                }
            },
            async filter() {
                try {
                    const branchId = $('#branch_id_filter').val()
                    const resp = await axios.get('/general-master-data/broadband-packets/filter', {
                        params: {
                            branch_id: branchId
                        }
                    })
                    this.broadbandPackets = resp.data;
                } catch (e) {
                    console.log(e)
                } finally {
                    this.isLoading = false;
                }

            },
            async paginate(url) {
                const branchId = $('#branch_id_filter').val()
                if (url) {
                    const resp = await axios.get(`${url}`, {
                        params: {
                            branch_id: branchId
                        }
                    });
                    this.broadbandPackets = resp.data
                }
            },
            async save(id = null) {
                this.buttonLoading = true;
                try {
                    if (!id) {
                        await axios.post('/general-master-data/broadband-packets', new FormData(this.form))
                    } else {
                        await axios.post(`/general-master-data/broadband-packets/${id}`, new FormData(this.form))
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
                const resp = await axios.get(`/general-master-data/broadband-packets/${id}`);
                this.editVal = resp.data;
                await this.selectedBranch();
            },
            async selectedBranch() {
                const selectedBranch = $('#selected-branch');
                const response = await $.ajax({
                    type: 'GET',
                    dataType: "JSON",
                    url: `/general-master-data/broadband-packets/branch/selected/${this.editVal.id}`,
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
                        await axios.post(`/general-master-data/broadband-packets/destroy`, new FormData(this.deleteForm));
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
