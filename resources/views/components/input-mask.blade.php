<div></div>

<script>
    function inputMask(idElement, maskType) {
        Inputmask(`${maskType}`, {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            allowMinus: false
        }).mask(`#${idElement}`);
    }
</script>
