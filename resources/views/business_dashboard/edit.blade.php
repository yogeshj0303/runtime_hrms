@extends('layouts.master')

@section('title')
Edit Business
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Business @endslot
    @slot('title') Edit Business @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">

            <div class="card-header">
                <h4 class="card-title mb-0">
                    Edit Business
                </h4>
            </div>

            <div class="card-body">

                <form action="{{ route('business.update',$business->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            Business Name *
                        </label>

                        <input type="text"
                               name="business_name"
                               class="form-control @error('business_name') is-invalid @enderror"
                               value="{{ old('business_name',$business->business_name) }}">

                        @error('business_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            PAN Number
                        </label>

                        <input type="text"
                               name="pan_number"
                               class="form-control"
                               value="{{ old('pan_number',$business->pan_number) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Address
                        </label>

                        <textarea name="address"
                                  class="form-control"
                                  rows="3">{{ old('address',$business->address) }}</textarea>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                State
                            </label>

                            <select name="state"
                                    id="state"
                                    class="form-select select2">

                                <option value="">
                                    Select State
                                </option>

                                @foreach($statesData['states'] as $state)
                                    <option value="{{ $state['state'] }}"
                                        {{ old('state',$business->state) == $state['state'] ? 'selected' : '' }}>
                                        {{ $state['state'] }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                District
                            </label>

                            <select name="district"
                                    id="district"
                                    class="form-select select2">

                                <option value="">
                                    Select District
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                City
                            </label>

                            <input type="text"
                                   name="city"
                                   class="form-control"
                                   value="{{ old('city',$business->city) }}">
                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Pincode
                            </label>

                            <input type="text"
                                   name="pincode"
                                   class="form-control"
                                   value="{{ old('pincode',$business->pincode) }}">
                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Business Constitution
                        </label>

                        <select name="business_constitution"
                                class="form-select">

                            <option value="">
                                Select Constitution
                            </option>

                            <option value="Proprietorship"
                                {{ old('business_constitution',$business->business_constitution) == 'Proprietorship' ? 'selected' : '' }}>
                                Proprietorship
                            </option>

                            <option value="Partnership"
                                {{ old('business_constitution',$business->business_constitution) == 'Partnership' ? 'selected' : '' }}>
                                Partnership
                            </option>

                            <option value="LLP"
                                {{ old('business_constitution',$business->business_constitution) == 'LLP' ? 'selected' : '' }}>
                                LLP
                            </option>

                            <option value="Private Limited"
                                {{ old('business_constitution',$business->business_constitution) == 'Private Limited' ? 'selected' : '' }}>
                                Private Limited
                            </option>

                            <option value="Public Limited"
                                {{ old('business_constitution',$business->business_constitution) == 'Public Limited' ? 'selected' : '' }}>
                                Public Limited
                            </option>

                        </select>

                    </div>

                    <button type="submit"
                            class="btn btn-primary">
                        Update Business
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

    let selectedState = "{{ old('state',$business->state) }}";
    let selectedDistrict = "{{ old('district',$business->district) }}";

    function loadDistricts(stateName)
    {
        $('#district').html(
            '<option value="">Select District</option>'
        );

        let stateObj = statesData.find(function(item){
            return item.state === stateName;
        });

        if(stateObj){

            $.each(stateObj.districts, function(index, district){

                let selected =
                    district === selectedDistrict
                    ? 'selected'
                    : '';

                $('#district').append(
                    '<option value="'+district+'" '+selected+'>'+district+'</option>'
                );

            });

        }

        $('#district').trigger('change');
    }

    $('#state').on('change', function () {
        loadDistricts($(this).val());
    });

    if(selectedState){
        loadDistricts(selectedState);
    }

});

</script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection