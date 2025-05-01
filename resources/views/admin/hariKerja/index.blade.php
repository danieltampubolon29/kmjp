@extends('layouts.aplication')
@section('title', 'Hari Kerja')
@section('content')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <x-bar.navbar> Hari Kerja
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-1 shadow-sm p-3">
                                    <h5 class="card-title">Kalender</h5>
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
                                @if (auth()->check() && auth()->user()->role === 'admin')
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
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
                    const liElement = event.target;

                    if (selectedDates.includes(selectedDate)) {
                        selectedDates = selectedDates.filter(date => date !== selectedDate);
                        liElement.classList.remove("active");

                        if (holidays.some(holiday => holiday.tanggal === selectedDate)) {
                            liElement.classList.add("holiday");
                        }
                    } else {
                        selectedDates.push(selectedDate);

                        liElement.classList.remove("holiday");
                        liElement.classList.add("active");
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
            <link rel="stylesheet" href="{{ asset('css/hariKerja/styles.css') }}">
        </x-slot>
    </x-bar.navbar>
@endsection
