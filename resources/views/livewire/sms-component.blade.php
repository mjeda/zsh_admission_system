<div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-muted font-weight-bold">Send mobile sms to the students</h5>
            </div>
            <div class="row">
                <div class="col">
                    <!--  form  -->
                    <div>
                        <div>
                            <label for="message">Message:</label>
                            <textarea wire:model="message" rows="4" cols="50" class="form-control @error('message') is-invalid @enderror"></textarea>
                            @error("message")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <label for="status">Intake:</label>
                                <select wire:model="intake" class="form-control mr-2 @error('intake') is-invalid @enderror select-2">
                                    <option value="">-- Select Intake --</option>
                                    @forelse($intakes as $intake)
                                        <option value="{{$intake->id}}" @if(request('intake') == $intake->id) selected @endif>{{$intake->title. " round - ". $intake->round .", ". $intake->year}}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error("intake")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="col">
                                <label for="status">Status:</label>
                                <select wire:model="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="">-- Select status --</option>
                                    <option value="CREATED">Created</option>
                                    <option value="SUBMITTED">Submitted</option>
                                    <option value="SELECTED">Selected</option>
                                </select>
                                @error("status")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <button wire:click="filterStudent" type="button" class="btn btn-primary">filter student</button>
                            </div>
                            @if(!empty($admissions))
                            <div class="col">
                                <button wire:click="sendSms" type="button" class="pull-right" class="btn btn-success">Send SMS</button>
                            </div>
                            @endif
                        </div>
                    <div>
                    <!-- student list  -->
                    <div class="mt-4">
                        <table class="table table-striped border-left border-right border-bottom">
                            <thead>
                            <tr>
                                <th width="35px" class="border-right">SN</th>
                                <th>Full name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>School name</th>
                                <th>Index Number</th>
                                <th>Created at</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($admissions as $item)
                                <tr>
                                    <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->first_name ." ". $item->middle_name ." ". $item->last_name}}</td>
                                    <td>{{ $item->gender }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->school_name }}</td>
                                    <td>{{ $item->index_number }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <div class="float-right">
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="cancel({{ $loop->index }})" 
                                                onclick="confirm('Are you sure you want to remove the student?') || event.stopImmediatePropagation()">
                                                Cancel
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">
                                    No content in this table
                                </td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
