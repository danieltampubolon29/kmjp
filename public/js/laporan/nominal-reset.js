document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('resetForm');
    const nominalInput = document.getElementById('nominal');

    nominalInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(value);
    });

    form.addEventListener('submit', function () {
        nominalInput.value = nominalInput.value.replace(/\D/g, '');
    });

    window.resetForm = function () {
        form.reset();
        nominalInput.value = '';
    };
});