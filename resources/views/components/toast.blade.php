<div>
</div>

<script>
    let Toast;

    document.addEventListener('DOMContentLoaded', () => {
        Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

    });

    async function showAlert(type, title, timer = 1000) {
        if (Toast) {
            await Toast.fire({
                title: title,
                icon: type,
                timer: timer,
            });
        } else {
            console.error('Toast is not defined.');
        }
    }

    function showConfirmModal(title, text, confirmButtonText, onConfirm) {
        Swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: confirmButtonText
        }).then((result) => {
            if (result.isConfirmed) {
                onConfirm();
            }
        });
    }

</script>
