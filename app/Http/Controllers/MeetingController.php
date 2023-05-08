<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Lawyer;
use App\Models\Citizen;
use App\Models\Meeting;
use App\Http\Requests\StoreMeetingRequest;
use App\Http\Resources\MeetingResource;
use App\Jobs\ScheduleMeeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $validated = $request->validated();
        $lawyer = Lawyer::find($validated['lawyer']['id']);
        $citizen = Citizen::find($validated['citizen']);
        
        $requested_date = \Carbon\Carbon::parse($validated['date']);
        $already_requested = Meeting::whereDate('date', $requested_date)->exists();

        ScheduleMeeting::dispatch($lawyer, $citizen, $requested_date, $already_requested);

        return redirect()->back()->with('status', 'Your meeting request is processing, we will get back to you shortly.');
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

    /**
     * Search meetings by different params.
     *
     * @param Request $request
     * @return Response $response
     */
    public function search(Request $request)
    {
        $results = Meeting::search(trim($request->search) ?? '')->query(function ($query) {
            $query
                ->join('citizens', 'meetings.citizen_id', 'citizens.id')
                ->join('lawyers', 'meetings.lawyer_id', 'lawyers.id')
                ->select([
                    'meetings.id', 
                    'meetings.date',
                    'meetings.status',
                    'lawyers.first_name as lawyer_first_name', 
                    'lawyers.last_name as lawyer_last_name', 
                    'citizens.first_name', 'citizens.last_name'
                ])
                ->orderBy('meetings.date', 'DESC');
        })
        ->paginate(10);
        
        return response()->json($results);
    }

    /**
     * Sort meetings by the data provided from the user.
     *
     * @param Request $request
     * @return MeetingResource $collection
     */
    public function sort(Request $request)
    {
        $sortingMethod = $request->direction === 'asc' ? "orderBy" : "orderByDesc";
        $meetings = Meeting::where('lawyer_id', $request->lawyer_id)
            ->$sortingMethod($request->param);

        return MeetingResource::collection($meetings->paginate(10));
    }

    /**
     * Filter meetings data by user input.
     *
     * @param Request $request
     * @return MeetingResource $collection
     */
    public function filter(Request $request)
    {
        if($request->filter_by === 'status'){
            $meetings = Meeting::where('lawyer_id', $request->lawyer_id)->orderBy('status');
        }

        if($request->filter_by === 'latest'){
            $meetings = Meeting::where('lawyer_id', $request->lawyer_id)->latest();
        }

        if($request->filter_by === 'oldest'){
            $meetings = Meeting::where('lawyer_id', $request->lawyer_id)->oldest();
        }

        return MeetingResource::collection($meetings->paginate(10));
    }
}
