                function getDaysInMonth(year, month) {
                    return new Date(year, month, 0).getDate();
                }

                function formatCurrency(amount) {
                    if (!amount || amount === '-') return '-';
                    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                function generateTable() {
                    const month = parseInt(document.getElementById('monthSelect').value);
                    const year = parseInt(document.getElementById('yearInput').value);

                    if (!year || year < 2000 || year > 2100) {
                        alert('Masukkan tahun yang valid (2000-2100).');
                        return;
                    }
                    const monthNames = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];
                    const selectedMonthName = monthNames[month - 1]; // Index dimulai dari 0
                    document.querySelector('#kasbonBulanIni').textContent = `Kasbon ${selectedMonthName}`;

                    fetch(`/rekap-data/get-pencairan-data?month=${month}&year=${year}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            document.getElementById('totalAnggota').textContent = data.totalAnggota;
                            document.getElementById('saldoSimpanan').textContent = `Rp. ${formatCurrency(data.saldoSimpanan)}`;
                            document.getElementById('totalSisaKredit').textContent =
                                `Rp. ${formatCurrency(data.totalSisaKredit)}`;
                            document.getElementById('totalPencairanPending').textContent = data.totalPencairanPending;
                            document.getElementById('pengambilanKasbon').textContent = `Rp ${formatCurrency(data.pengambilan_kasbon)}`;
                            document.getElementById('pengambilanKasbon').textContent = `Rp ${formatCurrency(data.pengambilan_kasbon)}`;

                            const pengembalianKasbonElement = document.getElementById('pengembalianKasbon');
                            pengembalianKasbonElement.textContent = `Rp ${formatCurrency(data.pengembalian_kasbon)}`;
                            if (data.pengembalian_kasbon < 0) {
                                pengembalianKasbonElement.style.color = 'red'; 
                            } else {
                                pengembalianKasbonElement.style.color = ''; 
}
                            document.getElementById('kasbonPerBulan').textContent = `Rp ${formatCurrency(data.kasbon_perbulan)}`;

                            const daysInMonth = getDaysInMonth(year, month);
                            const tbody = document.querySelector('#dynamicTable tbody');
                            tbody.innerHTML = '';

                            for (let day = 1; day <= daysInMonth; day++) {
                                const row = document.createElement('tr');
                                const dateKey = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                                const dateCell = document.createElement('td');
                                dateCell.textContent = `${day}/${month}/${year}`;
                                row.appendChild(dateCell);

                                const pencairanAmount = data.pencairan_data[dateKey]?.total || 0;
                                const pencairanCell = document.createElement('td');
                                pencairanCell.textContent = formatCurrency(pencairanAmount);
                                row.appendChild(pencairanCell);

                                const angsuranAmount = data.angsuran_data[dateKey]?.total || 0;
                                const angsuranCell = document.createElement('td');
                                angsuranCell.textContent = formatCurrency(angsuranAmount);
                                row.appendChild(angsuranCell);

                                tbody.appendChild(row);
                            }

                            document.getElementById('totalPencairan').textContent = formatCurrency(data.total_pencairan);
                            document.getElementById('totalAngsuran').textContent = formatCurrency(data.total_angsuran);
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            alert('Gagal memuat data dari server.');
                        });
                }

                document.getElementById('monthSelect').addEventListener('change', generateTable);
                document.getElementById('yearInput').addEventListener('input', generateTable);

                document.getElementById('downloadBtn').addEventListener('click', function() {
                    const cardBody = document.querySelector('.card-body');
                    html2canvas(cardBody).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const a = document.createElement('a');
                        a.href = imgData;
                        a.download = 'progres.png';
                        a.click();
                    });
                });

                window.onload = function() {
                    const today = new Date();
                    const currentMonth = today.getMonth() + 1;
                    const currentYear = today.getFullYear();
                    document.getElementById('monthSelect').value = currentMonth;
                    document.getElementById('yearInput').value = currentYear;
                    generateTable();
                };