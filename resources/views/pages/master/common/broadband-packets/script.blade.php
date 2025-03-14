<script>
    function broadbandPacketData() {
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
            async searchData() {
                this.isLoading = true;
                try {
                    const resp = await axios.get('/general-master-data/broadband-packets/search', {
                        params: {
                            search: this.search
                        },
                        headers: {'Content-Type': 'application/json'}
                    });
                    this.broadbandPackets = resp.data;
                } catch (error) {
                    console.log(error);
                } finally {
                    this.isLoading = false;
                }
            },
            async paginate(url) {
                if (url) {
                    const resp = await axios.get(`${url}`);
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
