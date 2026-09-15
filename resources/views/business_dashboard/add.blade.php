@extends('layouts.master')

@section('title')
Add Business
@endsection

@section('css')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Business @endslot
@slot('title') Add Business @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Add Business</h4>
            </div>


        <div class="card-body">

            <form action="{{ route('business.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Business Name *</label>
                    <input type="text"
                           name="business_name"
                           class="form-control"
                           value="{{ old('business_name') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">PAN Number</label>
                    <input type="text"
                           name="pan_number"
                           class="form-control"
                           value="{{ old('pan_number') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address"
                              class="form-control"
                              rows="3">{{ old('address') }}</textarea>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">State</label>

                        <select name="state"
                                id="state"
                                class="form-control select2">

                            <option value="">Select State</option>

                            @foreach($statesData['states'] as $state)
                                <option value="{{ $state['state'] }}">
                                    {{ $state['state'] }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">District</label>

                        <select name="district"
                                id="district"
                                class="form-control select2">

                            <option value="">Select District</option>

                        </select>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">City</label>

                        <input type="text"
                               name="city"
                               class="form-control"
                               value="{{ old('city') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pincode</label>

                        <input type="text"
                               name="pincode"
                               class="form-control"
                               value="{{ old('pincode') }}">
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Business Constitution
                    </label>

                    <select name="business_constitution"
                            class="form-control">

                        <option value="">
                            Select Constitution
                        </option>

                        <option value="Proprietorship">
                            Proprietorship
                        </option>

                        <option value="Partnership">
                            Partnership
                        </option>

                        <option value="LLP">
                            LLP
                        </option>

                        <option value="Private Limited">
                            Private Limited
                        </option>

                        <option value="Public Limited">
                            Public Limited
                        </option>

                    </select>
                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Save Business
                </button>

            </form>

        </div>
    </div>
</div>


</div>

@endsection

@section('script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function () {

    $('.select2').select2({
        width: '100%'
    });

    let statesData = @json($statesData['states']);

    $('#state').on('change', function () {

        let selectedState = $(this).val();

        $('#district').html(
            '<option value="">Select District</option>'
        );

        let stateObj = statesData.find(function(item){
            return item.state === selectedState;
        });

        if(stateObj){

            $.each(stateObj.districts, function(index, district){

                $('#district').append(
                    '<option value="' + district + '">' +
                    district +
                    '</option>'
                );

            });

        }

        $('#district').trigger('change');

    });

});

</script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
