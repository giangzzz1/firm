@extends('LayoutUser.master')

@section('title')
    Điểm danh
@endsection

@section('content')
    <?php
    use Carbon\Carbon;
    ?>
    <div class="container">
        <h2 class="text-center">Điểm Danh Nhận Thưởng</h2>

        <div class="row mt-4">
            @foreach (range(0, 6) as $day)
                @php
                    $date = Carbon::now()->startOfWeek()->addDays($day);
                    $attended = $user->attendances()->whereDate('date', $date)->exists(); // Kiểm tra đã điểm danh
                    $today = Carbon::today();
                    $isPastDay = $date->isBefore($today);
                    $isFutureDay = $date->isAfter($today);
                @endphp

                <div class="day col-md-1 text-center">
                    <div
                        class="day {{ $attended ? 'attended' : '' }} {{ $today->isSameDay($date) ? 'today' : '' }} {{ $isFutureDay ? 'future-day' : '' }} {{ $isPastDay ? 'past-day' : '' }}">
                        <div class="day-header">
                            {{ $date->format('D') }}
                        </div>

                        @if ($attended)
                            <span class="text-success">✔</span>
                        @else
                            @if ($isPastDay)
                                {{-- Nút điểm danh bù --}}
                                <form action="{{ route('diemdanh.makeup') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $date }}">
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        Makeup
                                    </button>
                                </form>
                            @elseif (!$isFutureDay)
                                {{-- Nút điểm danh hiện tại --}}
                                <form action="{{ route('diemdanh.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $date }}">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Mark
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Hiển thị tổng điểm -->
        <p class="text-center mt-4">Điểm thưởng của bạn: {{ $userPoints }}</p>
        @if ($userPoints >= 10000)
            <form action="{{ route('diemdanh.exchange') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Đổi 10,000 điểm thành 1 USD</button>
            </form>
        @else
            <p class="text-muted">Bạn cần tối thiểu 10,000 điểm để đổi thưởng.</p>
        @endif
        <!-- Hiển thị lịch sử điểm danh -->
        <h3 class="mt-5 text-center">Lịch sử điểm danh</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Điểm</th>
                    <th>Loại điểm danh</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->attendances as $attendance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                        <td>{{ $attendance->points }}</td>
                        <td>{{ $attendance->is_makeup ? 'Điểm danh bù' : 'Điểm danh thường' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Hiển thị thông tin tổng số ngày đã điểm danh trong tuần -->
        <p class="text-center mt-4">
            <strong>Điểm danh tuần này:</strong> {{ $attendanceCount }} / 7 ngày
        </p>
        <div class="referral-container">
            <label for="referralCode">Mã Giới Thiệu:</label>
            <input type="text" id="referralCode" value="{{ $user->referral_code }}" readonly>
            <button onclick="copyReferralCode()">Copy</button>
        </div>
    </div>

    <style>
        /* Bố cục danh sách các ngày */
        .row {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        /* Container của từng ngày */
        .day {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100px;
            min-height: 120px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8f9fa;
            transition: all 0.3s ease-in-out;
        }

        /* Header của ngày */
        .day-header {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 8px;
        }

        /* Nút bấm */
        .day button {
            font-size: 14px;
            padding: 6px 10px;
            width: 80%;
            border-radius: 6px;
            text-transform: uppercase;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        }

        /* Hiệu ứng hover cho nút */
        .day button:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Nút điểm danh hiện tại */
        .day button.btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .day button.btn-primary:hover {
            background-color: #0056b3;
        }

        /* Nút điểm danh bù */
        .day button.btn-warning {
            background-color: #ffc107;
            color: #333;
        }

        .day button.btn-warning:hover {
            background-color: #e0a800;
        }

        /* Nút disabled */
        .day button.disabled {
            background-color: #dcdcdc;
            color: #777;
            cursor: not-allowed;
        }

        /* Ngày hôm nay */
        .today {
            background-color: #ffc107;
            color: #333;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Ngày đã điểm danh */
        .attended {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }

        /* Ngày đã qua */
        .past-day {
            background-color: #6c757d;
            color: white;
            opacity: 0.8;
        }

        /* Ngày tương lai */
        .future-day {
            background-color: #e0e0e0;
            color: #aaa;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .row {
                justify-content: center;
            }

            .day {
                width: 80px;
                min-height: 100px;
            }

            .day button {
                font-size: 12px;
            }
        }

        /* Container của mã giới thiệu */
        .referral-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Label của mã giới thiệu */
        .referral-container label {
            font-weight: bold;
            color: #333;
            margin-right: 10px;
        }

        /* Ô input */
        .referral-container input[type="text"] {
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 250px;
            background-color: #fff;
            color: #555;
            outline: none;
            transition: all 0.3s ease-in-out;
        }

        .referral-container input[type="text"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Nút Copy */
        .referral-container button {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .referral-container button:hover {
            background-color: #0056b3;
            box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.3);
        }

        /* Responsive cho màn hình nhỏ */
        @media (max-width: 768px) {
            .referral-container {
                flex-direction: column;
                align-items: stretch;
            }

            .referral-container input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }

            .referral-container button {
                width: 100%;
            }
        }
    </style>
    <script>
        function copyReferralCode() {
            var referralCode = document.getElementById("referralCode");
            referralCode.select();
            referralCode.setSelectionRange(0, 99999);
            document.execCommand("copy");

            alert("Đã sao chép mã giới thiệu: " + referralCode.value);
        }
    </script>
@endsection
