<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateName;
    public $candidateId;
    public $panchayatName;

    /**
     * Create a new message instance.
     *
     * @param string $candidateName
     * @param string $candidateId
     * @param string $panchayatName
     */
    public function __construct($candidateName, $candidateId, $panchayatName)
    {
        $this->candidateName = $candidateName;
        $this->candidateId = $candidateId;
        $this->panchayatName = $panchayatName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Candidate Application Approved - Candidate Key Included')
                    ->view('emails.candidate_approved');
    }
}
