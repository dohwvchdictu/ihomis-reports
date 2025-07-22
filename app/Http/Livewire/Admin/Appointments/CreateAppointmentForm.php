<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.16  |
    |              on 2025-07-22 06:50:08              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
 namespace App\Http\Livewire\Admin\Appointments; use App\Models\Client; use Livewire\Component; class CreateAppointmentForm extends Component { public $state = array(); public function createAppointment() { dd($this->state); } public function render() { $clients = Client::all(); return view('livewire.admin.appointments.create-appointment-form', ['clients' => $clients]); } }
