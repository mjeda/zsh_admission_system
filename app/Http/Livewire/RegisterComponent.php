<?php

namespace App\Http\Livewire;

use App\Action\FetchInformationAction;
use App\Models\Admission;
use App\Models\Intake;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class RegisterComponent extends Component
{
    public $index_number, $phone_number, $email, $password, $password_confirmation = null;

    public $studentData = null;
    private $student = null;
    public $verified = false;
    public $failed = false;
    public $loading = false;
    public $apiError = null;
    public $intake = null;

    protected $rules = [
        'index_number' => 'required|unique:admissions|regex:/^[SP]\d{4}\/\d{4}\/\d{4}$/',
        'phone_number' => 'required|regex:/^0\d{9}$/',
        'email' => 'required|email|unique:users|ends_with:gmail.com,yahoo.com,hotmail.com,icloud.com',
        'password' => 'required|min:8|confirmed'
    ];

    protected $messages = [
        'index_number.regex' => 'Invalid Index Number Format: Eg. S0001/0001/2011',
        'phone_number.regex' => 'The phone number must start with 0 and be exactly 10 digits long.',
        'password.confirmed' => 'Passwords do not match.'
    ];

    public function mount()
    {
        $this->intake = Intake::where('status', 'Open')->where('type', 'Diploma')->first();
    }

    public function updatedIndexNumber()
    {
        $this->loading = true;
        $this->apiError = null;

        $this->studentData = null;
        $this->verified = false;
        $this->failed = false;

        $this->validateOnly('index_number');

        try {
            $verify = new FetchInformationAction();
            $index = explode('/', $this->index_number);

            if (count($index) == 3) {
                $response = $verify->PersonalHandle($index[0] . '/' . $index[1], $index[2]);

                if ($response && isset($response->status->code) && $response->status->code == 1) {
                    $this->student = $response; // Store complex data privately
                    // Extract and store simple data for public property
                    $this->studentData = [
                        'first_name' => $response->particulars->first_name ?? '',
                        'middle_name' => $response->particulars->middle_name ?? '',
                        'last_name' => $response->particulars->last_name ?? '',
                        'sex' => $response->particulars->sex ?? '',
                        'center_name' => $response->particulars->center_name ?? ''
                    ];
                    $this->verified = true;
                } else {
                    $this->failed = true;
                    $this->apiError = 'No information found for the provided index number.';
                }
            }
        } catch (\Exception $e) {
            $this->failed = true;
            $this->apiError = 'An error occurred while trying to fetch information. Please try again later.';
        } finally {
            $this->loading = false;
        }
    }

    public function submitForm()
    {
        $this->validate();

        if (!$this->studentData) {
            session()->flash('error', 'Student information is not available. Please verify your index number again.');
            return;
        }

        $user = User::create([
            'index_number' => $this->index_number,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Admission::create([
            'first_name' => $this->studentData['first_name'],
            'middle_name' => $this->studentData['middle_name'],
            'last_name' => $this->studentData['last_name'],
            'gender' => $this->studentData['sex'],
            'school_name' => $this->studentData['center_name'],
            'index_number' => $this->index_number,
            'phone' => $this->phone_number,
            'email' => $this->email,
            'user_id' => $user->id,
            'intake_id' => $this->intake->id,
            'step' => 1,
            'type' => 'Diploma'
        ]);

        $this->reset(['studentData', 'index_number', 'phone_number', 'email', 'password', 'password_confirmation']);
        session()->flash('message', 'Registration successful!');

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.register-component', [
            'studentData' => $this->studentData,
            'intake' => $this->intake
        ]);
    }
}
