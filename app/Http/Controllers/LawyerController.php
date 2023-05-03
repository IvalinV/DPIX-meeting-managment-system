<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLawyerRequest;
use App\Http\Requests\UpdateLawyerRequest;
use App\Models\Lawyer;
use Inertia\Inertia;

class LawyerController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Lawyer $lawyer)
    {
        return Inertia::render('Meeting/Index', [
            'meetings' => $lawyer->meetings()->with('citizen')->latest()->get()
        ]);
    }

}
