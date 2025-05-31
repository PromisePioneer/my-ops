<div></div>

<script>
    function decimalInputMask(idElement) {
        Inputmask("decimal", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            allowMinus: false
        }).mask(`#${idElement}`);
    }
</script>
