<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectStageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function build()
    {
        return $this->subject('⚠️ Оновлення по вашому проєкту')
            ->from('clients-supp@smarto.agency', 'Smarto Agency')
            ->view('emails.project_stage_notification');
    }
}
