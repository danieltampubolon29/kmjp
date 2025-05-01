                document.addEventListener('DOMContentLoaded', function() {
                    const pencairanId = document.getElementById('pencairan_id').value;
                    if (pencairanId) {
                        fetch(`/api/pencairan/${pencairanId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (!data.is_sequential) {
                                    alert('Error: Angsuran tidak berurutan atau ada yang kosong.');
                                    return;
                                }

                                document.getElementById('no_anggota').value = data.no_anggota;
                                document.getElementById('nama').value = data.nama;
                                document.getElementById('pinjaman_ke').value = data.pinjaman_ke;
                                document.getElementById('produk').value = data.produk;
                                document.getElementById('tenor').value = data.tenor;
                                document.getElementById('sisa_kredit').value = data
                                    .sisa_kredit;

                                document.getElementById('angsuran_ke').value = data.angsuran_ke;
                                document.getElementById('nominal').value = data
                                    .calculated_nominal;
                            })
                            .catch(error => console.error('Error fetching pencairan data:', error));
                    }
                });

                function formatToThousand(value) {
                    return new Intl.NumberFormat('id-ID').format(value);
                }

                function unformatThousand(value) {
                    return value.replace(/\./g, '');
                }

                document.getElementById('nominal').addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    e.target.value = formatToThousand(value);
                });

                document.addEventListener('DOMContentLoaded', function() {
                    const sisaKreditInput = document.getElementById('sisa_kredit');
                    if (sisaKreditInput.value) {
                        sisaKreditInput.value = formatToThousand(sisaKreditInput.value);
                    }
                });