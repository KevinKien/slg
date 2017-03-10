<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers\PushNotification, Log;

class PushNotificationJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $registration_ids;
    protected $payload;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @param $registration_ids
     * @param $payload
     * @internal param $ids
     */
    public function __construct($registration_ids, $payload)
    {
        $this->registration_ids = $registration_ids;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = array(
            'id' => 1000,
            'message' => "Hello Android! You've got a message from me ^_^",
            'url' => 'http://www.google.com',
            //...
        );
        $gcm = new PushNotification();
        $a = $gcm->pushMessageToManyDevices($this->registration_ids, $data);
        Log::info($a);
    }
}
