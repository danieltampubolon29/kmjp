document.addEventListener('DOMContentLoaded', function () {
    const nominalInput = document.getElementById('nominal');
    const sisaKreditInput = document.getElementById('sisa_kredit');

    function formatCurrency(input) {
        if (!input) return; // Hindari error jika elemen tidak ditemukan

        let value = input.value.replace(/\./g, ''); // Hapus titik pemisah ribuan
        value = value.replace(/[^0-9]/g, ''); // Hanya izinkan angka

        if (value === '') {
            input.value = '';
            return;
        }

        const formattedValue = new Intl.NumberFormat('id-ID').format(value);
        input.value = formattedValue;
    }

    // Format nominal & sisa_kredit saat halaman dimuat
    formatCurrency(nominalInput);
    formatCurrency(sisaKreditInput);

    // Format nominal saat input berubah
    nominalInput.addEventListener('input', function (e) {
        formatCurrency(e.target);
    });

    // Sebelum form submit, hapus titik agar nilai tetap integer
    const form = document.querySelector('.form-submit');
    if (form) {
        form.addEventListener('submit', function () {
            nominalInput.value = nominalInput.value.replace(/\./g, '');
            sisaKreditInput.value = sisaKreditInput.value.replace(/\./g, '');
        });
    }
});
