@extends('layouts.aplication')
@section('title', 'Laporan')
@section('content')
    {{-- <link rel="stylesheet" href="{{ asset('css/calender.css') }}"> --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <x-bar.navbar>Laporan Angsuran
        <x-slot name="content">
            <div class="container mb-5">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <button class="btn btn-primary" id="downloadBtn">
                            <i class="ri-download-2-line"></i>
                        </button>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dateModal">
                                Pilih Tanggal
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="cardBody">
                        <div class="text-center mb-3">
                            <h5 class="fw-bold">LAPORAN ANGSURAN HARIAN MARKETING</h5>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <strong>Marketing:</strong> <span id="marketingName">{{ Auth::user()->name }}</span>
                            </div>
                            <div>
                                <strong>Tanggal:</strong> <span id="selectedDate">-</span>
                            </div>
                            <div>
                                <strong>Hari:</strong> <span id="selectedDay">-</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="dynamicTable" class="table table-bordered">
                                <thead>
                                    <tr class="text-center align-middle">
                                        <th>NO</th>
                                        <th>NO ANGGOTA</th>
                                        <th>PINJAMAN KE</th>
                                        <th>Nama</th>
                                        <th>Nominal</th>
                                        <th>Simpanan</th>
                                        <th>Angsuran Ke</th>
                                        <th>Tenor</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @for ($i = 1; $i <= 20; $i++)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endfor

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="fw-bold text-start">Total</td>
                                        <td id="totalAngsuran" class="fw-bold border">-</td>
                                        <td id="totalSimpanan" class="fw-bold border">-</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row mt-5">
                            <div class="col-6 text-center">
                                <strong>Marketing</strong>
                                <div class="signature-box mt-5">
                                    <span class="bracket">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </span>
                                </div>
                                <p class="mt-3" id="marketingName">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="col-6 text-center">
                                <strong>Koordinator</strong>
                                <div class="signature-box mt-5">
                                    <span class="bracket">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </span>
                                </div>
                                <p class="mt-3">_ _ _ _ _ _ _ _ _ _</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <div class="wrapper">
                                <header>
                                    <p class="current-date"></p>
                                    <div class="icons">
                                        <span id="prev" class="material-symbols-rounded">chevron_left</span>
                                        <span id="next" class="material-symbols-rounded">chevron_right</span>
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
                </div>
            </div>
            <script>
                document.getElementById('downloadBtn').addEventListener('click', function() {
                    const cardBody = document.getElementById('cardBody');

                    html2canvas(cardBody, {
                        scale: 2
                    }).then(canvas => {
                        const image = canvas.toDataURL('image/png');

                        const link = document.createElement('a');
                        link.href = image;
                        link.download = 'laporan-angsuran.png';
                        link.click();
                    });
                });
            </script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        </x-slot>
    </x-bar.navbar>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

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
            background: var(--primary-color);
            color: white;
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
            color: white;
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
            background: #f2f2f2;
        }
    </style>

    <script>
        const daysTag = document.querySelector(".days"),
            currentDate = document.querySelector(".current-date"),
            prevNextIcon = document.querySelectorAll(".icons span"),
            selectedDateEl = document.getElementById("selectedDate"),
            selectedDayEl = document.getElementById("selectedDay");

        let date = new Date(),
            currYear = date.getFullYear(),
            currMonth = date.getMonth(),
            selectedDate = null;

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
                let isSelected = selectedDate === `${currYear}-${currMonth + 1}-${i}` ? "selected" : "";
                liTag += `<li class="${isToday} ${isSelected}" data-date="${currYear}-${currMonth + 1}-${i}">${i}</li>`;
            }

            for (let i = lastDayofMonth; i < 6; i++) {
                liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
            }
            currentDate.innerText = `${months[currMonth]} ${currYear}`;
            daysTag.innerHTML = liTag;

            document.querySelectorAll(".days li").forEach(day => {
                day.addEventListener("click", function() {
                    if (!this.classList.contains("inactive")) {
                        selectedDate = this.dataset.date;
                        let dateParts = selectedDate.split("-");
                        let formattedDate =
                            `${dateParts[2]} ${months[dateParts[1] - 1]} ${dateParts[0]}`;
                        let dayOfWeek = new Date(selectedDate).toLocaleDateString("id-ID", {
                            weekday: 'long'
                        });
                        selectedDateEl.innerText = formattedDate;
                        selectedDayEl.innerText = dayOfWeek;
                        renderCalendar();
                        $("#dateModal").modal("hide");
                    }
                });
            });
        };
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
    </script>
    {{-- <script src="{{ asset('js/calender.js') }}"></script> --}}
@endsection
