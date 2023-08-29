@extends('layouts.admin')

@section('title', "Pengaturan")
    
@section('content')
@if ($message != "")
    <div class="bg-green rounded p-2 mb-2">
        {{ $message }}
    </div>
@endif
<div class="bg-white rounded p-4 border">
    <form action="{{ route('admin.updateSettings') }}" method="POST">
        {{ csrf_field() }}
        <div class="group">
            <input type="number" name="max_distance" id="max_distance" min="1" value="{{ env('MAX_DISTANCE') }}" required>
            <label for="max_distance">Jarak Scan Maksimum (meter)</label>
        </div>
        <div class="group">
            <input type="number" name="min_to_claim" id="min_to_claim" min="1" value="{{ env('MIN_TO_CLAIM') }}" required>
            <label for="min_to_claim">Minimal Scan untuk Klaim Hadiah</label>
        </div>

        <button class="primary w-100 mt-3">Simpan Perubahan</button>
    </form>
</div>
@endsection