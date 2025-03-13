document.addEventListener('DOMContentLoaded', function () {
    const nominalInput = document.getElementById('nominal');

    nominalInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\./g, ''); 
        value = value.replace(/[^0-9]/g, ''); 

        if (value === '') {
            e.target.value = ''; 
            return;
        }

        const formattedValue = new Intl.NumberFormat('id-ID').format(value);
        e.target.value = formattedValue;
    });

    const form = document.querySelector('.form-submit');
    form.addEventListener('submit', function () {
        let rawValue = nominalInput.value.replace(/\./g, ''); 
        nominalInput.value = rawValue; 
    });
});