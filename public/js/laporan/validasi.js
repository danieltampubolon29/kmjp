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

            }
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

