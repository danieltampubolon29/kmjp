function resetForm() {
    // Kosongkan input pencarian
    const searchInput = document.getElementById('searchInput');
    searchInput.value = '';

    // Sembunyikan dropdown hasil pencarian
    const searchResults = document.getElementById('searchResults');
    searchResults.style.display = 'none';
    searchResults.innerHTML = '';

    // Kembalikan nilai default untuk nama anggota dan nomor anggota
    const namaAnggota = document.getElementById('namaAnggota');
    const noAnggota = document.getElementById('noAnggota');
    namaAnggota.textContent = '-';
    noAnggota.textContent = '-';

    // Kembalikan nilai default untuk simpanan
    const simpananPokok = document.getElementById('simpananPokok');
    const simpananWajib = document.getElementById('simpananWajib');
    const simpananSukarela = document.getElementById('simpananSukarela');
    const simpananDeposito = document.getElementById('simpananDeposito');
    simpananPokok.textContent = '-';
    simpananWajib.textContent = '-';
    simpananSukarela.textContent = '-';
    simpananDeposito.textContent = '-';

    // Kosongkan tabel pinjaman
    const tableBody = document.querySelector('.pinjaman-table tbody');
    tableBody.innerHTML = '';
}