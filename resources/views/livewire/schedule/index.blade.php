<div>

    <livewire:schedule.chart />

    <x-slot name="right">
        <livewire:courses.index />
    </x-slot>
    <x-slot name="top">
        <livewire:classrooms.index />
    </x-slot>
    <x-slot name="detay">
        <livewire:dynamic-detail />
    </x-slot>


    <script>
        document.addEventListener('DOMContentLoaded', function (e) {
            Livewire.on('show-confirm', (event) => {
                const data = event[0];
                // Show the confirmation dialog with an input field
                Swal.fire({
                    title: 'Yakalandınız!!!',
                    text: data.message,
                    icon: 'info',
                    input: 'text',
                    inputPlaceholder: 'Kodu Girmek istiyor musunuz???',
                    showCancelButton: false,
                    confirmButtonText: 'Canınız İsterse',
                    background: '#1a1a2e',
                    color: '#ffffff',
                    allowOutsideClick: false,
                    inputAttributes: {
                        style: 'color: black; background-color: #fff;'
                    },
                    preConfirm: (inputCode) => {
                        return new Promise((resolve, reject) => {
                            // Validate the entered code here
                            if (inputCode === '1234') {
                                // If the code is valid, resolve the promise
                                resolve(inputCode);
                            } else {
                                // If the code is invalid, reject the promise
                                Swal.showValidationMessage('Yemek Ismarlamadan Olmaz!');
                                reject('Invalid code!');
                            }
                        });
                    }
                }).then((result) => {
                    // If the user confirms and the code is valid, you can trigger further actions here
                    if (result.isConfirmed && result.value) {
                        Swal.fire({
                            title: data.type ===  'Başarılı!',
                            text: 'Teşekkür Ederim',
                            confirmButtonText: 'Rica Ederim',
                            timer: data.type === 'error' ? null : 2000,
                            timerProgressBar: true,
                            position: 'center',
                        });
                    }
                });
            });
        });
    </script>

</div>
