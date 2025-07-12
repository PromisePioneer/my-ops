<script defer>
    function companyData() {
        return {
            editPermission: "{{ request()->user()->can('Edit Data Perusahaan') }}",
            deletePermission: "{{ request()->user()->can('Hapus Data Perusahaan') }}",
            companies: [],
            isLoading: true,
            buttonLoading: false,
            selectedCheckBox: [],
            selectAll: false,
            singleChecked: false,
            search: '',
            editVal: '',
            form: document.getElementById('form-company'),
            modalForm: new bootstrap.Modal(document.getElementById('modal-company')),
            formDelete: document.getElementById('form-delete'),
            async init() {
                await this.getCompany();
            },
            async getCompany() {
                const resp = await axios.get('/master/common/companies/data');
                this.companies = resp.data
                this.isLoading = false;
            },
            async searchData() {
                try {
                    const resp = await axios.get('/master/common/companies/search', {
                        params: {search: this.search},
                        headers: {'Content-Type': 'application/json'}
                    });
                    this.companies = resp.data;
                } catch (error) {
                    console.log(error);
                }
            },
            async paginationEndPoint(url) {
                if (url) {
                    const resp = await axios.get(`${url}`);
                    this.branches = resp.data
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
            async save(id = null) {
                this.buttonLoading = true;
                try {
                    if (!id) {
                        await axios.post('/master/common/companies', new FormData(this.form))
                    } else {
                        await axios.post(`/master/common/companies/update/${id}`, new FormData(this.form))
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
                const resp = await axios.get(`/master/common/companies/${id}`);
                this.editVal = resp.data;
            },
            async destroy() {
                showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                    try {
                        await axios.post(`/master/common/companies/destroy`, new FormData(this.formDelete));
                        await showAlert('success', 'Data sukses dihapus');
                        await this.init();
                    } catch (error) {
                        console.error(error);
                        await showAlert('error', 'Terjadi kesalahan');
                    }
                });
            },
            getImageURL(imagePath) {
                if (imagePath === null) {
                    const placeholders = 'assets/media/avatars/blank.png'
                    return "{{ asset('') }}" + placeholders;
                }
                return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
            },
            openImage(imagePath) {
                const lightbox = new FsLightbox();
                console.log(lightbox);
                if (imagePath === null) {
                    const placeholders = 'assets/media/avatars/blank.png'
                    const image = "{{ asset('') }}" + placeholders
                    lightbox.props.sources = [image, image];
                    lightbox.open();
                } else {
                    const image = "{{ Storage::url('') }}" + imagePath;
                    lightbox.props.sources = [image];
                    lightbox.open();
                }
            },
        }
    }
</script>
