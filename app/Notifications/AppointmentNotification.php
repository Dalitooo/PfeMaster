<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification
{
    public function __construct(
        public Appointment $appointment,
        public string $event,   // booked | confirmed | cancelled | in_progress | completed | no_show
        public string $message,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'appointment_id'   => $this->appointment->id,
            'event'            => $this->event,
            'message'          => $this->message,
            'appointment_date' => $this->appointment->appointment_date->format('d/m/Y à H:i'),
            'patient_name'     => $this->appointment->patient?->name,
            'doctor_name'      => $this->appointment->doctor?->name,
        ];
    }
}
