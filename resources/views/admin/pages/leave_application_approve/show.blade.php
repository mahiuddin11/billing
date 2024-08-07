<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{$modal_title ?? 'Details'}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal">
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Employee Name: </strong>
                </div>
                <div class="col-md-8">
                    {{$modal_data->employee_id ?? 'N/A'}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <strong>Apply Date: </strong>
                </div>
                <div class="col-md-8">
                    {{$modal_data->apply_date ?? 'N/A'}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <strong>End Date: </strong>
                </div>
                <div class="col-md-8">
                    {{$modal_data->end_date ?? 'N/A'}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <strong>Reason: </strong>
                </div>
                <div class="col-md-8">
                    {{$modal_data->reason ?? 'N/A'}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <strong>Status: </strong>
                </div>
                <div class="col-md-8">
                    {{$modal_data->status ?? 'N/A'}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <strong>Joining Date: </strong>
                </div>
                <div class="col-md-8">
                    {{$modal_data->join_date ?? 'N/A'}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <strong>Address: </strong>
                </div>
                <div class="col-md-8">
                    {{$modal_data->address ?? 'N/A'}}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
            {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
    </div>
</div>
