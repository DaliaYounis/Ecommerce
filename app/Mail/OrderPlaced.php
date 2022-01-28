<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public  $order ;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function build()
    {
        //markdown for email
        return $this->to($this->order->billing_email,$this->order->billing_name)
                    ->bcc('another@another.com')
                    ->subject('Order For Ecommerce')
                    ->markdown('emails.orders.placed');
    }
}
