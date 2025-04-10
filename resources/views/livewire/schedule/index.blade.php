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
            Livewire.on('show-confirmx', (event) => {
                const data = event[0];
                // Show the confirmation dialog with an input field
                Swal.fire({
                    title: 'Mustafa Bey Yakalandınız!!!',
                    text: 'Bir Dondurma Ismarlarsanız Kodu sms ile gönderiyorum.:):)',
                    icon: 'error',
                    input: 'text',
                    inputPlaceholder: 'Kodu Girmek istiyor musunuz???',
                    showCancelButton: false,
                    confirmButtonText: 'Canınız İsterse',
                    background: '#1a1a2e',
                    color: '#ffffff',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    inputAttributes: {
                        style: 'color: black; background-color: #fff;'
                    },
                    footer: '<span style="font-size: 12px; color: #bbb;">Şartları kabul ettiniz dondurmayı geri isteyemezsiniz! .</span><br> <span style="font-size: 10px;">Nazife Hanım ve Fatih Bey tarafından düşünüldü.</span> ', // Add footer text or a link

                    preConfirm: (inputCode) => {
                        return new Promise((resolve, reject) => {
                            // Validate the entered code here
                            if (inputCode === '1234') {
                                // If the code is valid, resolve the promise
                                resolve(inputCode);
                            } else {
                                // If the code is invalid, reject the promise
                                Swal.showValidationMessage('Şansınızı Denediğiniz için dondurma yetmez ancak yemek kurtarır;(');
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
                            confirmButtonText: 'Rica Ederim.',
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
