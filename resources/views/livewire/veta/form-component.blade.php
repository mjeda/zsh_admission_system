<div>
    @include('errors')
    @if($admission?->status == "CREATED" and $admission?->step >= 4)
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step">
                    <a href="#step-1" type="button" class="btn btn-circle {{ $currentStep != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                    <p>Personal</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-2" type="button" class="btn btn-circle {{ $currentStep != 2 ? 'btn-default' : 'btn-primary' }}">2</a>
                    <p>Next of Kin</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-3" type="button" class="btn btn-circle {{ $currentStep != 3 ? 'btn-default' : 'btn-primary' }}">3</a>
                    <p>Payment</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-4" type="button" class="btn btn-circle {{ $currentStep != 4 ? 'btn-default' : 'btn-primary' }}" disabled="disabled">4</a>
                    <p>Courses</p>
                </div>
            </div>
        </div>

        <div class="row setup-content {{ $currentStep != 1 ? 'displayNone' : '' }}" id="step-1">
            {{--        <div class="col-xs-12">--}}
            <div class="col-md-12">
                <h5> Personal Information</h5>
                <div class="row">
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="first_name" class="form-label">First name</label>
                        <input type="text" wire:model="first_name" class="form-control" placeholder="First name">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="middle_name" class="form-label">Middle name</label>
                        <input type="text" wire:model="middle_name" class="form-control" placeholder="Middle name">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="last_name" class="form-label">Last name</label>
                        <input type="text" wire:model="last_name" class="form-control" placeholder="Last name">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="gender" class="form-label">Gender</label>
                        <select wire:model="gender" class="form-control" >
                            <option value="">-- select gender --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="school_name" class="form-label">School name</label>
                        <input type="text" wire:model="school_name" class="form-control" placeholder="School name" disabled>
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="phone_number" class="form-label">Phone number</label>
                        <input type="text" wire:model="phone_number" min="10" max="10" class="form-control @error('phone_number') is-invalid @enderror" placeholder="Phone number">
                        @error("phone_number")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small>This number used for receive code after selection</small>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" wire:model="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address">
                        @error("address")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="region" class="form-label">Region</label>
                        <input list="regionList" placeholder="enter region" wire:model="region" class="form-control @error('region') is-invalid @enderror" id="region">
                        <datalist id="regionList">
                            <option value="Mjini Magharibi">Mjini Magharibi (Urban)</option>
                            <option value="Mkoa wa Kaskazini Unguja">Mkoa wa Kaskazini Unguja (North Unguja Region)</option>
                            <option value="Mkoa wa Kusini Unguja">Mkoa wa Kusini Unguja (South Unguja Region)</option>
                            <option value="Mkoa wa Kaskazini Pemba">Mkoa wa Kaskazini Pemba (North Pemba Region)</option>
                            <option value="Mkoa wa Kusini Pemba">Mkoa wa Kusini Pemba (South Pemba Region)</option>
                        </datalist>
                        @error("region")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="district" class="form-label">District</label>
                        <input list="districtList" placeholder="enter district" wire:model="district" class="form-control @error('district') is-invalid @enderror" id="district">
                        <datalist id="districtList">
                            <option value="Wilaya ya mjini">Wilaya ya mjini</option>
                            <option value="Wilaya ya Magharibi A">Wilaya ya Magharibi A</option>
                            <option value="Wilaya ya Magharibi B">Wilaya ya Magharibi B</option>
                            <option value="Wilaya ya Kaskazini A">Wilaya ya Kaskazini A</option>
                            <option value="Wilaya ya Kaskazini B">Wilaya ya Kaskazini B</option>
                            <option value="Wilaya ya Kati">Wilaya ya Kati</option>
                            <option value="Wilaya ya Kusini">Wilaya ya Kusini</option>
                            <option value="Wilaya ya Wete">Wilaya ya Wete</option>
                            <option value="Wilaya ya Micheweni">Wilaya ya Micheweni</option>
                            <option value="Wilaya ya Chake Chake">Wilaya ya Chake Chake</option>
                            <option value="Wilaya ya Mkoani">Wilaya ya Mkoani</option>
                        </datalist>
                        @error("district")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row my-2">
                    <div class="form-group col-md-3 col-sm-12">
                        <label for="index_number" class="form-label">Date of Birth</label>
                        <input type="date" wire:model="dob" class="form-control @error('dob') is-invalid @enderror" placeholder="DOB">
                        @error("dob")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label for="index_number" class="form-label">Index number</label>
                        <input type="text" wire:model="index_number" class="form-control @error('index_number') is-invalid @enderror" placeholder="enter form 4 number" disabled>
                        @error("index_number")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    @if($add_index_number >= 2 OR $index_number_2 != null OR $index_number_3 != null)
                        <div class="form-group col-md-3 col-sm-12">
                            <label for="index_number" class="form-label">
                                2 <sup>nd</sup> Index number
                            </label>
                            <input type="text" wire:model="index_number_2" class="form-control @error('index_number_2') is-invalid @enderror" placeholder="S0000/0000/0000">
                            @error("index_number_2")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <label for="index_number" class="form-label">
                                3 <sup>rd</sup> Index number
                                <span wire:click="addIndex(1)" title="cancel" class="border p-1 bg-danger text-light" role="button">X</span>
                            </label>
                            <input type="text" wire:model="index_number_3" class="form-control @error('index_number_3') is-invalid @enderror" placeholder="S0000/0000/0000">
                            @error("index_number_3")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                    @if($add_index_number == 1 AND $index_number_2 == null OR $add_index_number == 1 AND $index_number_3 == null)
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="region" class="form-label">If you have multiple index number</label>
                            <div><button wire:click="addIndex(2)" class="btn btn-info">Add</button></div>
                        </div>
                    @endif
                </div>

                <div class="btn-group pull-right">
                    {{--                        <button class="btn btn-primary nextBtn" wire:click="SavePersonal" type="button" >Save</button>--}}
                    <button class="btn btn-primary nextBtn" wire:click="nextStep(2)" type="button">Next</button>
                </div>
            </div>
            {{--        </div>--}}
        </div>
        <div class="row setup-content {{ $currentStep != 2 ? 'displayNone' : '' }}" id="step-2">
            {{--        <div class="col-xs-12">--}}
            <div class="col-md-12">
                <h5> Next Kin Information</h5>
                <div class="row">
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="next_kin_name" class="form-label">Full name</label>
                        <input type="text" wire:model="next_of_kin_name" class="form-control @error('next_of_kin_name') is-invalid @enderror" placeholder="Full name">
                        @error("next_of_kin_name")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="next_kin_phone" class="form-label">Phone number</label>
                        <input type="text" wire:model="next_of_kin_phone" min="10" max="10" class="form-control @error('next_of_kin_phone') is-invalid @enderror" placeholder="Phone">
                        @error("next_of_kin_phone")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="next_kin_email" class="form-label">Email</label>
                        <input type="email" wire:model="next_of_kin_email" class="form-control @error('next_of_kin_email') is-invalid @enderror" placeholder="Email">
                        @error("next_of_kin_email")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="next_kin_relation" class="form-label">Relation</label>
                        <select wire:model="next_of_kin_relation" class="form-control @error('next_of_kin_relation') is-invalid @enderror">
                            <option value="" class="">--- Choose relation ---</option>
                            <option value="mother">Mother</option>
                            <option value="father">Father</option>
                            <option value="stepmother">Stepmother</option>
                            <option value="stepfather">Stepfather</option>
                            <option value="brother">Brother</option>
                            <option value="sister">Sister</option>
                            <option value="sibling">Sibling</option>
                            <option value="spouse">Spouse</option>
                            <option value="friend">Friend</option>
                            <option value="colleague">Colleague</option>
                            <option value="relative">Relative</option>
                            <option value="partner">Partner</option>
                            <option value="guardian">Guardian</option>
                            <option value="other">Other</option>
                        </select>
                        @error("next_of_kin_relation")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="next_kin_region" class="form-label">Region</label>
                        <input list="kinregionList" placeholder="enter region" wire:model="next_of_kin_region" class="form-control @error('next_of_kin_region') is-invalid @enderror" id="next_of_kin_region">
                        <datalist id="kinregionList">
                            <option value="Mjini Magharibi">Mjini Magharibi (Urban)</option>
                            <option value="Mkoa wa Kaskazini Unguja">Mkoa wa Kaskazini Unguja (North Unguja Region)</option>
                            <option value="Mkoa wa Kusini Unguja">Mkoa wa Kusini Unguja (South Unguja Region)</option>
                            <option value="Mkoa wa Kaskazini Pemba">Mkoa wa Kaskazini Pemba (North Pemba Region)</option>
                            <option value="Mkoa wa Kusini Pemba">Mkoa wa Kusini Pemba (South Pemba Region)</option>
                        </datalist>
                        @error("next_kin_region")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="next_kin_district" class="form-label">District</label>
                        <input list="kindistrictList" placeholder="enter district" wire:model="next_of_kin_district" class="form-control @error('next_of_kin_district') is-invalid @enderror" id="next_of_kin_district">
                        <datalist id="kindistrictList">
                            <option value="Wilaya ya mjini">Wilaya ya mjini</option>
                            <option value="Wilaya ya Magharibi A">Wilaya ya Magharibi A</option>
                            <option value="Wilaya ya Magharibi B">Wilaya ya Magharibi B</option>
                            <option value="Wilaya ya Kaskazini A">Wilaya ya Kaskazini A</option>
                            <option value="Wilaya ya Kaskazini B">Wilaya ya Kaskazini B</option>
                            <option value="Wilaya ya Kati">Wilaya ya Kati</option>
                            <option value="Wilaya ya Kusini">Wilaya ya Kusini</option>
                            <option value="Wilaya ya Wete">Wilaya ya Wete</option>
                            <option value="Wilaya ya Micheweni">Wilaya ya Micheweni</option>
                            <option value="Wilaya ya Chake Chake">Wilaya ya Chake Chake</option>
                            <option value="Wilaya ya Mkoani">Wilaya ya Mkoani</option>
                        </datalist>
                        @error("next_of_kin_district")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row my-2">
                    <div class="form-group col">
                        <label for="next_kin_address" class="form-label">Address</label>
                        <textarea wire:model="next_of_kin_address" rows="3" class="form-control @error('next_of_kin_address') is-invalid @enderror" placeholder="Address"></textarea>
                        @error("next_of_kin_address")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="btn-group pull-right">
                    <button class="btn btn-light nextBtn" type="button" wire:click="back(1)">Back</button>
                    {{--                        <button class="btn btn-primary nextBtn" type="button" wire:click="savekin">Save</button>--}}
                    <button class="btn btn-primary nextBtn" type="button" wire:click="nextStep(3)">Next</button>
                </div>
            </div>
            {{--        </div>--}}
        </div>
        <div class="row setup-content {{ $currentStep != 3 ? 'displayNone' : '' }}" id="step-3">
            {{--        <div class="col-xs-12">--}}
            <div class="col-md-12">
                {{--                <h5> Payment </h5>--}}
                <div class="row my-3">
                    @if($control_number_status == 'PAID')
                        <div class="col text-center">
                            <h4>Thank You!</h4>
                            <h5 class="mt-4"><strong>Payment Received</strong></h5>
                        </div>
                    @else
                        <div class="col">
                            <div class="text-center">
                                <h4>Fanya malipo ya control number hii</h4>
                                <h5 class="mt-4"><strong>{{ $control_number }} - 30,000 TZS</strong></h5>
                            </div>
                            <div class="container" style="width: 750px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong class="px-4">KULIPA KWA </strong>
                                        <img src="{{ asset('images/tigo-pesa.png') }}" class="pull-left" style="width: 100px; height: 90px;">
                                        <ul class="text-dark text-left ">
                                            <li><b>*150*01#</b></li>
                                            <li><b>Changua lipa bili 4</b></li>
                                            <li><b>Kupata majina ya kampuni 2</b></li>
                                            <li><b>UTT AMIS & ELIMU 9</b></li>
                                            <li><b>Lipa ada 4</b></li>
                                            <li><b>Ingiza control number na malizia</b></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <strong class="px-4">KULIPA KWA </strong>
                                        <img src="{{ asset('images/pbz.png') }}" style="width: 80px; height: 80px;">
                                        <ul>
                                            <li><b>Tembelea Tawi/wakala wa PBZ Bank ukiwa na control number yako.</b></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="btn-group pull-right">
                    <button class="btn btn-light nextBtn" type="button" wire:click="back(2)">Back</button>
                    <button class="btn btn-primary" wire:click="nextStep(4)" type="button">Next</button>
                </div>
            </div>
            {{--        </div>--}}
        </div>
        <div class="row setup-content {{ $currentStep != 4 ? 'displayNone' : '' }}" id="step-4">
            {{--            <div class="col-xs-12">--}}
            <div class="col-md-12">
                <h5> Select a Course <small class="text-muted">(Please select your First, Second and Third choice)</small></h5>
                <h6>If you can not see the course please refresh the page...</h6>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Applicable Courses according to your results</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($courses as $course)
                        {{-- <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="course" value="{{$course->id}}" wire:model="selectedCourses" id="flexRadioDefault{{$course->id}}">
                                    <label class="form-check-label" for="flexRadioDefault{{$course->id}}">
                                        {{$course->name}}
                                    </label>
                                </div>
                            </td>
                        </tr> --}}

                        <tr>
                            <td>
                                @if(in_array($course->id, $availableCourseIds))
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="selectedCourses[]" value="{{$course->id}}" wire:click="selectCourse({{$course}})" id="flexCheckbox{{$course->id}}">
                                        <label class="form-check-label" for="flexCheckbox{{$course->id}}">
                                            {{$course->name}}
                                        </label>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-danger text-center">No Course Matched Your Results</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                @if(count($selectedCourses) > 0)
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 50px;">Choice</th>
                            <th>Course</th>
                        </tr>

                        @foreach($selectedCourses as $available)
                            <tr>
                                <th class="text-center">{{ $loop->index + 1 }}</th>
                                <th>{{ $available['name'] }}</th>
                            </tr>
                        @endforeach
                    </table>
                @endif
                <div class="btn-group pull-right">
                    <button class="btn btn-light nextBtn" type="button" wire:click="back(3)">Back</button>
                    @if(count($selectedCourses) > 0)
                        <button class="btn btn-success" wire:click="submitForm" type="button">Finish!</button>
                    @endif
                </div>
            </div>
            {{--            </div>--}}
        </div>
    @endif
</div>
