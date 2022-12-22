<?php

namespace Modules\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Notification\Emails\SendMail as EmailsSendMail;
use Modules\Notification\Entities\Notification;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $userData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data['notification'];
        $this->userData = $data['userData'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'description' => $this->data->description,
            'subject' => $this->data->title,
            'to' => $this->userData->email
        ];
        Mail::to($this->userData->email)->send(new EmailsSendMail($data));
        $this->data->update(['status'=>Notification::STATUS_SENT]);
    }
}
