<?php

namespace App\Jobs;

use App\Mail\RescheduleMeetingSuggestion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ScheduleMeeting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $lawyer;
    private $citizen;
    private $requestedDate;
    private $alreadyRequested;

 /**
     * Create a new job instance.
     */
    public function __construct($lawyer, $citizen, $requestedDate, $alreadyRequested)
    {
        $this->lawyer = $lawyer;
        $this->citizen = $citizen;
        $this->requestedDate = $requestedDate;
        $this->alreadyRequested = $alreadyRequested;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->alreadyRequested && $this->lawyer->meetings()->count()) {
            $latestMeetingSaved = $this->lawyer->meetings()->latest('date')->first();

            $availabelDates = [
                $latestMeetingSaved->date->copy()->addHour(), 
                $latestMeetingSaved->date->copy()->addHour(2), 
                $latestMeetingSaved->date->copy()->addHour(3)
            ];
            
            Mail::to($this->lawyer->email)->send(new RescheduleMeetingSuggestion($availabelDates));
        }else{
            $this->lawyer->meetings()->create([
                'citizen_id' => $this->citizen->id,
                'date' => $this->requestedDate
            ]);
        }

    }
}
