document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("simpananPokok").addEventListener("click", function () {
        showSimpananModal("Simpanan Pokok", "POKOK");
    });

    document.getElementById("simpananWajib").addEventListener("click", function () {
        showSimpananModal("Simpanan Wajib", "WAJIB");
    });

    document.getElementById("simpananSukarela").addEventListener("click", function () {
        showSimpananModal("Simpanan Sukarela", "SUKARELA");
    });

    document.getElementById("simpananDeposito").addEventListener("click", function () {
        showSimpananModal("Simpanan Deposito", "DEPOSITO");
    });
});


                function formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID', { 
                        minimumFractionDigits: 0, 
                        maximumFractionDigits: 0 
                    }).format(amount);
                }


                function showSimpananModal(title, simpananType) {
                    const modalTitle = document.getElementById('simpananModalLabel');
                    const tableBody = document.getElementById('simpananTableBody');
                    const anggotaElement = document.getElementById('noAnggota'); 
                    if (!anggotaElement) {
                        console.error('Elemen noAnggota tidak ditemukan!');
                        alert('Silakan pilih anggota terlebih dahulu.');
                        return;
                    }
                    const anggotaId = anggotaElement.textContent.trim();
                    if (!anggotaId) {
                        alert('ID Anggota tidak tersedia.');
                        return;
                    }
                    modalTitle.textContent = title;
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Memuat data...</td></tr>';

                    fetch(`/get-simpanan-transactions?type=${encodeURIComponent(simpananType)}&anggota_id=${encodeURIComponent(anggotaId)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            tableBody.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(transaction => {
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                        <td>${new Date(transaction.tanggal_transaksi).toLocaleDateString('id-ID')}</td>
                                        <td>${transaction.jenis_transaksi}</td>
                                        <td>${formatCurrency(transaction.nominal)}</td>
                                    `;
                                    tableBody.appendChild(row);
                                });
                            } else {
                                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data transaksi</td></tr>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching simpanan transactions:', error);
                            tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Terjadi kesalahan saat memuat data.</td></tr>';
                        });

                    const modalElement = document.getElementById('simpananModal');
                    if (modalElement) {
                        const simpananModal = new bootstrap.Modal(modalElement);
                        simpananModal.show();
                    } else {
                        console.error('Modal tidak ditemukan!');
                    }
                }


                
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');
                    const searchResults = document.getElementById('searchResults');
                    const namaAnggota = document.getElementById('namaAnggota');
                    const noAnggota = document.getElementById('noAnggota');

                    
                    searchInput.addEventListener('input', function() {
                        const query = searchInput.value.trim();
                        if (query.length > 0) {
                            fetch(`/search-anggota?q=${encodeURIComponent(query)}`)
                                .then(response => response.json())
                                .then(data => {
                                    searchResults.innerHTML = '';
                                    searchResults.style.display = 'block';
                                    if (data.length > 0) {
                                        data.forEach(item => {
                                            const listItem = document.createElement('li');
                                            listItem.className = 'list-group-item';
                                            listItem.textContent = `${item.no_anggota} - ${item.nama}`;
                                            listItem.setAttribute('data-no-anggota', item.no_anggota);
                                            listItem.setAttribute('data-nama', item.nama);
                                            listItem.setAttribute('data-id', item
                                                .id);
                                            listItem.addEventListener('click', function() {
                                                const anggotaId = listItem.getAttribute(
                                                    'data-id');
                                                const anggotaNama = listItem.getAttribute(
                                                    'data-nama');
                                                const anggotaNo = listItem.getAttribute(
                                                    'data-no-anggota');
                                                namaAnggota.textContent = anggotaNama;
                                                noAnggota.textContent = anggotaNo;
                                                searchInput.value =
                                                    '';
                                                searchResults.style.display =
                                                    'none';
                                                fetchSimpananData(anggotaId);
                                                fetchPencairanData(anggotaId);
                                            });

                                            searchResults.appendChild(listItem);
                                        });
                                    } else {
                                        const listItem = document.createElement('li');
                                        listItem.className = 'list-group-item disabled';
                                        listItem.textContent = 'Tidak ada hasil ditemukan';
                                        searchResults.appendChild(listItem);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching data:', error);
                                    searchResults.innerHTML =
                                        '<li class="list-group-item disabled">Terjadi kesalahan</li>';
                                });
                        } else {
                            searchResults.style.display = 'none';
                        }
                    });

                    document.addEventListener('click', function(event) {
                        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                            searchResults.style.display = 'none';
                        }
                    });

                    function fetchSimpananData(anggotaId) {
                        fetch(`/get-simpanan-data?anggota_id=${anggotaId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data) {

                                    document.getElementById('simpananPokok').textContent = formatCurrency(data.pokok);
                                    document.getElementById('simpananWajib').textContent = formatCurrency(data.wajib);
                                    document.getElementById('simpananSukarela').textContent = formatCurrency(data
                                        .sukarela);
                                    document.getElementById('simpananDeposito').textContent = formatCurrency(data
                                        .deposito);
                                } else {
                                    console.error('Data simpanan kosong atau tidak valid.');
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching simpanan data:', error);
                            });
                            
                    }

                    function formatCurrency(amount) {
                        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }

                    function fetchPencairanData(anggotaId) {
                        fetch(`/get-pencairan-data?anggota_id=${anggotaId}`)
                            .then(response => response.json())
                            .then(data => {
                                const tableBody = document.querySelector('.pinjaman-table tbody');
                                tableBody.innerHTML = '';
                    
                                if (data.length > 0) {
                                    data.forEach(item => {
                                        const row = document.createElement('tr');
                    
                                        row.setAttribute('data-item', JSON.stringify(item));
                                        row.classList.add('clickable-row'); 
                    
                                        row.innerHTML = `
                                            <td>${item.pinjaman_ke}</td>
                                            <td>${new Date(item.tanggal_pencairan).toLocaleDateString('id-ID')}</td>
                                            <td>${formatCurrency(item.nominal)}</td>
                                            <td>${formatCurrency(item.sisa_kredit)}</td>
                                        `;
                    
                                        tableBody.appendChild(row);
                                    });
                    
                                    document.querySelectorAll('.clickable-row').forEach(row => {
                                        row.addEventListener('click', () => {
                                            const item = JSON.parse(row.getAttribute('data-item'));
                    
                                            fetch(`/get-angsuran-data/${item.id}`)
                                            .then(response => {
                                                if (!response.ok) {
                                                    throw new Error(`HTTP error! status: ${response.status}`);
                                                }
                                                return response.json();
                                            })
                                            .then(angsuranData => {
                                                const modalTitle = document.getElementById('modal-title');
                                                const modalBody = document.getElementById('modal-body');
                                        
                                                modalTitle.textContent = `Angsuran Pinjaman Ke ${item.pinjaman_ke}`;
                                        
                                                modalBody.innerHTML = `
                                                    <div class="table-responsive">
                                                        <table class="table table-light table-hover text-center pinjaman-table">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th class="text-center align-middle">ANGSURAN KE</th>
                                                                    <th class="text-center align-middle">TANGGAL</th>
                                                                    <th class="text-center align-middle">NOMINAL</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="angsuranTableBody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                `;
                    
                                                const angsuranTableBody = document.getElementById('angsuranTableBody');
                                                angsuranTableBody.innerHTML = '';
                                        
                                                if (angsuranData.length > 0) {
                                                    angsuranData.forEach(angsuran => {
                                                        const angsuranRow = document.createElement('tr');
                                                        angsuranRow.innerHTML = `
                                                            <td>${angsuran.angsuran_ke}</td>
                                                            <td>${new Date(angsuran.tanggal_angsuran).toLocaleDateString('id-ID')}</td>
                                                            <td>${formatCurrency(angsuran.nominal)}</td>
                                                        `;
                                                        angsuranTableBody.appendChild(angsuranRow);
                                                    });
                                                } else {
                                                    const noDataRow = document.createElement('tr');
                                                    noDataRow.innerHTML = `
                                                        <td colspan="4" class="text-center">Tidak ada data angsuran</td>
                                                    `;
                                                    angsuranTableBody.appendChild(noDataRow);
                                                }
                                        
                                                const modal = new bootstrap.Modal(document.getElementById('pencairanModal'));
                                                modal.show();
                                            })
                                            .catch(error => {
                                                console.error('Error fetching angsuran data:', error);
                                            });
                                        });
                                    });
                                } else {
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                        <td colspan="4" class="text-center">Tidak ada data pencairan</td>
                                    `;
                                    tableBody.appendChild(row);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching pencairan data:', error);
                            });
                    }
                });