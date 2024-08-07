<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from cashier.dotlines.com.bd/ci/?i=INVBU2309300500368399 by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 Nov 2023 11:23:10 GMT -->
<head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="Internet (Selfcare) Website" />
    <meta name="author" content="SSD-TECH" />
    <link href="img/favicon.html" type="image/x-icon" rel="shortcut icon" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;family=Sora:wght@300;400;500;600;700;800&amp;display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{asset('admin_assets/ci/css/style.css')}}" />
    <title> Invoice </title>
  </head>

  <body>
    <header class="payments-header">
      <div class="container">
        <div class="brand">
            {!! $billing->company->logo ?? '' !!}

        </div>
      </div>
    </header>

    <div class="container">
      <div class="payments-area">
        <div class="card pay-bill">
          <div class="card-header">Payment summary</div>
          <div class="card-body">
            <p>Marchant Name: <span>{{$billing->company->company_name ?? ""}}</span></p>
            <p>Mobile Number: <span>{{$billing->company->phone ?? ""}}</span></p>
            <p>Invoice Amount: <span>{{$amount ?? ""}} Tk</span></p>
          </div>
        </div>
         <form action="{{route('bkash-invoice-payment',$billing->id)}}" method="get">
          <div class="payment-form card">
            <div class="card-header">{{$billing->getCustomer->name ?? ""}} Choose payment option</div>
            <div class="card-body">

              <ul>
             @if ($billing->status != "paid")
              <li>
                      <label class="payment-option">
                          <input type="hidden" value="INVBU2309300500368399" name="invoice" id="invoice">
                          <input type="radio" name="payment-option" value="bKash" checked />
                          <div class="payment-option-box">
                              <img src="{{asset('admin_assets/ci/images/logo-bkash.svg')}}" alt="bkash" />
                              <div class="checked-icon">
                                  <img src="{{asset('admin_assets/ci/images/checked-icon.svg')}}" alt="airtel" />
                              </div>
                          </div>
                      </label>
                  </li>
              </ul>

              <div class="pay-now-box">
                  <button type="submit" class="btn">Pay Now</button>
                </div>
            @else
            <h3>You Already Paid this invoice</h3>
            @endif
            </div>
          </div>
        </form>
              </div>
    </div>
  </body>

<!-- Mirrored from cashier.dotlines.com.bd/ci/?i=INVBU2309300500368399 by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 Nov 2023 11:23:11 GMT -->
</html>
