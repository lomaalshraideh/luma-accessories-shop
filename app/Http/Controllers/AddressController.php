<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::with('user')->get();
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        $users = User::all();
        return view('addresses.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'country' => 'required|string',
            'city' => 'required|string',
            'street' => 'required|string',
            'zip_code' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        Address::create($request->all());

        return redirect()->route('addresses.index')->with('success', 'Address created!');
    }

    public function show(Address $address)
    {
        return view('admin.addresses.show', compact('address'));
    }

    public function edit(Address $address)
    {
        $users = User::all();
        return view('addresses.edit', compact('address', 'users'));
    }

    public function update(Request $request, Address $address)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'country' => 'required|string',
            'city' => 'required|string',
            'street' => 'required|string',
            'zip_code' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $address->update($request->all());

        return redirect()->route('addresses.index')->with('success', 'Address updated!');
    }

    public function destroy(Address $address)
    {
        $address->delete();
        return redirect()->route('addresses.index')->with('success', 'Address deleted!');
    }
}
