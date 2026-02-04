<?php

namespace App\Http\Controllers\Auth;

use App\Action\FetchInformationAction;
use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Intake;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    private FetchInformationAction $fetchInformationAction;
    private $data;
    private $intake;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FetchInformationAction $fetchInformationAction)
    {
        $this->middleware('guest');
        $this->fetchInformationAction = $fetchInformationAction;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $input)
    {
        //get active intake
        $this->intake = Intake::where('status', 'Open')->first();
        if(!$this->intake){
            return Validator::make($input, [
                'particular' => 'required',
            ],
                ['particular.required'=> 'No Open Intake']);
        }

        //get particulars
        $index = explode('/', $input['index_number']);
        if(count($index) == 3){
            Validator::make($input, 
                ['particular' => 'required'],
                ['particular.required'=> 'Invalid Index Number Format: Eg. S0001/0001/2011']
            );
            $index_number = $index[0].'-'.$index[1];
            $year = $index[2];
        }else{
            return Validator::make($input, 
                ['particular' => 'required'],
                ['particular.required'=> 'Invalid Index Number Format: Eg. S0001/0001/2011']
            );
        }


        $data = $this->fetchInformationAction->PersonalHandle($index_number, $year);

        if(!$data){
            return Validator::make($input, [
                'particular' => 'required',
            ],
                ['particular.required'=> 'Registration failed, please try again later or contact us']);
        }

        $input['particular'] = $data;

        $this->data = $data;

        return Validator::make($input, [
            'particular' => 'required',
            'index_number' => ['required', 'string', 'max:255', 'regex:/^[SP]\d{4}\/\d{4}\/\d{4}$/', 'unique:admissions'],
            'phone_number' => ['required', 'regex:/^0\d{9}$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users', 'ends_with:gmail.com,yahoo.com,hotmail.com,icloud.com'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],
        [
            'particular.required'=> 'Unknown Index Number',
            'phone_number.regex' => 'The phone number must start with 0 and be exactly 10 digits long.',
            'index_number.regex'=> 'Invalid Index Number Format: Eg. S0001/0001/2011'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return array
     */
    protected function create(array $input)
    {
       $user =  User::create([
            'index_number' => $input['index_number'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        Admission::create([
            'first_name' => $this->data->particulars->first_name,
            'middle_name' =>  $this->data->particulars->middle_name,
            'last_name' =>  $this->data->particulars->last_name,
            'gender'=> $this->data->particulars->sex,
            'school_name' =>  $this->data->particulars->center_name,
            'index_number' =>  $input['index_number'],
            'phone' =>  $input['phone_number'],
            'email' => $user->email,
            'user_id' => $user->id,
            'intake_id' => $this->intake->id,
            'step' => 1,
        ]);

        return $user;
    }
}
