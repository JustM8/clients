<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WaitingReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function build()
    {
        return $this->subject('⏳ Нагадування: проєкт очікує на вашу відповідь')
            ->from('clients-supp@smarto.agency', 'Smarto Agency')
            ->view('emails.waiting_reminder');
    }
}
