<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateKeyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateName;
    public $candidateKey;

    public function __construct($candidateName, $candidateKey)
    {
        $this->candidateName = $candidateName;
        $this->candidateKey = $candidateKey;
    }

    public function build()
    {
        return $this->subject('Your Candidate Key - VoteDesk')
            ->view('emails.candidate_key');
    }
}
