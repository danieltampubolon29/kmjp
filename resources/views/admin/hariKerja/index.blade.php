@extends('layouts.aplication')
@section('title', 'Hari Kerja')
@section('content')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <x-bar.navbar>Hari Libur
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-1 shadow-sm p-3">
                                    <h5 class="card-title">Kalender Hari Kerja</h5>
                                    <div class="wrapper">
                                        <header>
                                            <p class="current-date mt-3"></p>
                                            <div class="icons">
                                                <span id="prev"
                                                    class="text-primary material-symbols-rounded">chevron_left</span>
                                                <span id="next"
                                                    class="text-primary material-symbols-rounded">chevron_right</span>
                                            </div>
                                        </header>
                                        <div class="calendar">
                                            <ul class="weeks">
                                                <li>Min</li>
                                                <li>Sen</li>
                                                <li>Sel</li>
                                                <li>Rab</li>
                                                <li>Kam</li>
                                                <li>Jum</li>
                                                <li>Sab</li>
                                            </ul>
                                            <ul class="days"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-1 shadow-sm p-3 mb-3">
                                    <h5 class="card-title">Informasi Hari Libur</h5>
                                    <div class="card-text">
                                        <ul id="holiday-list">
                                            <li>Loading...</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card border-1 shadow-sm p-3">
                                    <div class="mt-2">
                                        <x-alert-message></x-alert-message>
                                    </div>
                                    <h5 class="card-title">Pengaturan Hari Libur</h5>
                                    <form action="{{ route('hari-kerja.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <div class="selected-dates-container d-flex align-items-center flex-wrap">
                                                <h6 class="mt-2 d-inline-block me-2">Tanggal Dipilih:</h6>
                                                <small id="select-info" style="font-size: 12px;"
                                                    class="text-muted me-2">(Klik tanggal pada kalender)</small>
                                                <div id="selected-dates" class="d-inline-block"></div>
                                                <div id="tanggal-input-container"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Tambahkan deskripsi"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .calendar .days li.holiday::before {
                    position: absolute;
                    background: red;
                    content: "";
                    left: 50%;
                    top: 50%;
                    height: 40px;
                    width: 40px;
                    z-index: -1;
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                }

                .calendar .days li.holiday {
                    color: white;
                }

                .calendar .days li.holiday:hover::before {
                    background: rgb(190, 1, 1);
                }

                :root {
                    --primary-color: var(--bs-primary);
                }

                .modal-dialog {
                    max-width: 500px;
                    width: 100%;
                }

                .modal-content {
                    border-radius: 10px;
                    overflow: hidden;
                }

                .modal-body {
                    padding: 0 !important;
                }

                .wrapper {
                    width: 100%;
                    background: #fff;
                    border-radius: 10px;
                    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
                }

                .wrapper header {
                    display: flex;
                    align-items: center;
                    padding: 20px;
                    justify-content: space-between;
                    background: white;
                    color: black;
                    border-top-left-radius: 10px;
                    border-top-right-radius: 10px;
                }

                header .icons {
                    display: flex;
                }

                header .icons span {
                    height: 38px;
                    width: 38px;
                    cursor: pointer;
                    color: black;
                    text-align: center;
                    line-height: 38px;
                    font-size: 1.9rem;
                    user-select: none;
                    border-radius: 50%;
                }

                header .icons span:hover {
                    background: rgba(255, 255, 255, 0.2);
                }

                header .current-date {
                    font-size: 1.45rem;
                    font-weight: 500;
                }

                .calendar {
                    padding: 20px;
                    margin-left: -35px;
                }

                .calendar ul {
                    display: flex;
                    flex-wrap: wrap;
                    list-style: none;
                    text-align: center;
                }

                .calendar .days {
                    margin-bottom: 20px;
                }

                .calendar li {
                    color: #333;
                    width: calc(100% / 7);
                    font-size: 1.1rem;
                }

                .calendar .weeks li {
                    font-weight: 600;
                    cursor: default;
                }

                .calendar .days li {
                    z-index: 1;
                    cursor: pointer;
                    position: relative;
                    margin-top: 30px;
                }

                .days li.inactive {
                    color: #aaa;
                }

                .days li.active {
                    color: #fff;
                }

                .days li::before {
                    position: absolute;
                    content: "";
                    left: 50%;
                    top: 50%;
                    height: 40px;
                    width: 40px;
                    z-index: -1;
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                }

                .days li.active::before {
                    background: var(--primary-color);
                }

                .days li:not(.active):hover::before {
                    background: var(--primary-color);
                }

                #selected-dates .selected-date {
                    color: red;
                }

                @media (max-width: 768px) {
                    .calendar ul {
                        font-size: 0.9rem;
                    }

                    .calendar li {
                        font-size: 0.85rem;
                    }

                    .days li::before {
                        height: 30px;
                        width: 30px;
                    }

                    header .current-date {
                        font-size: 1.2rem;
                    }

                    header .icons span {
                        font-size: 1.5rem;
                        height: 30px;
                        width: 30px;
                        line-height: 30px;
                    }
                }

                @media (max-width: 480px) {
                    .calendar ul {
                        font-size: 0.8rem;
                    }

                    .calendar li {
                        font-size: 0.75rem;
                    }

                    .days li::before {
                        height: 25px;
                        width: 25px;
                    }

                    header .current-date {
                        font-size: 1rem;
                    }

                    header .icons span {
                        font-size: 1.2rem;
                        height: 25px;
                        width: 25px;
                        line-height: 25px;
                    }
                }
            </style>
            <script>
                // Array untuk menyimpan tanggal yang dipilih
                let selectedDates = [];

                const holidayList = document.getElementById("holiday-list");
                const groupHolidays = (holidays) => {
                    holidays.sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));
                    const groupedHolidays = [];
                    let currentGroup = null;
                    holidays.forEach(holiday => {
                        const date = new Date(holiday.tanggal);
                        const day = date.getDate();
                        const month = date.getMonth();
                        const year = date.getFullYear();
                        if (currentGroup &&
                            currentGroup.deskripsi === holiday.deskripsi &&
                            new Date(currentGroup.endDate).getDate() + 1 === day &&
                            new Date(currentGroup.endDate).getMonth() === month &&
                            new Date(currentGroup.endDate).getFullYear() === year) {
                            currentGroup.endDate = holiday.tanggal;
                        } else {
                            currentGroup = {
                                startDate: holiday.tanggal,
                                endDate: holiday.tanggal,
                                deskripsi: holiday.deskripsi
                            };
                            groupedHolidays.push(currentGroup);
                        }
                    });
                    return groupedHolidays;
                };

                const updateHolidayList = () => {
                    holidayList.innerHTML = "";
                    let filteredHolidays = holidays
                        .filter(holiday => {
                            let date = new Date(holiday.tanggal);
                            return date.getFullYear() === currYear && date.getMonth() === currMonth;
                        });
                    let groupedHolidays = groupHolidays(filteredHolidays);
                    if (groupedHolidays.length === 0) {
                        holidayList.innerHTML = "<li>Tidak ada hari libur bulan ini</li>";
                    } else {
                        groupedHolidays.forEach(group => {
                            const startDate = new Date(group.startDate);
                            const endDate = new Date(group.endDate);
                            let formattedDateRange;
                            if (startDate.getTime() === endDate.getTime()) {
                                formattedDateRange =
                                    `${startDate.getDate()} ${months[startDate.getMonth()]} ${startDate.getFullYear()}`;
                            } else {
                                formattedDateRange = `${startDate.getDate()}-${endDate.getDate()} ${
                    months[startDate.getMonth()]
                } ${startDate.getFullYear()}`;
                            }
                            holidayList.innerHTML +=
                                `<li><strong>${formattedDateRange}</strong>: ${group.deskripsi}</li>`;
                        });
                    }
                };

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
                        let dateString = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                        let isHoliday = holidays.some(holiday => holiday.tanggal === dateString);
                        let holidayClass = isHoliday ? "holiday" : "";
                        liTag += `<li class="${holidayClass}" data-date="${dateString}">${i}</li>`;
                    }
                    for (let i = lastDayofMonth; i < 6; i++) {
                        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
                    }
                    currentDate.innerText = `${months[currMonth]} ${currYear}`;
                    daysTag.innerHTML = liTag;
                    document.querySelectorAll('.days li').forEach(day => {
                        if (!day.classList.contains('inactive')) {
                            day.addEventListener('click', handleDateClick);
                        }
                    });
                    updateHolidayList();
                };

                const handleDateClick = (event) => {
                    const selectedDate = event.target.getAttribute('data-date');
                    if (selectedDates.includes(selectedDate)) {
                        selectedDates = selectedDates.filter(date => date !== selectedDate);
                        event.target.classList.remove("selected");
                    } else {
                        selectedDates.push(selectedDate);
                        event.target.classList.add("selected");
                    }
                    updateSelectedDatesDisplay();
                };

                const updateSelectedDatesDisplay = () => {
                    const selectedDatesDiv = document.getElementById('selected-dates');
                    const tanggalInputContainer = document.getElementById('tanggal-input-container');

                    // Kosongkan konten sebelumnya
                    selectedDatesDiv.innerHTML = '';
                    tanggalInputContainer.innerHTML = '';

                    if (selectedDates.length > 0) {
                        // Kelompokkan tanggal berdasarkan bulan
                        const groupedDates = {};
                        selectedDates.forEach(date => {
                            const formattedDate = new Date(date);
                            const month = months[formattedDate.getMonth()];
                            const day = String(formattedDate.getDate()).padStart(2, '0');

                            if (!groupedDates[month]) {
                                groupedDates[month] = [];
                            }
                            groupedDates[month].push(day);
                        });

                        // Tampilkan tanggal yang telah dikelompokkan
                        let displayText = '';
                        for (const [month, days] of Object.entries(groupedDates)) {
                            displayText += `<div><strong>${month}:</strong> ${days.join(', ')}</div>`;
                        }

                        selectedDatesDiv.innerHTML = displayText;

                        // Tambahkan input hidden untuk setiap tanggal
                        selectedDates.forEach(date => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'tanggal[]';
                            input.value = date;
                            tanggalInputContainer.appendChild(input);
                        });

                        // Sembunyikan teks "(Klik tanggal pada kalender)"
                        document.getElementById('select-info').style.display = 'none';
                    } else {
                        selectedDatesDiv.innerHTML = '<small>Tidak ada tanggal yang dipilih</small>';
                        document.getElementById('select-info').style.display = 'inline';
                    }
                };

                const formatDate = (isoDate) => {
                    const date = new Date(isoDate);
                    const day = date.getDate();
                    const month = months[date.getMonth()];
                    const year = date.getFullYear();
                    return `${day} ${month} ${year}`;
                };

                const daysTag = document.querySelector(".days"),
                    currentDate = document.querySelector(".current-date"),
                    prevNextIcon = document.querySelectorAll(".icons span");

                const holidays = @json($holidays);
                let date = new Date(),
                    currYear = date.getFullYear(),
                    currMonth = date.getMonth();

                const months = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

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

                renderCalendar();
            </script>
        </x-slot>
    </x-bar.navbar>
@endsection
