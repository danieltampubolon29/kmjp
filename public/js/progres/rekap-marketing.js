document.addEventListener("DOMContentLoaded", function () {
    const monthSelect = document.getElementById("monthSelect");
    const yearInput = document.getElementById("yearInput");
    const tableBody = document.getElementById("tableBody");
    const tableHeaderRow = document
        .getElementById("tableHeader")
        .querySelector("tr");
    const tableSubHeaderRow = document.getElementById("tableSubHeader");
    const tableFooterRow = document
        .getElementById("tableFooter")
        .querySelector("tr");
    const mainTableBody = document.querySelector("tbody"); 
    let marketings = [];

    function formatCurrency(value) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(value);
    }

    function isiDataRekapPerMarketing(rekapPerMarketing) {
        const tbody = document.querySelector("tbody");

        while (tbody.rows.length > 2) {
            tbody.deleteRow(-1);
        }

        rekapPerMarketing.forEach((marketingData) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td class="text-start">${marketingData.name}</td>
                <td>${marketingData.nasabah_baru || 0}</td>
                <td>${formatCurrency(marketingData.nominal_pencairan || 0)}</td>
                <td>${formatCurrency(marketingData.saldo_awal || 0)}</td>
                
                <td>${marketingData.jumlah_pencairan || 0}</td>
                <td>${formatCurrency(marketingData.saldo_berjalan || 0)}</td>
                <td>${marketingData.nasabah_bayar || 0}</td>
                <td>${formatCurrency(marketingData.nominal_angsuran || 0)}</td>

            `;
            tbody.insertBefore(row, tbody.querySelector("tr.fw-bold"));
        });
    }

    function isiTotalData(rekapUtama) {
        const totalRow = document.querySelector("tr.fw-bold");

        if (!totalRow) {
            console.warn("Baris TOTAL tidak ditemukan di tabel.");
            return;
        }

        for (let i = 1; i < totalRow.cells.length; i++) {
            totalRow.cells[i].textContent = "";
        }

        totalRow.cells[1].textContent = rekapUtama.total_nasabah_baru ?? 0;
        totalRow.cells[2].textContent = formatCurrency(
            rekapUtama.total_nominal_pencairan ?? 0
        );
        totalRow.cells[3].textContent = formatCurrency(
            rekapUtama.total_saldo_awal ?? 0
        );
        totalRow.cells[4].textContent = rekapUtama.total_jumlah_pencairan ?? 0;
        totalRow.cells[5].textContent = formatCurrency(
            rekapUtama.total_saldo_berjalan ?? 0
        );
        totalRow.cells[6].textContent = rekapUtama.total_nasabah_bayar ?? 0;
        totalRow.cells[7].textContent = formatCurrency(
            rekapUtama.total_nominal_angsuran ?? 0
        );
    }

    function resetTabelMarketing() {
        const tbody = document.querySelector("tbody");
        if (tbody) {
            const rows = Array.from(tbody.rows);
            rows.forEach((row) => {
                if (!row.classList.contains("fw-bold")) {
                    row.remove();
                }
            });
        }

        if (tableHeaderRow) tableHeaderRow.innerHTML = "";
        if (tableSubHeaderRow) tableSubHeaderRow.innerHTML = "";
        if (tableFooterRow) tableFooterRow.innerHTML = "";
    }

    function isiDataPencairan(dataPencairan) {
        const totalPerMarketing = {};
        dataPencairan.forEach(({ marketing_id, date, total_nominal }) => {
            const cell = tableBody.querySelector(
                `td[data-marketing="${marketing_id}"][data-type="pencairan"][data-tanggal="${date}"]`
            );
            if (cell) {
                cell.textContent = formatCurrency(total_nominal);
            }
            if (!totalPerMarketing[marketing_id])
                totalPerMarketing[marketing_id] = 0;
            totalPerMarketing[marketing_id] += Number(total_nominal);
        });

        for (const id in totalPerMarketing) {
            const cell = tableFooterRow.querySelector(
                `td[data-marketing="${id}"][data-type="pencairan"]`
            );
            if (cell) cell.textContent = formatCurrency(totalPerMarketing[id]);
        }
    }

    function isiDataAngsuran(dataAngsuran) {
        const totalPerMarketing = {};
        dataAngsuran.forEach(({ marketing_id, date, total_nominal }) => {
            const cell = tableBody.querySelector(
                `td[data-marketing="${marketing_id}"][data-type="angsuran"][data-tanggal="${date}"]`
            );
            if (cell) {
                cell.textContent = formatCurrency(total_nominal);
            }
            if (!totalPerMarketing[marketing_id])
                totalPerMarketing[marketing_id] = 0;
            totalPerMarketing[marketing_id] += Number(total_nominal);
        });

        for (const id in totalPerMarketing) {
            const cell = tableFooterRow.querySelector(
                `td[data-marketing="${id}"][data-type="angsuran"]`
            );
            if (cell) cell.textContent = formatCurrency(totalPerMarketing[id]);
        }
    }

    async function fetchMarketings() {
        try {
            marketings = [];
            const month = parseInt(monthSelect.value);
            const year = parseInt(yearInput.value);

            const response = await fetch(
                `/rekap-data/get-rekap-marketing?month=${month}&year=${year}`
            );
            if (!response.ok) throw new Error("Gagal mengambil data");

            const result = await response.json();

            marketings = result.marketings || [];

            resetTabelMarketing(); 
            updateHeaders();
            updateFooter();
            renderTable(month, year);

            isiDataPencairan(result.pencairanData);
            isiDataAngsuran(result.angsuranData);
            isiDataRekapPerMarketing(result.rekapUtama.rekap_per_marketing);
            isiTotalData(result.rekapUtama); 
        } catch (error) {
            console.error(error);
            alert("Terjadi kesalahan saat mengambil data.");
        }
    }

    function updateHeaders() {
        tableHeaderRow.innerHTML =
            '<th rowspan="2" class="text-center align-middle">Tanggal</th>';
        tableSubHeaderRow.innerHTML = "";

        marketings.forEach((marketing) => {
            const th = document.createElement("th");
            th.setAttribute("colspan", 2);
            th.className = "text-center align-middle";
            th.textContent = marketing.name;
            tableHeaderRow.appendChild(th);

            const pencairanTh = document.createElement("th");
            pencairanTh.className = "text-center align-middle";
            pencairanTh.textContent = "Pencairan";
            pencairanTh.dataset.id = marketing.id;
            tableSubHeaderRow.appendChild(pencairanTh);

            const angsuranTh = document.createElement("th");
            angsuranTh.className = "text-center align-middle";
            angsuranTh.textContent = "Angsuran";
            angsuranTh.dataset.id = marketing.id;
            tableSubHeaderRow.appendChild(angsuranTh);
        });
    }

    function updateFooter() {
        tableFooterRow.innerHTML =
            '<td class="text-center align-middle"><strong>Total</strong></td>';

        marketings.forEach((marketing) => {
            const tdPencairan = document.createElement("td");
            tdPencairan.className = "fw-bold totalPencairan text-end";
            tdPencairan.dataset.marketing = marketing.id;
            tdPencairan.dataset.type = "pencairan";
            tdPencairan.textContent = "-";

            const tdAngsuran = document.createElement("td");
            tdAngsuran.className = "fw-bold totalAngsuran text-end";
            tdAngsuran.dataset.marketing = marketing.id;
            tdAngsuran.dataset.type = "angsuran";
            tdAngsuran.textContent = "-";

            tableFooterRow.appendChild(tdPencairan);
            tableFooterRow.appendChild(tdAngsuran);
        });
    }

    function generateDates(month, year) {
        const daysInMonth = new Date(year, month, 0).getDate();
        const dates = [];
        for (let i = 1; i <= daysInMonth; i++) {
            const iso = `${year}-${String(month).padStart(2, "0")}-${String(
                i
            ).padStart(2, "0")}`;
            dates.push({ display: `${i}/${month}/${year}`, iso });
        }
        return dates;
    }

    function renderTable(month, year) {
        const dates = generateDates(month, year);
        tableBody.innerHTML = "";

        dates.forEach(({ display, iso }) => {
            const row = document.createElement("tr");
            const tdDate = document.createElement("td");
            tdDate.className = "text-center align-middle";
            tdDate.textContent = display;
            row.appendChild(tdDate);

            marketings.forEach((marketing) => {
                const tdPencairan = document.createElement("td");
                tdPencairan.className = "text-end";
                tdPencairan.dataset.marketing = marketing.id;
                tdPencairan.dataset.type = "pencairan";
                tdPencairan.dataset.tanggal = iso;
                tdPencairan.textContent = "-";

                const tdAngsuran = document.createElement("td");
                tdAngsuran.className = "text-end";
                tdAngsuran.dataset.marketing = marketing.id;
                tdAngsuran.dataset.type = "angsuran";
                tdAngsuran.dataset.tanggal = iso;
                tdAngsuran.textContent = "-";

                row.appendChild(tdPencairan);
                row.appendChild(tdAngsuran);
            });

            tableBody.appendChild(row);
        });
    }

    function updateJudulLaporan() {
        const monthNames = [
            "JANUARY",
            "FEBRUARY",
            "MARCH",
            "APRIL",
            "MAY",
            "JUNE",
            "JULY",
            "AUGUST",
            "SEPTEMBER",
            "OCTOBER",
            "NOVEMBER",
            "DECEMBER",
        ];
        const month = parseInt(monthSelect.value);
        const year = parseInt(yearInput.value);
        const bulanText = monthNames[month - 1] || "Bulan Tidak Valid";
        const titleElement = document.getElementById("judulLaporan");

        if (titleElement) {
            titleElement.textContent = `HASIL ANALISA OPERASIONAL KOPERASI MANDIRI JAYA PRATAMA ${bulanText} ${year}`;
        }
    }

    monthSelect.addEventListener("change", () => {
        updateJudulLaporan();
        resetTabelMarketing();
        fetchMarketings();
    });

    yearInput.addEventListener("input", () => {
        updateJudulLaporan();
        resetTabelMarketing();
        fetchMarketings();
    });

    resetTabelMarketing();
    const today = new Date();
    monthSelect.value = today.getMonth() + 1;
    yearInput.value = today.getFullYear();
    updateJudulLaporan();
    fetchMarketings();
});

const monthSelect = document.getElementById("monthSelect");
const yearInput = document.getElementById("yearInput");

const monthNames = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
];

// 🔁 Fungsi global untuk ambil bulan & tahun yang dipilih
function getSelectedMonthYear() {
    const month = parseInt(monthSelect.value);
    const year = parseInt(yearInput.value);
    const bulanText = monthNames[month - 1] || "BulanTidakValid";
    return { month, year, bulanText };
}

// 📥 Event: Download Progres
document.getElementById("downloadBtn").addEventListener("click", function () {
    const container = document.querySelector(".card-body2");
    const { month, year, bulanText } = getSelectedMonthYear();

    html2canvas(container).then((canvas) => {
        const link = document.createElement("a");
        link.href = canvas.toDataURL("image/png");
        link.download = `progres-${bulanText}-${year}.png`;
        link.click();
    });
});

// 📥 Event: Download Rekap Utama
document.getElementById("rekapUtama").addEventListener("click", function () {
    const container = document.querySelector(".card-body1");
    const { month, year, bulanText } = getSelectedMonthYear();

    html2canvas(container).then((canvas) => {
        const link = document.createElement("a");
        link.href = canvas.toDataURL("image/png");
        link.download = `rekap-${bulanText}-${year}.png`;
        link.click();
    });
});
