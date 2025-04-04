document.getElementById('resetButton').addEventListener('click', () => {
    document.getElementById('tableBody').innerHTML = '';
    selectedDate = null;
    selectedDateEl.innerText = '-';
    selectedDayEl.innerText = '-';
    document.getElementById('totalNominal').innerText = '-';
    date = new Date();
    currYear = date.getFullYear();
    currMonth = date.getMonth();
    renderCalendar();
});

const daysTag = document.querySelector(".days"),
    currentDate = document.querySelector(".current-date"),
    prevNextIcon = document.querySelectorAll(".icons span"),
    selectedDateEl = document.getElementById("selectedDate"),
    selectedDayEl = document.getElementById("selectedDay");

let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth(),
    selectedDate = null;

let currentPage = 1; 
let totalPages = 1; 

const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli",
    "Agustus", "September", "Oktober", "November", "Desember"
];

const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
    let liTag = "";

    for (let i = firstDayofMonth; i > 0; i--) {
        liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateofMonth; i++) {
        let isToday = i === date.getDate() && currMonth === new Date().getMonth() && currYear === new Date()
            .getFullYear() ? "active" : "";
        let isSelected = selectedDate ===
            `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}` ?
            "selected active" : "";
        liTag +=
            `<li class="${isToday} ${isSelected}" data-date="${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}">${i}</li>`;
    }

    for (let i = lastDayofMonth; i < 6; i++) {
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
    }

    currentDate.innerText = `${months[currMonth]} ${currYear}`;
    daysTag.innerHTML = liTag;
    attachDateListeners();
};

function attachDateListeners() {
    document.querySelectorAll(".days li").forEach(day => {
        day.addEventListener("click", async function () {
            if (!this.classList.contains("inactive")) {
                document.querySelectorAll(".days li").forEach(item => {
                    item.classList.remove("active");
                });

                this.classList.add("active");

                selectedDate = this.dataset.date;

                let dateParts = selectedDate.split("-");
                let formattedDate =
                    `${dateParts[2]} ${months[dateParts[1] - 1]} ${dateParts[0]}`;
                let dayOfWeek = new Date(selectedDate).toLocaleDateString("id-ID", {
                    weekday: 'long'
                });
                selectedDateEl.innerText = formattedDate;
                selectedDayEl.innerText = dayOfWeek;

                try {
                    const response = await fetch(`/laporan/get-angsuran-by-date`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            tanggal_laporan: selectedDate
                        })
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        populateTable(result.data);
                    } else {
                        populateTable([]);
                    }
                } catch (error) {
                    console.error('Error fetching data:', error);
                    alert('Terjadi kesalahan saat memuat data angsuran.');
                }

                $("#dateModal").modal("hide");
            }
        });
    });
}

function populateTable(data) {
    const tableBody = document.getElementById('tableBody');
    const pagination = document.getElementById('pagination');

    tableBody.innerHTML = '';
    pagination.innerHTML = '';

    totalPages = Math.ceil(data.length / 10);

    const startIndex = (currentPage - 1) * 10;
    const endIndex = startIndex + 10;
    const pageData = data.slice(startIndex, endIndex);

    let totalNominal = 0;

    for (let i = 0; i < 10; i++) {
        const row = document.createElement('tr');
        if (i < pageData.length) {
            const item = pageData[i];
            const formatNominal = (value) => value.toLocaleString('id-ID');
            totalNominal += item.nominal;
            row.innerHTML = `
                <td style="border: 3px solid black;">${startIndex + i + 1}</td>
                <td style="border: 3px solid black;">${item.no_anggota || '-'}</td>
                <td style="border: 3px solid black;">${item.pinjaman_ke || '-'}</td>
                <td style="border: 3px solid black;">${item.nama || '-'}</td>
                <td style="border: 3px solid black;">${formatNominal(item.nominal) || '-'}</td>
                <td style="border: 3px solid black;">${item.angsuran_ke || '-'}</td>
                <td style="border: 3px solid black;">${item.tenor || '-'}</td>
            `;
        } else {
            row.innerHTML = `
                <td style="border: 3px solid black;">${startIndex + i + 1}</td>
                <td style="border: 3px solid black;">-</td>
                <td style="border: 3px solid black;">-</td>
                <td style="border: 3px solid black;">-</td>
                <td style="border: 3px solid black;">-</td>
                <td style="border: 3px solid black;">-</td>
                <td style="border: 3px solid black;">-</td>
            `;
        }
        tableBody.appendChild(row);
    }

    document.getElementById('totalNominal').innerText = totalNominal > 0 ? totalNominal.toLocaleString('id-ID') : '-';

    for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
        pagination.appendChild(li);
    }

    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            currentPage = parseInt(this.getAttribute('data-page'));
            populateTable(data); 
        });
    });
}

renderCalendar();

prevNextIcon.forEach(icon => {
    icon.addEventListener("click", () => {
        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;
        if (currMonth < 0 || currMonth > 11) {
            date = new Date(currYear, currMonth, new Date().getDate());
            currYear = date.getFullYear();
            currMonth = date.getMonth();
        } else {
            date = new Date();
        }
        renderCalendar();
    });
});

document.getElementById('downloadBtn').addEventListener('click', function () {
    const cardBody = document.getElementById('cardBody');
    if (!cardBody) {
        console.error('Element with ID "cardBody" not found!');
        return;
    }

    const originalWidth = cardBody.style.width;
    const originalHeight = cardBody.style.height;

    cardBody.style.width = '1463px';
    cardBody.style.height = '1042px';

    html2canvas(cardBody, {
        scale: 1.5 
    }).then(function (canvas) {
        cardBody.style.width = originalWidth;
        cardBody.style.height = originalHeight;

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a5',
            compress: true 
        });

        const imgData = canvas.toDataURL('image/jpeg', 0.9); 
        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);

        let fileName = generateDynamicFileName();
        pdf.save(fileName);
    }).catch(function (error) {
        console.error('Error generating canvas:', error);
    });
});

function generateDynamicFileName() {
    const selectedDateEl = document.getElementById('selectedDate');
    let selectedDate = selectedDateEl.innerText.trim();

    if (selectedDate === '-' || selectedDate === '') {
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = months[today.getMonth()];
        const year = today.getFullYear();
        return `Ang-${day}-${month}-${year}.pdf`;
    }

    const dateParts = selectedDate.split(' ');
    const day = dateParts[0];
    const month = dateParts[1];
    const year = dateParts[2];

    return `Ang-${day}-${month}-${year}.pdf`;
}