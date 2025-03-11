document.addEventListener('DOMContentLoaded', function () {
    const produkSelect = document.getElementById('produk');
    const jatuhTempoSelect = document.getElementById('jatuh_tempo');

    function updateJatuhTempoOptions() {
        jatuhTempoSelect.innerHTML = '';

        if (produkSelect.value === 'Harian') {
            const option = document.createElement('option');
            option.value = 'Harian';
            option.textContent = 'Harian';
            jatuhTempoSelect.appendChild(option);
        } else if (produkSelect.value === 'Mingguan') {
            const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            days.forEach(day => {
                const option = document.createElement('option');
                option.value = day;
                option.textContent = day;
                jatuhTempoSelect.appendChild(option);
            });
        }
    }

    updateJatuhTempoOptions();
    produkSelect.addEventListener('change', updateJatuhTempoOptions);
});