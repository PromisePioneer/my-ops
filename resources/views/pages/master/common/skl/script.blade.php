<script>
    function sklData() {
        return {
            editPermission: "{{ request()->user()->can('Edit Data SKL') }}",
            deletePermission: "{{ request()->user()->can('Hapus Data SKL') }}",
            skl: [],
            isLoading: false,
            buttonLoading: false,
            selectedCheckBox: [],
            selectAll: false,
            singleChecked: false,
            search: '',
            editVal: '',
            form: document.getElementById('form-skl'),
            modalForm: new bootstrap.Modal(document.getElementById('modal-skl')),
            formDelete: document.getElementById('form-delete'),
            async init() {
                await this.getSklData();
            },
            async getSklData() {
                this.isLoading = false;
                try {
                    const resp = await axios.get('/master/common/skl/data');
                    this.skl = resp.data
                } catch (e) {
                    console.log(e)
                } finally {
                    this.isLoading = false;
                }
            },
            async searchData() {
                try {
                    const resp = await axios.get('/master/common/skl/search', {
                        params: {search: this.search},
                        headers: {'Content-Type': 'application/json'}
                    });
                    this.skl = resp.data;
                } catch (error) {
                    console.log(error);
                }
            },
            async paginationEndPoint(url) {
                if (url) {
                    const resp = await axios.get(`${url}`);
                    this.skl = resp.data
                }
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
            async edit(id) {
                const resp = await axios.get(`/master/common/skl/${id}`);
                console.log(resp);
                this.editVal = resp.data;
            },
            async update(id) {
                this.buttonLoading = true;
                try {
                    await axios.post(`/master/common/skl/${id}`, new FormData(this.formEdit))
                    await showAlert('success', 'Data berhasil disimpan')
                    this.modalEdit.hide();
                    this.formEdit.reset();
                    await this.init();
                } catch (error) {
                    const respError = error.response.data.errors;
                    Object.keys(respError).map(err => toastr.error(respError[err][0]));
                } finally {
                    this.buttonLoading = false;
                }
            },
            async destroy() {
                showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                    try {
                        await axios.post(`/master/common/skl/destroy`, new FormData(this.formDelete));
                        await showAlert('success', 'Data sukses dihapus');
                        await this.init();
                    } catch (error) {
                        console.error(error);
                        await showAlert('error', 'Terjadi kesalahan');
                    }
                });
            },
            async saveSKL(id = null) {
                this.buttonLoading = true;
                try {
                    if (!id) {
                        await axios.post('/master/common/skl', new FormData(this.form))
                    } else {
                        await axios.post(`/master/common/skl/${id}`, new FormData(this.form))
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
            }
        }
    }
</script>
