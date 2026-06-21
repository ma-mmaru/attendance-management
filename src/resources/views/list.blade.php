@extends('layouts.app')

@section('title', '勤怠一覧画面(一般ユーザー)')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/list.css') }}">
@endsection

@section('content')
<div class="list">
    <div class="list__inner">
        <h1 class="list__title">勤怠一覧</h1>
        <div class="month-selector">
            <a href="#" class="month-selector__btn month-selector__btn--prev">← 前月</a>
            <div class="month-selector__current">📅{{ \Carbon\Carbon::now()->format('Y/m') }}</div>
            <a href="#" class="month-selector__btn month-selector__btn--next">翌月 →</a>
        </div>
        <table class="list-table">
            <thead>
                <tr class="list__table--row">
                    <th class="list__table--header">日付</th>
                    <th class="list__table--header">出勤</th>
                    <th class="list__table--header">退勤</th>
                    <th class="list__table--header">休憩</th>
                    <th class="list__table--header">合計</th>
                    <th class="list__table--header">詳細</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                @php
                $clockIn = $record->clock_in ? \Carbon\Carbon::parse($record->clock_in)->format('H:i') :'';
                $clockOut = $record->clock_out ? \Carbon\Carbon::parse($record->clock_out)->format('H:i') :'';
                $carbonDate = \Carbon\Carbon::parse($record->date);
                $weekInJapanese =['日','月','火','水','木','金','土'][$carbonDate->dayOfWeek];
                $formattedDate =$carbonDate->format('m/d') . '(' . $weekInJapanese . ')';
                @endphp
                <tr class="list__table--row">
                    <td class="list__table--item">{{ $formattedDate }}</td>
                    <td class="list__table--item">{{ $clockIn }}</td>
                    <td class="list__table--item">{{ $clockOut }}</td>
                    <td class="list__table--item">{{ $record->total_rest_time }}</td>
                    <td class="list__table--item">{{ $record->total_work_time }}</td>
                    <td class="list__table--item">
                        <a href="{{ route('attendance.detail', ['id' => $record->id]) }}"
                            class="list__table--link">詳細</a>
                    </td>
                </tr>
                @empty
                <tr class="list__table--row">
                    <td colspan="6" class="list__table--item list__table--empty">
                        "勤怠データが登録されていません"
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection