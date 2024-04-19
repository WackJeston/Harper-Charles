<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

use App\Models\Order;

class OrderSuccessful extends Mailable
{
  use Queueable, SerializesModels;

	public $record;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($id)
  {
    $this->record = Order::find($id);
  }

  /**
   * Get the message envelope.
   *
   * @return \Illuminate\Mail\Mailables\Envelope
   */
  public function envelope()
  {
		return new Envelope(
      from: new Address($_ENV['MAIL_NOTIFICATION_ADDRESS'], $_ENV['APP_NAME']),
      subject: sprintf('Order Successful (#%s)', $this->record->id),
    );
  }

  /**
   * Get the message content definition.
   *
   * @return \Illuminate\Mail\Mailables\Content
   */
  public function content()
  {
    return new Content(
      view: 'email.order.order-successful',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array
   */
  public function attachments()
  {
    return [];
  }
}
