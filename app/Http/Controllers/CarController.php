<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\Part;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarController extends Controller
{
    public function index()
    {
        return Inertia::render('cars/CarsIndex', [
            'cars' => Car::with('parts')->get(), //eager load car and parts
            'parts' => Part::with('car')->get()
        ]);
    }

    public function store(StoreCarRequest $request){
        $validated = $request->validated();

        if(!empty($validated['registration_number'])){
            $validated['registration_number'] = mb_strtoupper($validated['registration_number']);
        }

        $car = Car::create($validated);

        $partIDs = $validated['parts'] ?? [];

        $this->attachParts($partIDs, $car);

        return redirect()->back();
    }

    public function update(UpdateCarRequest $request, Car $car){
        $validated = $request->validated();

        if(!empty($validated['registration_number'])){
            $validated['registration_number'] = mb_strtoupper($validated['registration_number']);
        }

        $car->update($validated);

        $newPartIDs = $validated['parts'] ?? [];
        $currentCarPartIDs = $car->parts()->pluck('id')->toArray();//picks ids from collection and puts them into an array

        $detachPartsIDs = array_diff($currentCarPartIDs, $newPartIDs); //returns ids that are currently attached to the car but missing in the newPartIDs
        $attachPartsIDs = array_diff($newPartIDs, $currentCarPartIDs); //returns ids that are not attached to the car but are present in the newPartIDs

        if(!empty($detachPartsIDs)){
            Part::whereIn('id', $detachPartsIDs)->update(['car_id' => null]);
        }

        $this->attachParts($attachPartsIDs, $car);

        return redirect()->back();
    }

    public function destroy(Request $request,Car $car)
    {
        $validated = $request->validate([
            'deleteWithParts' => 'nullable|boolean'
        ]);

        if($validated['deleteWithParts']){
            $car->parts()->delete();
        }
        $car->delete();

        return redirect()->back();
    }

    //helper function for attaching parts
    private function attachParts(array $partIDs, Car $car){
        if(!empty($partIDs)){
            Part::whereNull('car_id')
                ->whereIn('id', $partIDs)
                ->update(['car_id' => $car->id]);
        }
    }
}
