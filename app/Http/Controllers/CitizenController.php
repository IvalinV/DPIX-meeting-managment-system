<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CitizenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        return response()->json(Citizen::search(trim($request->search))->get());
    }

        /**
     * Display the specified resource.
     */
    public function show(Citizen $citizen)
    {
        return Inertia::render('Meeting/Index', [
            'meetings' => $citizen
                ->meetings()
                ->with('lawyer')
                ->latest()
                ->paginate(10)
        ]);
    }
}
