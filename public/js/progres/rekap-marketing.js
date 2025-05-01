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
    const downloadBtn = document.getElementById("downloadBtn");

    let marketings = [];

    async function fetchMarketings() {
        try {
            const month = parseInt(monthSelect.value);
            const year = parseInt(yearInput.value);

            const response = await fetch(
                `/rekap-data/get-rekap-marketing?month=${month}&year=${year}`
            );
            if (!response.ok) throw new Error("Gagal mengambil data");
            const result = await response.json();

            marketings = result.marketings || [];

            updateHeaders();
            updateFooter();
            renderTable(month, year);
            isiDataPencairan(result.pencairanData);
            isiDataAngsuran(result.angsuranData);
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
            const dateObj = new Date(year, month - 1, i);
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
            row.dataset.tanggal = iso;

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
        dataAngsuran.forEach(({ marketing_id, date, total_nominal })=>  {
            const cell = tableBody.querySelector(
                `td[data-marketing="${marketing_id}"][data-type="angsuran"][data-tanggal="${date}"]`
            );
            if (cell) {
                cell.textContent = formatCurrency(total_nominal);
            }
            if (!totalPerMarketing[marketing_id]) totalPerMarketing[marketing_id] = 0;
            totalPerMarketing[marketing_id] += Number(total_nominal);
        });
        for (const id in totalPerMarketing) {
            const cell = tableFooterRow.querySelector(
                `td[data-marketing="${id}"][data-type="angsuran"]`
            );
            if (cell) cell.textContent = formatCurrency(totalPerMarketing[id]);
        }
    }

    function formatCurrency(value) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(value);
    }

    downloadBtn.addEventListener("click", function () {
        const container = document.querySelector(".card-body");
        html2canvas(container).then((canvas) => {
            const link = document.createElement("a");
            link.href = canvas.toDataURL("image/png");
            link.download = `rekap-data-${monthSelect.value}-${yearInput.value}.png`;
            link.click();
        });
    });

    monthSelect.addEventListener("change", fetchMarketings);
    yearInput.addEventListener("input", fetchMarketings);

    // Load awal
    const today = new Date();
    monthSelect.value = today.getMonth() + 1;
    yearInput.value = today.getFullYear();
    fetchMarketings();
});
