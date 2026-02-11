<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartRequest;
use App\Http\Requests\UpdatePartRequest;
use App\Models\Car;
use App\Models\Part;
use Inertia\Inertia;

class PartController extends Controller
{
    public function index()
    {
        return Inertia::render('parts/PartsIndex', [
            'parts' => Part::with('car')->get(), //eager load cars and parts
            'cars' => Car::with('parts')->get()
        ]);
    }

    public function store(StorePartRequest $request){
        $validated = $request->validated();

        //always change serial_number to uppercase
        $validated['serial_number'] = strtoupper($validated['serial_number']);

        Part::create($validated);

        return redirect()->back();
    }

    public function update(UpdatePartRequest $request, Part $part){
        $validated = $request->validated();

        //always change serial_number to uppercase
        $validated['serial_number'] = strtoupper($validated['serial_number']);

        $part->update($validated);

        return redirect()->back();
    }

    public function destroy(Part $part)
    {
        $part->delete();

        return redirect()->back();
    }
}
