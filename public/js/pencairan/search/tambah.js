document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const anggotaId = document.getElementById('anggota_id');
    const noAnggota = document.getElementById('no_anggota');
    const namaAnggota = document.getElementById('nama');
    const pinjamanKe = document.getElementById('pinjaman_ke');

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();

        if (query.length > 0) {
            fetch(`/search-anggota?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    searchResults.style.display = 'block';

                    if (data.length > 0) {
                        data.forEach(anggota => {
                            const div = document.createElement('div');
                            div.className = 'p-2 bg-light border-bottom cursor-pointer';
                            div.textContent = `${anggota.no_anggota} - ${anggota.nama}`;
                            div.setAttribute('data-id', anggota.id);
                            div.setAttribute('data-no-anggota', anggota.no_anggota);
                            div.setAttribute('data-nama-anggota', anggota.nama);

                            div.addEventListener('click', function () {
                                const selectedAnggotaId = this.getAttribute('data-id');

                                anggotaId.value = selectedAnggotaId;
                                noAnggota.value = this.getAttribute('data-no-anggota');
                                namaAnggota.value = this.getAttribute('data-nama-anggota');

                                fetch(`/get-pinjaman-ke/${selectedAnggotaId}`)
                                    .then(response => response.json())
                                    .then(pinjamanKeValue => {
                                        pinjamanKe.value = pinjamanKeValue;
                                    })
                                    .catch(error => {
                                        console.error('Error fetching pinjaman_ke:', error);
                                    });

                                searchResults.style.display = 'none';
                                searchInput.value = `${this.getAttribute('data-no-anggota')} - ${this.getAttribute('data-nama-anggota')}`;
                            });

                            searchResults.appendChild(div);
                        });
                    } else {
                        searchResults.innerHTML = '<div class="p-2">Tidak ada hasil ditemukan</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    searchResults.innerHTML = '<div class="p-2">Terjadi kesalahan saat mencari</div>';
                });
        } else {
            searchResults.style.display = 'none';
        }
    });

    document.addEventListener('click', function (event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.style.display = 'none';
        }
    });
});