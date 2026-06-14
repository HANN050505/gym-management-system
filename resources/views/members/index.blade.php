@extends('layouts.app')

@section('content')

<h2>Data Member</h2>

<a href="{{ route('members.create') }}"
   class="btn btn-primary mb-3">
   Tambah Member
</a>

<table class="table table-bordered">

    <tr>
        <th>Kode</th>
        <th>Nama</th>
        <th>No HP</th>
        <th>Email</th>
        <th>Aksi</th>
    </tr>

    @foreach($members as $member)

    <tr>

        <td>{{ $member->kode_member }}</td>
        <td>{{ $member->nama }}</td>
        <td>{{ $member->no_hp }}</td>
        <td>{{ $member->email }}</td>

        <td>

            <a href="{{ route('members.edit',$member->id) }}"
               class="btn btn-warning btn-sm">
               Edit
            </a>

            <a
    href="{{ route('members.show',$member->id) }}"
    class="btn btn-info btn-sm"
>
    QR
</a>

            <form action="{{ route('members.destroy',$member->id) }}"
                  method="POST"
                  style="display:inline">

                @csrf
                @method('DELETE')

                <button class="btn btn-danger btn-sm">
                    Hapus
                </button>

            </form>

        </td>

    </tr>

    @endforeach

</table>

@endsection