@extends('layouts.app')

@section('title', '勤怠登録画面')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/attendance')  }}">
@endsection

@section('content')
<div class="attendance">
    @if($status === 'outside')
    <span class="badge">勤務外</span>
    @else
    <span class="badge">出勤中</span>
    @endif
</div>