@extends('layouts.master')

@section('title') TDS Returns @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Data Capture - Deduction - TDS @endslot
    @slot('title') TDS Returns @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Capture quarterly TDS return details. This information is used to generate FORM-16 at year end.</h5>
                    <div class="flex-shrink-0">
                        <button class="btn btn-success btn-sm">Need Help</button>
                    </div>
                </div>
            </div>
            
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form method="GET" action="{{ route('capture.tdsreturns') }}">
                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-6">
                            <div>
                                <label class="form-label text-muted d-none">Financial Year</label>
                                <div class="input-group">
                                    <div class="input-group-text">Financial Year</div>
                                    <select name="financial_year" class="form-control" required>
                                        @php
                                            $currentYear = date('Y');
                                            $years = [
                                                ($currentYear-1) . '-' . substr($currentYear, -2),
                                                $currentYear . '-' . substr($currentYear+1, -2),
                                                ($currentYear+1) . '-' . substr($currentYear+2, -2)
                                            ];
                                        @endphp
                                        @foreach($years as $yr)
                                            <option value="{{ $yr }}" {{ $financialYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <div>
                                <button type="submit" class="btn btn-secondary"><i class="ri-refresh-line me-1"></i> Load</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 200px;">QUARTER</th>
                                <th scope="col">RECEIPT NUMBER</th>
                                <th scope="col" style="width: 80px;" class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                                <tr>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $return->quarter }}</div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm inline-input w-50" id="receipt_{{ $return->id }}" value="{{ $return->receipt_number }}">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0 inline-save-btn" data-id="{{ $return->id }}" style="width: 24px; height: 24px; padding: 0;" title="Save">
                                            <i class="mdi mdi-check fs-12"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // AJAX save TDS Returns
        $(document).on('click', '.inline-save-btn', function(e) {
            e.preventDefault();
            
            var btn = $(this);
            var id = btn.data('id');
            var receipt = $('#receipt_' + id).val();
            
            var originalBtnHtml = btn.html();
            btn.html('<i class="mdi mdi-loading mdi-spin fs-12"></i>').prop('disabled', true);

            $.ajax({
                url: '{{ route('capture.tdsreturns.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    receipt_number: receipt
                },
                success: function(response) {
                    btn.html('<i class="mdi mdi-check-all fs-12"></i>').prop('disabled', false);
                    setTimeout(function() {
                        btn.html(originalBtnHtml);
                    }, 2000);
                    
                    if(!response.success) {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    btn.html(originalBtnHtml).prop('disabled', false);
                    var errMsg = 'Error saving data';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    alert(errMsg);
                }
            });
        });

        $(document).on('keypress', '.inline-input', function(e) {
            if(e.which == 13) { // Enter key
                e.preventDefault();
                var btn = $(this).closest('tr').find('.inline-save-btn');
                btn.click();
            }
        });
    });
</script>
@endsection
