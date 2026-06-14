@extends('layouts.app')

@section('content')

<h2>Edit Member</h2>

<form action="{{ route('members.update', $member->id) }}" method="POST">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama</label>
        <input
            type="text"
            name="nama"
            class="form-control"
            value="{{ $member->nama }}"
            required>
    </div>

    <div class="mb-3">
        <label>No HP</label>
        <input
            type="text"
            name="no_hp"
            class="form-control"
            value="{{ $member->no_hp }}"
            required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input
            type="email"
            name="email"
            class="form-control"
            value="{{ $member->email }}">
    </div>

    <div class="mb-3">
        <label>Alamat</label>
        <textarea
            name="alamat"
            class="form-control"
            rows="4">{{ $member->alamat }}</textarea>
    </div>

    <button type="submit" class="btn btn-success">
        Update
    </button>

    <a href="{{ route('members.index') }}"
       class="btn btn-secondary">
       Kembali
    </a>

</form>

@endsection