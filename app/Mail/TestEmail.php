<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $info;
    public $info2;
    public $sumPrice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info, $info2, $sumPrice)
    {
        $this->info = $info;
        $this->info2 = $info2;
        $this->sumPrice = $sumPrice;
        // dd($arra);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Test Email User')->view('emails');
    }
}