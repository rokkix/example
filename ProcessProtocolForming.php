<?php

namespace App\Jobs;

use App\Models\Protocol;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessProtocolForming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    private $date;
    private $file;
    private $number;

    private $candidateIds;

    private $collegeId;
    private $subSystem;

    /**
     * ProcessProtocolCreating constructor.
     * @param $candidateIds
     * @param $date
     * @param $file
     * @param $number
     * @param $collegeId
     * @param $subSystem
     */
    public function __construct($candidateIds, $date, $file, $number, $collegeId, $subSystem)
    {
        $this->date         = $date;
        $this->file         = $file;
        $this->number       = $number;
        $this->candidateIds = $candidateIds;
        $this->collegeId    = $collegeId;
        $this->subSystem    = $subSystem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = app(Request::class);

        $request->headers->set('HOST', $this->subSystem.'.'.config('app.domain'));

        $request->request->set('college_id', $this->collegeId);

        $systemId = $this->subSystem == 'spo' ? 1 : 2;

        $protocol = app(Protocol::class)::create([
            'type' => 'protocol',
            'protocol_date' => datetime('Y-m-d', $this->date),
            'protocol_number' => $this->number,
            'file' => $this->file,
            'college_id' => $this->collegeId,
            'system_id' => $systemId,
        ]);

        $protocol->candidate()->sync($this->candidateIds);
    }
}
