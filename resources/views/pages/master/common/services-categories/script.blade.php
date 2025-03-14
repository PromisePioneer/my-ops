<script>
    function servicesCategoriesData() {
        return {
            createPermission: "{{ request()->user()->can('Tambah Data Kategori Layanan') }}",
            editPermission: "{{ request()->user()->can('Edit Data Kategori Layanan') }}",
            deletePermission: "{{ request()->user()->can('Hapus Data Kategori Layanan') }}",
            categories: [],
            buttonLoading: false,
            isLoading: false,
            search: '',
            selectAll: false,
            selectedCheckBox: [],
            singleChecked: false,
            editVal: '',
            modalForm: new bootstrap.Modal(document.getElementById('modal-service-category')),
            form: document.getElementById('form-service-category'),
            deleteForm: document.getElementById('deleteForm'),
            async init() {
                await this.getServiceCategories();
            },
            async getServiceCategories() {
                this.isLoading = true;
                try {
                    const resp = await axios.get('/common-master-data/service-categories/data');
                    this.categories = resp.data;
                } catch (e) {
                    console.log(e)
                } finally {
                    this.isLoading = false;
                }
            },
            async searchData() {
                const resp = await axios.get('/common-master-data/service-categories/search', {
                    params: {
                        search: this.search
                    },
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                this.categories = resp.data;
            },
            async paginationEndPoint(url) {
                if (url) {
                    const resp = await axios.get(`${url}`);
                    this.startIndex = resp.data.from
                    this.categories = resp.data
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
            async saveServiceCategory(id = null) {
                this.buttonLoading = true;
                try {
                    if (!id) {
                        await axios.post('/common-master-data/service-categories/', new FormData(this.form))
                    } else {
                        await axios.post(`/common-master-data/service-categories/update/${id}`, new FormData(this.form))
                    }
                    await showAlert('success', 'Data berhasil disimpan');
                    await this.init();
                    await this.form.reset();
                    await this.modalForm.hide();
                } catch (error) {
                    const respError = error.response.data.errors;
                    Object.keys(respError).map(err => toastr.error(respError[err][0]))
                } finally {
                    this.buttonLoading = false
                }
            },
            async edit(id) {
                const resp = await axios.get(`/common-master-data/service-categories/show/${id}`);
                this.editVal = resp.data;
            },
            async destroy() {
                showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                    try {
                        await axios.post(`/common-master-data/service-categories/destroy`, new FormData(this.deleteForm));
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
