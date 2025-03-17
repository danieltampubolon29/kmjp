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

                                        row.innerHTML = `
                                        <td>${item.pinjaman_ke}</td>
                                        <td>${new Date(item.tanggal_pencairan).toLocaleDateString('id-ID')}</td>
                                        <td>${formatCurrency(item.nominal)}</td>
                                        <td>${formatCurrency(item.sisa_kredit)}</td>`;
                                        
                                        

                                        tableBody.appendChild(row);
                                    });
                                } else {
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                    <td colspan="4" class="text-center">Tidak ada data pencairan</td>`;
                                    tableBody.appendChild(row);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching pencairan data:', error);
                            });
                    }
                });