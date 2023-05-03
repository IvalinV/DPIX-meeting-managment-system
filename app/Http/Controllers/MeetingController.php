<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Lawyer;
use App\Models\Citizen;
use App\Models\Meeting;
use App\Http\Requests\StoreMeetingRequest;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Meeting/Create', [
            'lawyers' => \App\Models\Lawyer::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMeetingRequest $request)
    {
        $lawyer = Lawyer::find($request->lawyer);
        $citizen = Citizen::find($request->citizen);

        $requested_date = \Carbon\Carbon::parse($request->date);
        $already_requested = Meeting::whereDate('date', $requested_date)->exists();

        if ($already_requested) {
            $available_slot = $this->rescheduleMeeting($lawyer);
            return redirect()->back()->withErrors([
                'date' => "Date time slot already requested! Can reschedule for: $available_slot"
            ]);
        }

        $lawyer->meetings()->create([
            'citizen_id' => $citizen->id,
            'date' => $request->date
        ]);

        return redirect()->back()->with('status', 'Meeting requested succsesfully');
    }

    /**
     * Reschedule meeting.
     *
     * @param \App\Models\Lawyer $lawyer
     * @return string $available_slot
     */
    public function rescheduleMeeting($lawyer)
    {
        return $lawyer
                ->meetings()
                ->latest()
                ->first()
                ->date
                ->addHour()
                ->format('M d Y H:i');
    }
}
