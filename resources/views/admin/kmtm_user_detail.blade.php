@extends('layouts.admin')

@section('title', $user->name)
    
@section('content')
<a href="{{ route('admin.kmtmUser') }}" class="text primary gap-10 flex item-center mb-2">
    <i class="bx bx-left-arrow-alt"></i>
    kembali
</a>
<div class="bg-white shadow rounded p-4">
    <div class="flex row gap-40">
        <div class="h-100 ratio-1-1 rounded-max bg-primary flex centerize text bold size-24">
            {{ $user->initial }}
        </div>
        <div class="flex column grow-1">
            <div class="text small muted">NAME</div>
            <div class="text bold size-20">{{ $user->name }}</div>
            <div class="h-20"></div>
            @if ($user->join_type != "personal")
                <div class="text small muted">{{ strtoupper($user->join_type) }}</div>
                <div class="text bold size-20">{{ $user->from_company }}</div>
            @else
                <div class="text small muted">REFERENSI</div>
                <div class="text bold size-20">{{ $user->reference }}</div>
                <div class="text small muted mt-2">WANT TO VISIT SELLERS</div>
                <div class="text bold size-20">{{ $user->interesting_sellers }}</div>
            @endif
        </div>
        <div class="flex column grow-1">
            <div class="text small muted">NO. TELEPON</div>
            <a href="tel:{{ $user->phone }}" class="text primary bold size-20">{{ $user->phone }}</a>
            <div class="h-20"></div>
            <div class="text small muted">EMAIL</div>
            <a href="mailto:{{ $user->email }}" class="text primary bold size-20">{{ $user->email }}</a>
            <div class="h-20"></div>
            <div class="text small muted">WEBSITE / SNS</div>
            <a href="{{ $user->website }}" class="text primary bold size-20" target="_blank">{{ $user->website }}</a>
        </div>
    </div>
</div>

@if ($user->join_type == "company")
<div class="bg-white shadow rounded p-4 mt-4 flex row gap-40">
    <div class="flex column grow-1">
        <div class="text small muted">NAMA PERUSAHAAN</div>
        <div class="text bold">{{ $user->from_company }}</div>
    </div>
    <div class="flex column grow-1">
        <div class="text small muted">LINE OF BUSINESS</div>
        <div class="text bold">{{ $user->line_of_business }}</div>
    </div>
    <div class="flex column grow-1">
        <div class="text small muted">EST. YEAR</div>
        <div class="text bold">{{ $user->company_established }}</div>
    </div>
</div>
@endif

@if ($user->custom_field != null)
<div class="bg-white shadow rounded p-4 mt-4">
    <table>
        <thead>
            <tr>
                <th style="width: 70%">Pertanyaan</th>
                <th>Jawaban</th>
            </tr>
        </thead>
        <tbody>
            @foreach (json_decode($user->custom_field) as $item)
                @if ($item->parent_id == null)
                    <tr>
                        <td>
                            <div class="text bold">{{ $item->body }}</div>
                        </td>
                        <td>{{ $item->answer }}</td>
                    </tr>
                    @foreach (json_decode($user->custom_field) as $obj)
                        @if ($obj->parent_id == $item->id && $obj->expected_parent_answer == $item->answer)
                            <tr>
                                <td>{{ $obj->body }}</td>
                                <td>{{ $obj->answer }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection