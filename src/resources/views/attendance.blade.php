@extends('layouts.app')

@section('title', '勤怠登録画面')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/attendance.css')  }}">
@endsection

@section('content')
<div class="attendance">
    <div class="attendance__inner">
        <div class="attendance__status">
            <span class="attendance__status-badge">{{ $status }}</span>
        </div>
        <div class="attendance__date">{{ $dateString }}</div>
        <div class="attendance__time">{{ $timeString }}</div>
        <div class="attendance__actions">
            @if($status ==='勤務外')
            <div class="attendance__button-group">
                <form action="{{ route('attendance.clock-in') }}" method="post">
                    @csrf
                    <button type="submit" class="attendance__btn attendance__btn-black">出勤</button>
                </form>
            </div>
            @endif
            @if($status === '出勤中')
            <div class="attendance__button-group attendance__button-group--split">
                <form action="{{ route('attendance.clock-out') }}" method="post">
                    @csrf
                    <button type="submit" class="attendance__btn attendance__btn-black">退勤</button>
                </form>
                <form action="{{ route('attendance.rest-in') }}" method="post">
                    @csrf
                    <button type="submit" class="attendance__btn attendance__btn-white">休憩入</button>
                </form>
            </div>
            @endif
            @if($status === '休憩中')
            <div class="attendance__button-group">
                <form action="{{ route('attendance.rest-out') }}" method="post">
                    @csrf
                    <button type="submit" class="attendance__btn attendance__btn-white">休憩戻</button>
                </form>
            </div>
            @endif
            @if($status === '退勤済')
            <div class="attendance__message">
                <p class="attendance__text">お疲れ様でした。</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection