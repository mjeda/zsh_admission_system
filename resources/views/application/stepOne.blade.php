@extends('layouts.portal')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Personal Information</h1>
    </div>

      <table class="table table-bordered">
          <thead>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Date of Birth</th>
            <th>School</th>
          </thead>

          <tbody>
            <tr>
                <td>{{$admission->first_name}}</td>
                <td>{{$admission->middle_name}}</td>
                <td>{{$admission->last_name}}</td>
                <td>{{$admission->date_of_birth}}</td>
                <td>{{$admission->school_name}}</td>
            </tr>
          </tbody>

      </table>

    <form class="g-3">
        <fieldset class="row">
            <legend>Basic Information</legend>
            <div class="col-md-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text"  name="name" class="form-control" id="phone">
            </div>
            <div class="col-md-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email">
            </div>

            <div class="col-md-2">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" id="address">
            </div>

            <div class="col-md-2">
                <label for="district" class="form-label">District</label>
                <input type="text" name="district" class="form-control" id="district">
            </div>

            <div class="col-md-2">
                <label for="phone" class="form-label">Region</label>
                <input type="text" name="region" class="form-control" id="phone">
            </div>
        </fieldset>

        <fieldset class="row mt-5">
            <legend>Next of kin</legend>
            <div class="col-md-3">
                <label for="kin_name" class="form-label">Name</label>
                <input type="text" name="kin_name" class="form-control" id="kin_name">
            </div>

            <div class="col-md-3">
                <label for="kin_phone" class="form-label">Phone</label>
                <input type="text" name="kin_email" class="form-control" id="kin_phone">
            </div>

            <div class="col-md-3">
                <label for="email" class="form-label">Email</label>
                <input type="email"  class="form-control" id="email">
            </div>

            <div class="col-md-3">
                <label for="relation" class="form-label">Relationship</label>
                <input type="text" name="relation" class="form-control" id="relation">
            </div>

            <div class="col-md-3">
                <label for="occupation" class="form-label">Occupation</label>
                <input type="text" name="occupation" class="form-control" id="occupation">
            </div>

            <div class="col-md-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="kin_address" class="form-control" id="address">
            </div>

            <div class="col-md-3">
                <label for="district" class="form-label">District</label>
                <input type="text" name="kin_district" class="form-control" id="district">
            </div>

            <div class="col-md-3">
                <label for="region" class="form-label">Region</label>
                <input type="text" name="kin_region" class="form-control" id="region">
            </div>
        </fieldset>

        <div class="col-12 mt-3 float-right">
            <button type="submit" class="btn btn-primary">Save Records</button>
        </div>
    </form>

@endsection
