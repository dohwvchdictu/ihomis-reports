<?php

namespace App\Http\Livewire\Admin\Appointments;

use App\Models\Client;
use Livewire\Component;

class CreateAppointmentForm extends Component
{
    public $state = [];

    public function createAppointment()
    {
        dd($this->state);
    }

    public function render()
    {
        $clients = Client::all();

        return view('livewire.admin.appointments.create-appointment-form', [
            'clients' => $clients,
        ]);
    }
}
