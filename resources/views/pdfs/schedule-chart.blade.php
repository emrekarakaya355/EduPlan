<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ders Programi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .schedule-container {
            padding: 4px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .day-header {
            background-color: #2d3748;
            color: white;
            text-align: center;
            font-weight: bold;
            padding: 4px;
            border: 1px solid #4a5568;
        }

        .time-header {
            background-color: #edf2f7;
            text-align: center;
            font-weight: bold;
            padding: 4px;
            width: 20px;
            max-width: 20px;
            white-space: nowrap;
            border: 1px solid #cbd5e0;
            font-size: 8px;
        }

        .class-cell {
            min-height: 20px;
            padding: 6px 4px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .empty-cell {
            width: 1px !important;
            max-width: 1px !important;
            padding: 0 !important;
            border: 1px solid #edf2f7;
        }

        .has-class {
            background-color: #e6f7ff;
            width: auto;
        }

        /* Yazdırma stilleri */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .schedule-container {
                padding: 0;
                width: 100%;
            }

            .empty-cell {
                width: 1px !important;
                max-width: 1px !important;
                padding: 0 !important;
            }

            .has-class {
                padding: 2px;
            }

            table {
                table-layout: fixed;
            }
        }
    </style>
</head>
<body>
<div class="schedule-container">
    <table cellspacing="0" cellpadding="0">
        <caption style="text-align:left;">
                <img src="{{ public_path('assets/images/logos/kucuk_logo.png') }}" alt="Logo" style="height: 24px; width: auto;">
        </caption>
        <caption style="text-align:center; padding: 1px">

            {{ $schedule->year .' - '. \Carbon\Carbon::createFromDate($schedule->year)->addYear()->year . ' ' . $schedule->semester }}
            {{ $schedule->program?->name ?? '' }}
            {{ $schedule->grade . '. Sınıf Ders Programı' }}
        </caption>
        <thead>
        <tr>
            <th class="time-header" style="background-color: white; border: none;"></th>
            <th class="day-header">Pazartesi</th>
            <th class="day-header">Sali</th>
            <th class="day-header">Carsamba</th>
            <th class="day-header">Persembe</th>
            <th class="day-header">Cuma</th>
        </tr>
        </thead>
        <tbody>
        @foreach($scheduleData as $time => $days)
            <tr>
                <td class="time-header">{{ $time }}</td>
                @foreach(range(0,4) as $day)
                    <td class="{{ isset($days[$day]) && $days[$day] ? 'class-cell' : 'empty-cell' }}">
                        @if(isset($days[$day]) && $days[$day])
                            <div style="display: flex; align-items: center;text-align: center; gap: 4px;">
                                @foreach($days[$day] as $class)
                                    <div style="background-color: {{ '#FFFFFF' }}; font-size: 8px; padding: 4px;">
                                        <strong>{{ $class['class_code'] }}</strong> - {{ $class['class_name'] ?? '' }}<br>
                                        {{ $class['instructor_name'] ?? '' }}<br>
                                        {{ $class['classrom_name'] ?? '' }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
