@extends('layouts.admin')

@section('title', "Tambah Seller")

@section('content')
<form action="{{ route('seller.store') }}" method="POST" class="bg-white rounded p-3 border" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="flex centerize">
        <div class="group w-50 flex centerize">
            <div class="flex column grow-1 ratio-5-2 rounded bg-grey CoverPreview centerize">Upload Logo</div>
            <input type="file" id="cover" name="cover" onchange="inputFile(this, '.CoverPreview')">
        </div>
    </div>
    <div class="flex row gap-20 mt-4">
        <div class="flex column grow-1">
            <h1 class="mt-0">English</h1>
            <div class="group">
                <input type="text" name="payloads[name_en]" id="name_en" required>
                <label for="name_en">Seller Name :</label>
            </div>
            <div class="group">
                <textarea id="description_en" name="payloads[description_en]" required></textarea>
                <label for="description_en">Profile :</label>
            </div>
            <div class="group">
                <input type="text" id="specialization_en" name="payloads[specialization_en]" required>
                <label for="specialization_en">Specialization :</label>
            </div>
        </div>
        <div class="flex column grow-1">
            <h1 class="mt-0">Indonesian</h1>
            <div class="group">
                <input type="text" id="name_id" name="payloads[name_id]" required>
                <label for="name_id">Nama Seller :</label>
            </div>
            <div class="group">
                <textarea id="description_id" name="payloads[description_id]" required></textarea>
                <label for="description_id">Profil :</label>
            </div>
            <div class="group">
                <input type="text" id="specialization_id" name="payloads[specialization_id]" required>
                <label for="specialization_id">Spesialisasi :</label>
            </div>
        </div>
    </div>

    <div class="bg-grey w-100 mt-2 mb-2" style="height: 2px"></div>

    <div class="group">
        <input type="text" name="website" id="website" required>
        <label for="website">Website</label>
    </div>

    <button class="mt-2 primary ">Submit</button>
</form>
@endsection