<div class="row mb-10">
    @if(empty(Auth::user()->branch_id))
        <div class="col-md-6">
            <label for="branch_id" class="required form-label">Cabang</label>
            <select name="branch_id" id="selected-branch"
                    class="form-select form-select-solid main-branches-select2"
                    data-dropdown-parent="#modal-transactions">
                <option></option>
            </select>
        </div>
    @endif
    <div class="col-md-6">
        <label for="date" class="required form-label">Tanggal</label>
        <input type="date" id="date" name="date" class="form-control-solid form-control date"
               placeholder="Tanggal Transaksi"
               :value="editVal?.date">
    </div>
</div>
<div class="row mb-10">
    <label for="detail" class="required form-label">
        Detail Transaksi
    </label>
    <textarea class="form-control form-control-solid" name="detail" id="detail"
              placeholder="Detail Transaksi" :value="editVal?.detail"
              data-kt-autosize="true"></textarea>
</div>

<div class="row mb-10">
    <div class="col-md-4">
        <label for="name" class="required form-label">
            Nama Barang
        </label>
        <select name="item_id" id="selected-item" class="form-select form-select-solid items-select2"
                data-dropdown-parent="#modal-transactions">
            <option></option>
        </select>
    </div>

    <div class="col-md-4">
        <label for="name" class="required form-label">
            Harga Satuan
        </label>
        <input type="text" class="form-control form-control-solid" name="unit_price"
               id="unit_price" :value="parseFloat(editVal?.unit_price)" placeholder="Harga Satuan"/>
    </div>
    <div class="col-lg-4">
        <label for="name" class="required form-label">Qty</label>
        <input type="number" class="form-control form-control-solid" name="qty" id="qty"
               placeholder="Kuantitas" :value="editVal?.qty">
    </div>

</div>


<div class="row mb-10">


    <div class="col-lg-6">
        <label for="name" class="required form-label">Bukti Transaksi</label>
        <input type="file" class="form-control form-control-solid" @change="previewFile"
               accept="image/*" x-ref="myFile" name="attachment" id="attachment">
    </div>


    <div class="col-lg-6">
        <label
            :class="`${imgsrc.length > 0 ? 'col-form-label required fw-bold fs-6' : 'd-none'}`">
            Preview
        </label>
        <template x-for="(src, index) in imgsrc" :key="index">
            <div class="col-md-4">
                <img :src="src" class="img-fluid" @click="openImage(src)">
            </div>
        </template>
    </div>

</div>


<div class="row mb-10">
    <div class="col-lg-6">
        <label for="name" class="required form-label">
            Akun Debit
        </label>
        <select name="debit_account_id" id="selected-debit-account"
                class="form-select form-select-solid stock-accounts-select2"
                data-dropdown-parent="#modal-transactions">
            <option></option>
        </select>
    </div>
    <div class="col-lg-6">
        <label for="name" class="required form-label">
            Akun Kredit
        </label>
        <select name="credit_account_id" id="selected-credit-account"
                class="form-select-solid form-select kas-and-leverage-accounts-select2"
                data-dropdown-parent="#modal-transactions">
            <option></option>
        </select>
    </div>
</div>
