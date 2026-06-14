@extends('layouts.app')

@section('content')

<h2>Absensi Member</h2>

<div id="reader" style="width:300px;"></div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<form method="POST" action="/absensi">

    @csrf

    <div class="mb-3">

        <label>Kode Member</label>

        <input
    type="text"
    id="kode_member"
    name="kode_member"
    class="form-control"
    required
        >   

    </div>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Absensi
    </button>

</form>
<p></p>
<a
    href="/riwayat-absensi"
    class="btn btn-success mb-3"
>
    Lihat Riwayat Absensi
</a>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>

function onScanSuccess(decodedText)
{
    document.getElementById(
        'kode_member'
    ).value = decodedText;
}

let html5QrcodeScanner =
new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: 250
    }
);

html5QrcodeScanner.render(
    onScanSuccess
);

</script>
@endsection