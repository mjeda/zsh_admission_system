<?php

namespace App\Http\Livewire\Veta;

use App\Action\FetchInformationAction;
use App\Models\Admission;
use App\Models\Intake;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class _RegisterComponent extends Component
{
    public $email, $password, $password_confirmation = null;

    public $intake = null;

    protected $rules = [
        'email' => 'required|email|unique:users|ends_with:gmail.com,yahoo.com,hotmail.com,icloud.com',
        'password' => 'required|min:8|confirmed'
    ];

    protected $messages = [
        'password.confirmed' => 'Passwords do not match.'
    ];

    public function mount()
    {
        $this->intake = Intake::where('status', 'Open')->where('type', 'VTA/VETA')->first();
    }

    public function submitForm()
    {
        $this->validate();

        $user = User::create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'type' => 'veta',
        ]);

        $this->reset(['email', 'password', 'password_confirmation']);
        session()->flash('message', 'Registration successful!');

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.veta.register-component', [
            'intake' => $this->intake
        ])->layout('layouts.guest');
    }
}
