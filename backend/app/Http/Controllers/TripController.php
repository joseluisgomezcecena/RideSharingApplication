<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'origin'=>'required|string',
            'destination'=>'required|string',
            'destination_name'=>'required|string',
        ]);

        $user = $request->user();
        $trip = $user->trips()->create($request->only([
            'origin',
            'destination',
            'destination_name'
        ]));

        return $trip;
    }

    public function show(Request $request, Trip $trip)
    {
        //make sure the trip belongs to the user
        if($trip->user->id === $request->user()->id){
            return $trip;
        }

        if($trip->driver && $request->user()->driver)
        {
            if($trip->driver->id === $request->user()->driver->id)
            {
                return $trip;
            }
        }


        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }


    public function accept(Request $request, Trip $trip)
    {
        //a driver accepts a trip.
        $request->validate([
            'driver_location'=>'required',
        ]);

        $trip->update([
            'driver_id' => $request->user()->id,
            'driver_location' => $request->driver_location
        ]);

        $trip->load('driver.user');
        return $trip;
    }

    public function start(Request $request, Trip $trip)
    {
        //a driver starts a trip.
        $trip->update([
            'is_started' => true
        ]);
        $trip->load('driver.user');
        return $trip;
    }


    public function end(Request $request, Trip $trip)
    {
        //a driver starts a trip.
        $trip->update([
            'is_complete' => true
        ]);
        $trip->load('driver.user');
        return $trip;
    }

}
