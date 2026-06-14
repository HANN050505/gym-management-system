<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::latest()->get();

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'no_hp' => 'required'
    ]);

    $member = Member::create([
        'kode_member' => 'GYM' . str_pad(
            Member::count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        ),
        'nama' => $request->nama,
        'email' => $request->email,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat
    ]);

    return redirect()
        ->route('members.index')
        ->with(
            'success',
            'Member berhasil ditambahkan'
        );
}

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $member->update($request->all());

        return redirect()
            ->route('members.index')
            ->with('success', 'Member berhasil diupdate');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()
            ->route('members.index')
            ->with('success', 'Member berhasil dihapus');
    }

    public function show(Member $member)
{
    return view(
        'members.show',
        compact('member')
    );
}
}