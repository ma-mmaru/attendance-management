@extends('layouts.app')

@section('title', '勤怠詳細画面')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css') }}">
@endsection

@section('content')
<div class="detail">
    <div class="detail__inner">
        <h1 class="detail__title">勤怠詳細</h1>
        <form action="#" method="post" class="detail__form">
            @csrf
            <table class="detail__table">
                <tbody>
                    <tr class="detail__table-row">
                        <th class="detail__table-header">名前</th>
                        <td class="detail__table-item">
                            <span class="detail__table-text">{{ $record->user->name }}</span>
                        </td>
                    </tr>
                    <tr class="detail__table-row">
                        <th class="detail__table-header">日付</th>
                        <td class="detail__table-item">
                            @php
                            $carbonDate =\Carbon\Carbon::parse($record->date);
                            @endphp
                            <span class="detail__table-text detail__table-text--bold">
                                {{ $carbonDate->format('Y年') }}
                            </span>
                            <span class="detail__table-text detail__table-text--bold">
                                {{ $carbonDate->format('n月j日') }}
                            </span>
                        </td>
                    </tr>
                    <tr class="detail__table-row">
                        <th class="detail__table-header">出勤・退勤</th>
                        <td class="detail__table-item">
                            @if(Auth::user()->is_admin)
                            <input type="text" name="clock_in" class="detail__table-input"
                                value="{{ $record->clock_in ? \Carbon\Carbon::parse($record->clock_in)->format('H:i') : '' }}">
                            <span class="detail__table-tilde">~</span>
                            <input type="text" name="clock_out" class="detail__table-input"
                                value="{{ $record->clock_out ? \Carbon\Carbon::parse($record->clock_out)->format('H:i') : '' }}">
                            @else
                            <span class="detail__table-text">
                                {{ $record->clock_in ? \Carbon\Carbon::parse($record->clock_in)->format('H:i') : '' }}
                                <span class="detail__table-tilde">~</span>
                                {{ $record->clock_out ? \Carbon\Carbon::parse($record->clock_out)->format('H:i') : '' }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @foreach($record->restRecords as $index => $rest)
                    <tr class="detail__table-row">
                        <th class="detail__table-header">{{ $index === 0 ? '休憩' : '休憩' . ($index +1) }}
                        </th>
                        <td class="detail__table-item">
                            @if(Auth::user()->is_admin)
                            <input type="text" name="rest[{{ $rest->id }}][in]" class="detail__table-input"
                                value="{{ $rest->rest_in ? \Carbon\Carbon::parse($rest->rest_in)->format('H:i') : '' }}">
                            <span class="detail__table-tilde">~</span>
                            <input type="text" name="rest[{{ $rest->id }}][out]" class="detail__table-input"
                                value="{{ $rest->rest_out ? \Carbon\Carbon::parse($rest->rest_out)->format('H:i') : '' }}">
                            @else
                            <span class="detail__table-text">
                                {{ $rest->rest_in ? \Carbon\Carbon::parse($rest->rest_in)->format('H:i') : '' }}
                                <span class="detail__table-tilde">~</span>
                                {{ $rest->rest_out ? \Carbon\Carbon::parse($rest->rest_out)->format('H:i') : '' }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @if(Auth::user()->is_admin && $record->restRecords->count() < 5) <tr class="detail__table-row">
                        <th class="detail__table-header">
                            休憩{{ $record->restRecords->count() + 1 }}
                        </th>
                        <td class="detail__table-item">
                            <input type="text" name="new_rest_in" class="detail__table-input" value="">
                            <span class="detail__table-tilde">~</span>
                            <input type="text" name="new_rest_out" class="detail__table-input" value="">
                        </td>
                        </tr>
                        @endif
                        <tr class="detail__table-row">
                            <th class="detail__table-header">備考</th>
                            <td class="detail__table-item">
                                @if(Auth::user()->is_admin)
                                <textarea name="comment"
                                    class="detail__table-textarea">{{ $record->comment }}</textarea>
                                @else
                                <span class="detail__table-text">{{ $record->comment }}</span>
                                @endif
                            </td>
                        </tr>
                </tbody>
            </table>
            @if(Auth::user()->is_admin)
            <div class="detail__form-actions">
                <button type="submit" class="detail__form-btn">修正</button>
            </div>
            @else
            <div class="detail__form-message">
                <p class="detail__form-error">*承認待ちのため修正はできません。</p>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection