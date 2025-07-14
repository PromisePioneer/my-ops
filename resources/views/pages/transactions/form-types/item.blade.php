<div class="row mb-10">
    <div class="col-md-6">
        <label for="branch_id" class="required form-label">Cabang</label>
        <x-select2.index name="branch_id" id="selected-branch" class="form-select form-select-solid"
                         elementSelector="branches-select2"/>
    </div>
    <div class="col-md-6">
        <label for="company_id" class="required form-label">Perusahaan</label>
        <x-select2.index name="company_id" id="selected-company" class="form-select form-select-solid"
                         elementSelector="companies-select2"/>
    </div>
</div>


<div class="row mb-10">
    <div class="col-md-6">
        <label for="date" class="required form-label">Tanggal</label>
        <input type="date" id="date" name="date" class="form-control-solid form-control date"
               placeholder="Tanggal Transaksi" value="{{ $transaction->date ?? '' }}">
    </div>
    <div class="col-md-6">
        <label for="date" class="required form-label">Supplier</label>
        <x-select2.index name="supplier_id" id="selected-supplier" class="form-select form-select-solid"
                         elementSelector="suppliers-select2"
        />
    </div>
</div>

<div class="row mb-10">
    <label for="detail" class="required form-label">
        Detail Transaksi
    </label>
    <textarea class="form-control form-control-solid" name="detail" id="detail"
              placeholder="Detail Transaksi"
              data-kt-autosize="true">{{ $transaction->detail ?? null }}</textarea>
</div>

<div class="row mb-10">
    <div class="col-md-4">
        <label for="name" class="required form-label">
            Nama Barang
        </label>
        <x-select2.index
            name="item_id"
            id="selected-item"
            class="form-select form-select-solid"
            elementSelector="items-select2"
        />
    </div>

    <div class="col-md-4">
        <label for="name" class="required form-label">
            Harga Satuan
        </label>
        <input type="text" class="form-control form-control-solid" name="unit_price"
               id="unit_price" :value="parseFloat(unitPrice)" placeholder="Harga Satuan"/>
    </div>
    <div class="col-lg-4">
        <label for="name" class="required form-label">Qty</label>
        <input type="number" class="form-control form-control-solid" name="qty" id="qty"
               placeholder="Kuantitas" value="{{ $transaction->qty ?? ''}}">
    </div>

</div>


<div class="row mb-10">
    <div class="col-md-4" x-show="qtyInMeter" x-transition x-cloak>
        <label for="name" class="required form-label">Qty (Meter) Dalam 1 Haspel</label>
        <input type="number" class="form-control form-control-solid" :name="`${qtyInMeter ? 'qty_in_meter' : ''}`"
               value="{{ $transaction->qty_in_meter ?? ''}}" id="qty_in_meter">
    </div>
    <div :class="qtyInMeter ? 'col-md-4' : 'col-md-6'">
        <label for="name" class="required form-label">Akun Persediaan</label>
        <x-select2.index
            name="debit_account_id"
            id="selected-debit-account"
            class="form-select form-select-solid"
            elementSelector="stock-accounts-select2"
        />
    </div>


    <div :class="qtyInMeter ? 'col-md-4' : 'col-md-6'">
        <label for="name" class="required form-label">Akun Kas Dan Utang</label>
        <x-select2.index
            name="credit_account_id"
            id="selected-credit-account"
            class="form-select form-select-solid"
            elementSelector="kas-and-leverage-accounts-select2"
        />
    </div>
</div>


<div class="row mb-10">
    <div class="col-lg-6">
        <label for="name" class="required form-label">Bukti Transaksi</label>
        <input type="file" class="form-control form-control-solid" @change="previewAttachmentFile()"
               accept="image/*" x-ref="attachmentFile" name="attachment" id="attachment">
    </div>
    <div class="col-lg-6" x-show="PKP" x-cloak x-transition>
        <label for="name" class="required form-label">Faktur Pajak</label>
        <input type="file" class="form-control form-control-solid" @change="previewTaxInvoiceFile()"
               accept="image/*" x-ref="taxInvoiceFile" name="tax_invoice" id="tax_invoice">
    </div>
</div>


<div class="row mb-10">
    <div class="col-lg-6">
        <label
            :class="`${attachmentImgSrc.length > 0 ? 'col-form-label required fw-bold fs-6' : 'd-none'}`">
            Preview
        </label>
        <img :src="attachmentImgSrc" class="w-100" @click="openAttachmentImage(attachmentImgSrc)">
    </div>
    <div class="col-lg-6">
        <label
            :class="`${taxInvoiceImgSrc.length > 0 ? 'col-form-label required fw-bold fs-6' : 'd-none'}`">
            Preview
        </label>
        <img :src="taxInvoiceImgSrc" class="w-100" @click="openTaxInvoiceImage(taxInvoiceImgSrc)">
    </div>

</div>

