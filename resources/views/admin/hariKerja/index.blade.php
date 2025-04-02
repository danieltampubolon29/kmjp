@extends('layouts.aplication')
@section('title', 'Hari Kerja')
@section('content')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <x-bar.navbar>Hari Kerja
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <x-alert-message></x-alert-message>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm p-3">
                                    <h5 class="card-title">Kalender</h5>
                                    <div class="wrapper">
                                        <header class="d-flex justify-content-between align-items-center">
                                            <p class="current-date mb-0"></p>
                                            <div class="icons">
                                                <span id="prev" class="material-symbols-rounded">chevron_left</span>
                                                <span id="next" class="material-symbols-rounded">chevron_right</span>
                                            </div>
                                        </header>
                                        <div class="calendar">
                                            <ul class="weeks list-unstyled d-flex justify-content-between">
                                                <li>Min</li>
                                                <li>Sen</li>
                                                <li>Sel</li>
                                                <li>Rab</li>
                                                <li>Kam</li>
                                                <li>Jum</li>
                                                <li>Sab</li>
                                            </ul>
                                            <ul class="days list-unstyled d-flex flex-wrap"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm p-3">
                                    <h5 class="card-title">Pengaturan Hari Kerja</h5>
                                    <form action="{{ route('hari-kerja.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <div class="selected-dates-container">
                                                <h6>Tanggal Dipilih:</h6>
                                                <div id="selected-dates" class="d-flex flex-column gap-2"></div>

                                                <input type="hidden" id="tanggal-input" name="tanggal">

                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Tambahkan deskripsi"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <button type="button" id="reset-button" class="btn btn-danger">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                const currentDate = document.querySelector(".current-date"),
                    daysTag = document.querySelector(".days"),
                    prevNextIcon = document.querySelectorAll(".icons span");

                let date = new Date(),
                    currYear = date.getFullYear(),
                    currMonth = date.getMonth();

                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli",
                    "Agustus", "September", "Oktober", "November", "Desember"
                ];

                let selectedDate = ""; 

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
                        const formattedDate =
                            `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                        const isHoliday = holidays.includes(formattedDate);
                        let isSelected = selectedDate === formattedDate ? "selected-date" : "";
                        let isToday = i === date.getDate() && currMonth === new Date().getMonth() &&
                            currYear === new Date().getFullYear() ? "active" : "";
                        liTag +=
                            `<li class="tanggal ${isToday} ${isHoliday ? 'holiday' : ''} ${isSelected}" data-date="${formattedDate}">${i}</li>`;
                    }

                    for (let i = lastDayofMonth; i < 6; i++) {
                        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
                    }

                    currentDate.innerText = `${months[currMonth]} ${currYear}`;
                    daysTag.innerHTML = liTag;

                    const tanggalElements = document.querySelectorAll(".tanggal");
                    tanggalElements.forEach(tanggalElement => {
                        tanggalElement.addEventListener("click", () => {
                            selectedDate = tanggalElement.getAttribute("data-date");

                            renderCalendar();
                            renderSelectedDate();
                        });
                    });
                };

                const renderSelectedDate = () => {
                    const selectedDatesContainer = document.getElementById("selected-dates");
                    selectedDatesContainer.innerHTML = "";

                    if (selectedDate) {
                        const [year, month, day] = selectedDate.split("-");
                        const monthName = months[parseInt(month) - 1];

                        const selectedDateElement = document.createElement("div");
                        selectedDateElement.classList.add("month-group");
                        selectedDateElement.innerHTML = `<strong>${monthName}:</strong> ${day}`;

                        selectedDatesContainer.appendChild(selectedDateElement);
                    }

                    document.getElementById("tanggal-input").value = selectedDate;
                };

                document.getElementById("reset-button").addEventListener("click", () => {
                    selectedDate = ""; 
                    renderCalendar();
                    renderSelectedDate();
                });

                const holidays = {!! json_encode($holidays) !!};

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

                document.querySelector("form").addEventListener("submit", () => {
                    document.getElementById("tanggal-input").value = selectedDate;
                });
            </script>

            <style>
                .wrapper {
                    background: #fff;
                    border-radius: 10px;
                    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    text-align: center;
                }

                .wrapper header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 15px;
                }

                .wrapper header .current-date {
                    font-size: 18px;
                    font-weight: bold;
                }

                .wrapper header .icons span {
                    cursor: pointer;
                    font-size: 24px;
                    color: #007bff;
                    transition: color 0.3s ease;
                }

                .wrapper header .icons span:hover {
                    color: #0056b3;
                }

                .calendar {
                    margin-top: 10px;
                }

                .weeks,
                .days {
                    display: flex;
                    justify-content: space-between;
                    flex-wrap: wrap;
                    padding: 0;
                }

                .weeks li,
                .days li {
                    list-style: none;
                    width: calc(100% / 7);
                    text-align: center;
                    margin: 5px 0;
                    font-size: 14px;
                }

                .weeks li {
                    font-weight: bold;
                    color: #333;
                }

                .days li {
                    cursor: pointer;
                    padding: 5px;
                    border-radius: 5px;
                    transition: background-color 0.3s ease, color 0.3s ease;
                }

                .days li:not(.inactive):hover {
                    background-color: #007bff;
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: auto;
                }

                .days li.inactive {
                    color: #ccc;
                    cursor: default;
                }

                .days li.active {
                    background: none;
                    color: inherit;
                }

                .days li.holiday {
                    background-color: #ff4d4d;
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: auto;
                }

                .selected-dates-container {
                    margin-top: 15px;
                }

                #selected-dates {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }

                .month-group {
                    background-color: #e7f3ff;
                    color: #333;
                    font-size: 14px;
                    padding: 10px;
                    border-radius: 5px;
                    display: flex;
                    align-items: center;
                }

                .month-group strong {
                    color: #007bff;
                    margin-right: 5px;
                }

                .card.border-0.shadow-sm {
                    border: none;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                }

                .card-title {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 15px;
                }

                .form-label {
                    font-weight: bold;
                }

                .form-control,
                .form-select {
                    border-radius: 5px;
                    border: 1px solid #ced4da;
                    padding: 10px;
                    font-size: 14px;
                }

                .btn-primary {
                    background-color: #007bff;
                    border: none;
                    padding: 10px 20px;
                    font-size: 14px;
                    border-radius: 5px;
                    transition: background-color 0.3s ease;
                }

                .btn-primary:hover {
                    background-color: #0056b3;
                }

                .btn-danger {
                    background-color: #dc3545;
                    border: none;
                    padding: 10px 20px;
                    font-size: 14px;
                    border-radius: 5px;
                    transition: background-color 0.3s ease;
                }

                .btn-danger:hover {
                    background-color: #bd2130;
                }
            </style>
        </x-slot>
    </x-bar.navbar>
@endsection
