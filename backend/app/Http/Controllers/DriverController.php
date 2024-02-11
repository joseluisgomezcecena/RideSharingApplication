<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    //show a driver
    public function show(Request $request)
    {
       $user = $request->user();
       $user->load('driver');
       return $user;
    }

    //update a driver
    public function update(Request $request)
    {
        $request->validate([
            'year'=>'required|numeric|between:2007,2025',
            'make'=>'required|string',
            'model'=>'required|string',
            'color'=>'required|string',
            'license_plate'=>'required|string',
            'name'=>'required|string',
        ]);

        $user = $request->user();
        $user->update($request->only('name'));

        //create or update a driver associated with the user.
        $user->driver()->updateOrCreate($request->only([
            'year',
            'make',
            'model',
            'color',
            'license_plate'
        ]));

        $user->load('driver');
        return $user;

    }
}
