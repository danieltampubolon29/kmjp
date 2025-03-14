document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const pencairanId = document.getElementById('pencairan_id');
    const noAnggota = document.getElementById('no_anggota');
    const namaAnggota = document.getElementById('nama');
    const pinjamanKe = document.getElementById('pinjaman_ke');
    const produk = document.getElementById('produk');
    const tenor = document.getElementById('tenor');
    const sisaKredit = document.getElementById('sisa_kredit');
    const nominalAngsuran = document.getElementById('nominal');
    const angsuranKe = document.getElementById('angsuran_ke');

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();

        if (query.length > 0) {
            fetch(`/search-pencairan?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    searchResults.style.display = 'block';

                    if (data.length > 0) {
                        data.forEach(pencairan => {
                            const div = document.createElement('div');
                            div.className = 'p-2 bg-light border-bottom cursor-pointer';
                            div.innerHTML = `${pencairan.no_anggota} | ${pencairan.nama} | Pinjaman ke-${pencairan.pinjaman_ke}`;

                            
                            div.dataset.id = pencairan.id;
                            div.dataset.noAnggota = pencairan.no_anggota;
                            div.dataset.nama = pencairan.nama;
                            div.dataset.pinjamanKe = pencairan.pinjaman_ke;
                            div.dataset.produk = pencairan.produk;
                            div.dataset.tenor = pencairan.tenor;
                            div.dataset.sisaKredit = pencairan.sisa_kredit;
                            div.dataset.angsuranKe = pencairan.angsuran_ke; 
                            div.dataset.nominalPencairan = pencairan.nominal || 0; // Ambil nominal dari tabel pencairan

                            div.addEventListener('click', function () {
                                pencairanId.value = this.dataset.id;
                                noAnggota.value = this.dataset.noAnggota;
                                namaAnggota.value = this.dataset.nama;
                                pinjamanKe.value = this.dataset.pinjamanKe;
                                produk.value = this.dataset.produk;
                                tenor.value = this.dataset.tenor;
                                sisaKredit.value = formatNumber(this.dataset.sisaKredit);
                                angsuranKe.value = this.dataset.angsuranKe; 

                                // Perhitungan nominal angsuran menggunakan nominal dari pencairan
                                const nominalPencairan = parseFloat(this.dataset.nominalPencairan) || 0;
                                const tenorValue = parseInt(this.dataset.tenor) || 1;

                                if (tenorValue > 0) {
                                    const calculatedNominal = Math.floor((nominalPencairan + (nominalPencairan * 0.2)) / tenorValue);
                                    nominalAngsuran.value = formatNumber(calculatedNominal);
                                } else {
                                    nominalAngsuran.value = '0';
                                }

                                searchResults.style.display = 'none';
                                searchInput.value = `${this.dataset.noAnggota} - ${this.dataset.nama}`;
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

    function formatNumber(number) {
        return parseInt(number).toLocaleString('id-ID');
    }
});
