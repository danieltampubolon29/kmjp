document.addEventListener('DOMContentLoaded', function () {
    const nominalInput = document.getElementById('nominal');

    // Format angka saat pengguna mengetik
    nominalInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\./g, ''); // Hapus semua titik sebelum pemrosesan
        value = value.replace(/[^0-9]/g, ''); // Hanya izinkan angka

        if (value === '') {
            e.target.value = ''; // Jika kosong, biarkan kosong
            return;
        }

        // Format angka dengan pemisah ribuan
        const formattedValue = new Intl.NumberFormat('id-ID').format(value);
        e.target.value = formattedValue;
    });

    // Bersihkan format saat form disubmit
    const form = document.querySelector('.form-submit');
    form.addEventListener('submit', function () {
        let rawValue = nominalInput.value.replace(/\./g, ''); // Hapus titik untuk mendapatkan nilai numerik murni
        nominalInput.value = rawValue; // Simpan nilai bersih ke input
    });
});