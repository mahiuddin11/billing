@extends('admin.master')

@section('style')
    <style>
        td {
            padding: 0.72rem 0 !important;
        }

        ..form-control {
            padding: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $page_heading ?? 'Create' }}</h4>
                    <a href="{{ $back_url ?? 'javascript:;' }}" class="btn btn-dark">Back</a>
                </div>
                <div class="card-body">

                    <x-alert></x-alert>

                    <div class="basic-form">
                        <form action="{{ $store_url ?? '#' }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <label>Invoice Number : <span class="text-danger">*<span></label>
                                    <input class="form-control" value="{{ $invoice_no }}" name="invoice_no">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label>BILLING Month: <span class="text-danger">*<span></label>
                                    <div class="input-group">
                                        <input type="month" name="month" value="{{ date('Y-m') }}"
                                            class="form-control" />
                                    </div>
                                </div>


                                <div class="col-md-3 mb-1">
                                    <label>Provider : <span class="text-danger">*<span></label>
                                    <select class="form-control select2 supid" name="provider_id">
                                        <option selected disabled value="">--Select Provider--</option>
                                        @foreach ($providers as $key => $value)
                                            <option value="{{ $value->id }}">
                                                {{ $value->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-1">
                                    <label>Account : <span class="text-danger">*<span></label>
                                    <select class="form-control select2 supid" name="account_id">
                                        <option selected disabled value="">--Select Provider--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->head_code }}
                                                {{ $account->account_name }}</option>
                                            @if ($account->subAccount->isNotEmpty())
                                                @foreach ($account->subAccount as $subaccount)
                                                    <option value="{{ $subaccount->id }}">
                                                        - {{ $subaccount->head_code }} {{ $subaccount->account_name }}
                                                    </option>

                                                    @if ($subaccount->subAccount->isNotEmpty())
                                                        @foreach ($subaccount->subAccount as $subaccount2)
                                                            <option value="{{ $subaccount2->id }}">
                                                                --{{ $subaccount2->head_code }}
                                                                {{ $subaccount2->account_name }}</option>
                                                            @if ($subaccount2->subAccount->isNotEmpty())
                                                                @foreach ($subaccount2->subAccount as $subaccount3)
                                                                    <option value="{{ $subaccount3->id }}" disabled>
                                                                        ---{{ $subaccount3->head_code }}
                                                                        {{ $subaccount3->account_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <table class="table table-bordered table-hover" id="show_item">
                                    <thead>
                                        <tr>
                                            <th colspan="10">Select Product Item</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center" width="15%"><strong>Item</strong></td>
                                            <td class="text-center" width="10%"><strong>Description</strong></td>
                                            <td class="text-center"><strong>Unit</strong></td>
                                            <td class="text-center"><strong>Quantity</strong></td>
                                            <td class="text-center" width="10%"><strong>Rate</strong></td>
                                            <td class="text-center"><strong>VAT(%)</strong></td>
                                            <td class="text-center"><strong>From Date</strong></td>
                                            <td class="text-center"><strong>To Date</strong></td>
                                            <td class="text-center"><strong>Total</strong></td>
                                            <td class="text-center"><strong>Action</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="select2 form-control item_value" name="item_id[]"
                                                    id="item-option">
                                                    <option disabled selected>---Select Item---</option>
                                                    @foreach ($items as $value)
                                                        <option unit="{{ $value->unit }}" vat="{{ $value->vat }}"
                                                            value="{{ $value->id }}">
                                                            {{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <textarea name="description[]" class="form-control" rows="0"></textarea>
                                            </td>
                                            <td>
                                                <input type="text" readonly id="unitval" name="unit[]"
                                                    class="form-control unitval">
                                            </td>
                                            <td>
                                                <input type="number" id="quantity" name="qty[]"
                                                    class="form-control calculate qty">
                                            </td>
                                            <td>
                                                <input type="number" id="rate" name="rate[]"
                                                    class="form-control calculate rate">
                                            </td>
                                            <td>
                                                <input type="number" id="vat" name="vat[]"
                                                    class="form-control calculate vat">
                                            </td>

                                            <td>
                                                <input type="date" id="from_date" name="from_date[]"
                                                    class="form-control calculate from_date">
                                            </td>
                                            <td>
                                                <input type="date" id="to_date" name="to_date[]"
                                                    class="form-control calculate to_date">
                                            </td>
                                            <td>
                                                <input type="number" readonly id="total" name="total[]"
                                                    class="form-control total">
                                            </td>
                                            <td>
                                                <a id="add_item" class="btn btn-danger" style="white-space: nowrap"
                                                    href="javascript:;">
                                                    <i class="fa fa-minus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-right" colspan="8">Total</td>
                                            <td class="text-right"><span id="totalsum">0.00</span></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="col-md-12 text-right p-1" onclick="addRow()">
                                    <button type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <textarea cols="100" rows="3" class="form-control" name="note" placeholder="Narration"
                                                type="text"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-1 form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    function totalsumFun() {
        var totalRowSum = 0;
        $('.total').each(function () {
            totalRowSum += Number($(this).val());
        });
        $('#totalsum').text(totalRowSum);
        let discount = $('.discount-input').val();
        let netTotal = totalRowSum - discount;
        $('.cart_net_total').text(netTotal);
        let amount = $('#paymentTypeCheck').val();
        $('.cart_due').text(netTotal - amount);
    }

    function getBalance() {
        let amount = $('.getAccount option:selected').attr('amount');
        $('.available-balance').text('Available Balance ' + amount);
    }
    $('#paymentTypeCheck').on('input', function () {
        let amount = $('.getAccount option:selected').attr('amount');
        let payamount = $(this).val();
        if (Number(amount) < Number(payamount)) {
            $(this).val(amount)
        };
        totalsumFun()
    });

    $(document).on('change', '.item_value', function () {
        let item = $(this).closest('tr').find('.item_value option:selected').attr('unit');
        let vat = $(this).closest('tr').find('.item_value option:selected').attr('vat');
        $(this).closest('tr').find('.unitval').val(item);
        $(this).closest('tr').find('.vat').val(vat);
    })

    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
        totalsumFun();
    })

    function getDay(formday,today){

        const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        const firstDate = new Date(formday);
        const secondDate = new Date(today);
        const diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay))+1;
        return  diffDays;
    }

    $(document).on('input', '.calculate', function () {
        let quantity = $(this).closest('tr').find('.qty').val();
        let rate = $(this).closest('tr').find('.rate').val();
        let vat = $(this).closest('tr').find('.vat').val();
        let from_date = $(this).closest('tr').find('.from_date').val() ? $(this).closest('tr').find('.from_date').val() : '2022-12-1';
        let to_date = $(this).closest('tr').find('.to_date').val() ? $(this).closest('tr').find('.to_date').val() : '2022-12-30' ;
        let countDay = getDay(from_date,to_date);
        let totalsum = $(this).closest('tr').find('.total');
        let sum = (Number(quantity) * Number(rate));
        let onedaysalary = sum / 30;
        let daySum = onedaysalary * countDay;
        let totalWithVat = daySum * (Number(vat) / 100);
        let total = daySum + totalWithVat;
        totalsum.val(total.toFixed(2));

        totalsumFun()

    })

    let count = 0;
    function addRow() {
        let table = `<tr>`;
        table += `<td>`;
        table += `                  <select class="select2 form-control item_value" name="item_id[]"
                                                id="item-option">
                                                <option disabled selected>---Select Item---</option>
                                                @foreach ($items as $value)
                                                <option unit="{{$value->unit}}" vat="{{ $value->vat }}" value="{{ $value->id }}">
                                                    {{ $value->name }}</option>
                                                @endforeach
                                            </select>`;
        table += `</td>`;
        table += `<td>`;
        table += `<textarea name="description[]" class="form-control" rows="0"></textarea>`;
        table += `</td>`;
        table += `<td>`;
        table += `<input type="text" readonly id="unitval" name="unit[]" class="form-control  unitval">`;
        table += `</td>`;
        table += `<td>`;
        table += `<input type="number" id="quantity" name="qty[]"class="form-control  calculate qty">`;
        table += `</td>`;
        table += `<td>`;
        table += `<input type="number" id="rate" name="rate[]" class="form-control  calculate rate">`;
        table += `</td>`;
        table += `<td>`;
        table += `<input type="number" id="vat" name="vat[]" class="form-control  calculate vat">`;
        table += `</td>`;
        table += `<td>`;
        table += `<input type="date" id="from_date" name="from_date[]" class="form-control calculate from_date">`;
        table += `</td>`;
        table += `<td>`;
        table += `<input type="date" id="to_date" name="to_date[]" class="form-control calculate to_date">`;
        table += `</td>`;
        table += `<td>`;
        table += `<input type="text" readonly id="total" name="total[]" class="form-control  total">`;
        table += `</td>`;
        table += `<td>`;
        table += `<a id="add_item" class="btn btn-danger removeRow" style="white-space: nowrap"
                                                href="javascript:;" title="Add Item">
                                                <i class="fa fa-minus"></i>
                                            </a>`;
        table += `</td>`;
        table += `</tr>`;

        $('#show_item').append(table);
    }

</script>
@endpush
