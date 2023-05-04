<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Lawyer;
use App\Models\Citizen;
use App\Models\Meeting;
use App\Http\Requests\StoreMeetingRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
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
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $meeting = Meeting::find($request->meeting_id);

        if ($request->status != 'reschedule') {
            $meeting->update(['status' => $request->status]);
        }else{
            // TODO: Think of a better way of doing this.
            $new_date = $this->rescheduleMeeting(Auth::guard('lawyer')->user());
            $meeting->update(['date' => $new_date]);
        }

        return redirect(route('lawyer.meetings', Auth::guard('lawyer')->id()))
            ->with(['success' => Auth::guard('lawyer')->user()->meetings]);
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
            $available_slot = $this->rescheduleMeeting($lawyer)->format('M d Y H:i');
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
     * @return Carbon\Carbon $available_slot
     */
    public function rescheduleMeeting($lawyer)
    {
        return $lawyer
                ->meetings()
                ->latest()
                ->first()
                ->date
                ->addHour();
    }
}
