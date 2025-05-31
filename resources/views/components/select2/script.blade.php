<div></div>


<script>
    async function select2(element, placeholder, url, cache = true, tags = false, modalId = null) {
        return $(`${element}`).select2({
            allowClear: true,
            placeholder: placeholder,
            escapeMarkup: markup => (markup),
            tags: tags,
            language: {
                noResults: () => {
                    return `Data Tidak Ditemukan..
                    ${modalId ?
                        `<a href=/'#' data-bs-toggle="modal" data-bs-target="#${modalId}">
                            Tambahkan terlebih dahulu
                         </a>`
                        : ''}`;
                }
            },
            ajax: {
                url: url,
                dataType: "json",
                type: "GET",
                data: (params) => ({
                    search: params.term,
                }),
                processResults: (data) => ({results: data}),
                cache: cache,
            }
        });
    }


    async function selectedValue(idElement, url) {
        const selectedEl = $(`#${idElement}`);
        const response = await axios.get(`${url}`);
        const option = new Option(response.data.name, response.data.id, true, true);
        selectedEl.append(option).trigger('change').trigger({
            type: 'select2:select',
            params: {results: response}
        });
    }
</script>
