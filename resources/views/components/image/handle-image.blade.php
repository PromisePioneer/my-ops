<div></div>


<script>
    function getImageURL(imagePath) {
        if (imagePath === null) {
            const placeholders = 'assets/media/avatars/blank.png'
            return "{{ asset('') }}" + placeholders;
        }
        return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
    }


    function previewImage(imageIdElement, imageSrcElement) {
        let files = document.getElementById(imageIdElement).files;
        if (!files.length) return;


        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;

            let reader = new FileReader();
            reader.onload = e => {
                imageSrcElement = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        return imageSrcElement
    }


    function openImage(imagePath) {
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
    }
</script>
