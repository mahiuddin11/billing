@extends('admin.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{$page_heading ?? 'Create'}}</h4>
                <a href="{{$back_url ?? 'javascript:;'}}" class="btn btn-dark">Back</a>
            </div>
            <div class="card-body">

                <x-alert></x-alert>

                <div class="basic-form">
                    <form action="{{ $store_url ?? '#' }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row" id="service-list">
                            <div class="col-md-6 mb-1">
                                    <label for="">Customer Name</label>
                                    <select class="select2 form-control" name="customer_id">
                                        <option selected disabled>Select</option>
                                        @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}-{{$customer->username}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            <div class="col-md-6 mb-1">
                                    <label for="">Date</label>
                                <input type="date" name="date" class="form-control">
                                </div>
                        </div>
                        <div class="service_div">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                            <label for="">Service Name</label>
                            <input type="text" class="form-control" id="service_name" name="service_name[]">
                            </div>
                            <div class="col-md-4 mb-1">
                            <label for="">Price</label>
                            <input type="number" class="form-control" id="amount" name="amount[]">
                            </div>
                            <div class="col-md-2 mb-1">
                           <button type="button"  class="btn btn-info mt-2 additem">Add</button>
                            </div>
                        </div>
                        </div>
                        <div class="mb-1 form-group">
                            <button type="submit" class="btn btn-primary mt-3">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
  $(document).on('click','.additem',function(){
let html = `<div class="row"><div class="col-md-6 mb-1">
    <label for="">Service Name</label>
    <input type="text" class="form-control" id="service_name" name="service_name[]">
</div>
<div class="col-md-4 mb-1">
    <label for="">Price</label>
    <input type="number" class="form-control" id="amount" name="amount[]">
</div>
<div class="col-md-2 mb-1">
    <button type="button" class="btn btn-danger mt-2 clear">Remove</button>
</div></div>`;
$('.service_div').append(html);
   });

   $(document).on('click','.clear',function(){
    if(confirm("Are You wan't to remove this")){
$(this).closest('.row').remove();
    }
   })
</script>
@endpush


