@extends('admin.master')

@section('title')
    Dashboard
@endsection

@section('style')
    <link href="{{ asset('admin_assets/vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/nouislider/nouislider.min.css') }}">
    <style>
        .card_color {
            background-color: #10245A;
            /* border-radius: 30px; */
            color: #fff;
        }

        .gr_1_color {
            background: linear-gradient(150deg, #f731db, #4600f1 100%);
            color: #fff;
        }

        .gr_2_color {
            background: linear-gradient(150deg, #39ef74, #4600f1 100%);
            color: #fff;
        }

        .gr_3_color {
            background: linear-gradient(150deg, #ff6b00f0, #0015f9f5 100%);
            color: #fff;
        }

        .gr_4_color {
            background: linear-gradient(150deg, #8f0d8b, #5821de 100%);
            color: #fff;
        }

        .h3_title {
            color: #fff;
        }
    </style>
@endsection
@section('content')
    <!-- Stats Horizontal Card -->
    @if (auth()->user()->mac_reseler)
        <div class="d-flex justify-content-center">
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card card_color">
                    <div class="card-header ">
                        <div>
                            <p class="card-text">Available Balance:&nbsp;
                                {{ auth()->user()->mac_reseler->recharge_balance ?? '0' }}</p>
                            <h3 class="font-weight-bolder mb-0 h3_title">
                            </h3>
                            <p class="btn btn-sm btn-danger">Expire Date: &nbsp;<span
                                    class="font-weight-bolder">{{ auth()->user()->mac_reseler->expire_date }}</span>
                            </p>
                        </div>
                        <div class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="cpu" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">

        <div class="col-md-6 col-12 revenue-report-wrapper">
            <div class="card p-1">
                <div class="d-sm-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-50 mb-sm-0">Revenue Report</h4>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center mr-2">
                            <span class="bullet bullet-primary font-small-3 mr-50 cursor-pointer"></span>
                            <span>Earning</span>
                        </div>
                        <div class="d-flex align-items-center ml-75">
                            <span class="bullet bullet-warning font-small-3 mr-50 cursor-pointer"></span>
                            <span>Expense</span>
                        </div>
                    </div>
                </div>
                <div id="revenue-report-chart" style="min-height: 245px;">
                    <div id="apexchartsk5scl973" class="apexcharts-canvas apexchartsk5scl973 apexcharts-theme-light"
                        style="width: 473px; height: 230px;"><svg id="SvgjsSvg3075" width="473" height="230"
                            xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS"
                            transform="translate(0, 0)" style="background: transparent;">
                            <g id="SvgjsG3077" class="apexcharts-inner apexcharts-graphical"
                                transform="translate(54.78125, 10)">
                                <defs id="SvgjsDefs3076">
                                    <linearGradient id="SvgjsLinearGradient3081" x1="0" y1="0"
                                        x2="0" y2="1">
                                        <stop id="SvgjsStop3082" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)"
                                            offset="0">
                                        </stop>
                                        <stop id="SvgjsStop3083" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                            offset="1">
                                        </stop>
                                        <stop id="SvgjsStop3084" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                            offset="1">
                                        </stop>
                                    </linearGradient>
                                    <clipPath id="gridRectMaskk5scl973">
                                        <rect id="SvgjsRect3086" width="412.21875" height="190.73" x="-2" y="0"
                                            rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                            stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                    <clipPath id="gridRectMarkerMaskk5scl973">
                                        <rect id="SvgjsRect3087" width="412.21875" height="194.73" x="-2" y="-2"
                                            rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                            stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                </defs>
                                <rect id="SvgjsRect3085" width="7.710798611111111" height="190.73" x="0" y="0"
                                    rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3"
                                    fill="url(#SvgjsLinearGradient3081)" class="apexcharts-xcrosshairs" y2="190.73"
                                    filter="none" fill-opacity="0.9">
                                </rect>
                                <g id="SvgjsG3111" class="apexcharts-xaxis" transform="translate(0, 0)">
                                    <g id="SvgjsG3112" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)">
                                        <text id="SvgjsText3114" font-family="Helvetica, Arial, sans-serif"
                                            x="22.678819444444443" y="219.73" text-anchor="middle"
                                            dominant-baseline="auto" font-size="0.86rem" font-weight="400"
                                            fill="#b9b9c3" class="apexcharts-text apexcharts-xaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan id="SvgjsTspan3115">Jan</tspan>
                                            <title>Jan</title>
                                        </text><text id="SvgjsText3117" font-family="Helvetica, Arial, sans-serif"
                                            x="68.03645833333333" y="219.73" text-anchor="middle" dominant-baseline="auto"
                                            font-size="0.86rem" font-weight="400" fill="#b9b9c3"
                                            class="apexcharts-text apexcharts-xaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan id="SvgjsTspan3118">Feb</tspan>
                                            <title>Feb</title>
                                        </text><text id="SvgjsText3120" font-family="Helvetica, Arial, sans-serif"
                                            x="113.39409722222221" y="219.73" text-anchor="middle"
                                            dominant-baseline="auto" font-size="0.86rem" font-weight="400"
                                            fill="#b9b9c3" class="apexcharts-text apexcharts-xaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan id="SvgjsTspan3121">Mar</tspan>
                                            <title>Mar</title>
                                        </text><text id="SvgjsText3123" font-family="Helvetica, Arial, sans-serif"
                                            x="158.7517361111111" y="219.73" text-anchor="middle" dominant-baseline="auto"
                                            font-size="0.86rem" font-weight="400" fill="#b9b9c3"
                                            class="apexcharts-text apexcharts-xaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan id="SvgjsTspan3124">Apr</tspan>
                                            <title>Apr</title>
                                        </text><text id="SvgjsText3126" font-family="Helvetica, Arial, sans-serif"
                                            x="204.109375" y="219.73" text-anchor="middle" dominant-baseline="auto"
                                            font-size="0.86rem" font-weight="400" fill="#b9b9c3"
                                            class="apexcharts-text apexcharts-xaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan id="SvgjsTspan3127">May</tspan>
                                            <title>May</title>
                                        </text><text id="SvgjsText3129" font-family="Helvetica, Arial, sans-serif"
                                            x="249.46701388888886" y="219.73" text-anchor="middle"
                                            dominant-baseline="auto" font-size="0.86rem" font-weight="400"
                                            fill="#b9b9c3" class="apexcharts-text apexcharts-xaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan id="SvgjsTspan3130">Jun</tspan>
                                            <title>Jun</title>
                                        </text><text id="SvgjsText3132" font-family="Helvetica, Arial, sans-serif"
                                            x="294.8246527777777" y="219.73" text-anchor="middle" dominant-baseline="auto"
                                            font-size="0.86rem" font-weight="400" fill="#b9b9c3"
                                            class="apexcharts-text apexcharts-xaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan id="SvgjsTspan3133">Jul</tspan>
                                            <title>Jul</title>
                                        </text><text id="SvgjsText3135" font-family="Helvetica, Arial, sans-serif"
                                            x="340.18229166666663" y="219.73" text-anchor="middle"
                                            dominant-baseline="auto" font-size="0.86rem" font-weight="400"
                                            fill="#b9b9c3" class="apexcharts-text apexcharts-xaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan id="SvgjsTspan3136">Aug</tspan>
                                            <title>Aug</title>
                                        </text><text id="SvgjsText3138" font-family="Helvetica, Arial, sans-serif"
                                            x="385.53993055555554" y="219.73" text-anchor="middle"
                                            dominant-baseline="auto" font-size="0.86rem" font-weight="400"
                                            fill="#b9b9c3" class="apexcharts-text apexcharts-xaxis-label "
                                            style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan id="SvgjsTspan3139">Sep</tspan>
                                            <title>Sep</title>
                                        </text>
                                    </g>
                                </g>
                                <g id="SvgjsG3154" class="apexcharts-grid">
                                    <g id="SvgjsG3155" class="apexcharts-gridlines-horizontal"></g>
                                    <g id="SvgjsG3156" class="apexcharts-gridlines-vertical"></g>
                                    <line id="SvgjsLine3158" x1="0" y1="190.73" x2="408.21875"
                                        y2="190.73" stroke="transparent" stroke-dasharray="0"></line>
                                    <line id="SvgjsLine3157" x1="0" y1="1" x2="0"
                                        y2="190.73" stroke="transparent" stroke-dasharray="0">
                                    </line>
                                </g>
                                <g id="SvgjsG3088" class="apexcharts-bar-series apexcharts-plot-series">
                                    <g id="SvgjsG3089" class="apexcharts-series" seriesName="Earning" rel="1"
                                        data:realIndex="0">
                                        <path id="SvgjsPath3091"
                                            d="M 18.82342013888889 114.438L 18.82342013888889 80.12699965277778Q 22.678819444444443 76.27160034722223 26.53421875 80.12699965277778L 26.53421875 80.12699965277778L 26.53421875 114.438L 26.53421875 114.438z"
                                            fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 18.82342013888889 114.438L 18.82342013888889 80.12699965277778Q 22.678819444444443 76.27160034722223 26.53421875 80.12699965277778L 26.53421875 80.12699965277778L 26.53421875 114.438L 26.53421875 114.438z"
                                            pathFrom="M 18.82342013888889 114.438L 18.82342013888889 114.438L 26.53421875 114.438L 26.53421875 114.438L 26.53421875 114.438L 18.82342013888889 114.438"
                                            cy="78.19930000000001" cx="64.18105902777778" j="0" val="95"
                                            barHeight="36.238699999999994" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3092"
                                            d="M 64.18105902777778 114.438L 64.18105902777778 48.847279652777786Q 68.03645833333333 44.99188034722223 71.8918576388889 48.847279652777786L 71.8918576388889 48.847279652777786L 71.8918576388889 114.438L 71.8918576388889 114.438z"
                                            fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 64.18105902777778 114.438L 64.18105902777778 48.847279652777786Q 68.03645833333333 44.99188034722223 71.8918576388889 48.847279652777786L 71.8918576388889 48.847279652777786L 71.8918576388889 114.438L 71.8918576388889 114.438z"
                                            pathFrom="M 64.18105902777778 114.438L 64.18105902777778 114.438L 71.8918576388889 114.438L 71.8918576388889 114.438L 71.8918576388889 114.438L 64.18105902777778 114.438"
                                            cy="46.91958000000001" cx="109.53869791666666" j="1" val="177"
                                            barHeight="67.51841999999999" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3093"
                                            d="M 109.53869791666666 114.438L 109.53869791666666 8.031059652777786Q 113.39409722222221 4.175660347222231 117.24949652777778 8.031059652777786L 117.24949652777778 8.031059652777786L 117.24949652777778 114.438L 117.24949652777778 114.438z"
                                            fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 109.53869791666666 114.438L 109.53869791666666 8.031059652777786Q 113.39409722222221 4.175660347222231 117.24949652777778 8.031059652777786L 117.24949652777778 8.031059652777786L 117.24949652777778 114.438L 117.24949652777778 114.438z"
                                            pathFrom="M 109.53869791666666 114.438L 109.53869791666666 114.438L 117.24949652777778 114.438L 117.24949652777778 114.438L 117.24949652777778 114.438L 109.53869791666666 114.438"
                                            cy="6.103360000000009" cx="154.89633680555556" j="2" val="284"
                                            barHeight="108.33464" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3094"
                                            d="M 154.89633680555556 114.438L 154.89633680555556 18.71193965277779Q 158.75173611111111 14.856540347222236 162.60713541666667 18.71193965277779L 162.60713541666667 18.71193965277779L 162.60713541666667 114.438L 162.60713541666667 114.438z"
                                            fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 154.89633680555556 114.438L 154.89633680555556 18.71193965277779Q 158.75173611111111 14.856540347222236 162.60713541666667 18.71193965277779L 162.60713541666667 18.71193965277779L 162.60713541666667 114.438L 162.60713541666667 114.438z"
                                            pathFrom="M 154.89633680555556 114.438L 154.89633680555556 114.438L 162.60713541666667 114.438L 162.60713541666667 114.438L 162.60713541666667 114.438L 154.89633680555556 114.438"
                                            cy="16.78424000000001" cx="200.25397569444445" j="3" val="256"
                                            barHeight="97.65375999999999" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3095"
                                            d="M 200.25397569444445 114.438L 200.25397569444445 76.31239965277778Q 204.109375 72.45700034722223 207.96477430555555 76.31239965277778L 207.96477430555555 76.31239965277778L 207.96477430555555 114.438L 207.96477430555555 114.438z"
                                            fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 200.25397569444445 114.438L 200.25397569444445 76.31239965277778Q 204.109375 72.45700034722223 207.96477430555555 76.31239965277778L 207.96477430555555 76.31239965277778L 207.96477430555555 114.438L 207.96477430555555 114.438z"
                                            pathFrom="M 200.25397569444445 114.438L 200.25397569444445 114.438L 207.96477430555555 114.438L 207.96477430555555 114.438L 207.96477430555555 114.438L 200.25397569444445 114.438"
                                            cy="74.38470000000001" cx="245.61161458333333" j="4" val="105"
                                            barHeight="40.05329999999999" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3096"
                                            d="M 245.61161458333333 114.438L 245.61161458333333 92.33371965277779Q 249.46701388888889 88.47832034722224 253.32241319444444 92.33371965277779L 253.32241319444444 92.33371965277779L 253.32241319444444 114.438L 253.32241319444444 114.438z"
                                            fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 245.61161458333333 114.438L 245.61161458333333 92.33371965277779Q 249.46701388888889 88.47832034722224 253.32241319444444 92.33371965277779L 253.32241319444444 92.33371965277779L 253.32241319444444 114.438L 253.32241319444444 114.438z"
                                            pathFrom="M 245.61161458333333 114.438L 245.61161458333333 114.438L 253.32241319444444 114.438L 253.32241319444444 114.438L 253.32241319444444 114.438L 245.61161458333333 114.438"
                                            cy="90.40602000000001" cx="290.96925347222225" j="5" val="63"
                                            barHeight="24.031979999999997" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3097"
                                            d="M 290.96925347222225 114.438L 290.96925347222225 52.28041965277778Q 294.8246527777778 48.42502034722222 298.68005208333335 52.28041965277778L 298.68005208333335 52.28041965277778L 298.68005208333335 114.438L 298.68005208333335 114.438z"
                                            fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 290.96925347222225 114.438L 290.96925347222225 52.28041965277778Q 294.8246527777778 48.42502034722222 298.68005208333335 52.28041965277778L 298.68005208333335 52.28041965277778L 298.68005208333335 114.438L 298.68005208333335 114.438z"
                                            pathFrom="M 290.96925347222225 114.438L 290.96925347222225 114.438L 298.68005208333335 114.438L 298.68005208333335 114.438L 298.68005208333335 114.438L 290.96925347222225 114.438"
                                            cy="50.352720000000005" cx="336.32689236111116" j="6" val="168"
                                            barHeight="64.08528" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3098"
                                            d="M 336.32689236111116 114.438L 336.32689236111116 33.20741965277779Q 340.18229166666674 29.352020347222233 344.03769097222226 33.20741965277779L 344.03769097222226 33.20741965277779L 344.03769097222226 114.438L 344.03769097222226 114.438z"
                                            fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 336.32689236111116 114.438L 336.32689236111116 33.20741965277779Q 340.18229166666674 29.352020347222233 344.03769097222226 33.20741965277779L 344.03769097222226 33.20741965277779L 344.03769097222226 114.438L 344.03769097222226 114.438z"
                                            pathFrom="M 336.32689236111116 114.438L 336.32689236111116 114.438L 344.03769097222226 114.438L 344.03769097222226 114.438L 344.03769097222226 114.438L 336.32689236111116 114.438"
                                            cy="31.27972000000001" cx="381.6845312500001" j="7" val="218"
                                            barHeight="83.15827999999999" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3099"
                                            d="M 381.6845312500001 114.438L 381.6845312500001 88.90057965277778Q 385.53993055555566 85.04518034722223 389.3953298611112 88.90057965277778L 389.3953298611112 88.90057965277778L 389.3953298611112 114.438L 389.3953298611112 114.438z"
                                            fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 381.6845312500001 114.438L 381.6845312500001 88.90057965277778Q 385.53993055555566 85.04518034722223 389.3953298611112 88.90057965277778L 389.3953298611112 88.90057965277778L 389.3953298611112 114.438L 389.3953298611112 114.438z"
                                            pathFrom="M 381.6845312500001 114.438L 381.6845312500001 114.438L 389.3953298611112 114.438L 389.3953298611112 114.438L 389.3953298611112 114.438L 381.6845312500001 114.438"
                                            cy="86.97288" cx="427.042170138889" j="8" val="72"
                                            barHeight="27.46512" barWidth="7.710798611111111"></path>
                                    </g>
                                    <g id="SvgjsG3100" class="apexcharts-series" seriesName="Expense" rel="2"
                                        data:realIndex="1">
                                        <path id="SvgjsPath3102"
                                            d="M 18.82342013888889 114.438L 18.82342013888889 167.8220003472222Q 22.678819444444443 171.67739965277775 26.53421875 167.8220003472222L 26.53421875 167.8220003472222L 26.53421875 114.438L 26.53421875 114.438z"
                                            fill="rgba(255,159,67,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="1"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 18.82342013888889 114.438L 18.82342013888889 167.8220003472222Q 22.678819444444443 171.67739965277775 26.53421875 167.8220003472222L 26.53421875 167.8220003472222L 26.53421875 114.438L 26.53421875 114.438z"
                                            pathFrom="M 18.82342013888889 114.438L 18.82342013888889 114.438L 26.53421875 114.438L 26.53421875 114.438L 26.53421875 114.438L 18.82342013888889 114.438"
                                            cy="169.7497" cx="64.18105902777778" j="0" val="-145"
                                            barHeight="-55.311699999999995" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3103"
                                            d="M 64.18105902777778 114.438L 64.18105902777778 143.02710034722222Q 68.03645833333333 146.88249965277777 71.8918576388889 143.02710034722222L 71.8918576388889 143.02710034722222L 71.8918576388889 114.438L 71.8918576388889 114.438z"
                                            fill="rgba(255,159,67,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="1"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 64.18105902777778 114.438L 64.18105902777778 143.02710034722222Q 68.03645833333333 146.88249965277777 71.8918576388889 143.02710034722222L 71.8918576388889 143.02710034722222L 71.8918576388889 114.438L 71.8918576388889 114.438z"
                                            pathFrom="M 64.18105902777778 114.438L 64.18105902777778 114.438L 71.8918576388889 114.438L 71.8918576388889 114.438L 71.8918576388889 114.438L 64.18105902777778 114.438"
                                            cy="144.9548" cx="109.53869791666666" j="1" val="-80"
                                            barHeight="-30.516799999999996" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3104"
                                            d="M 109.53869791666666 114.438L 109.53869791666666 135.39790034722222Q 113.39409722222221 139.25329965277777 117.24949652777778 135.39790034722222L 117.24949652777778 135.39790034722222L 117.24949652777778 114.438L 117.24949652777778 114.438z"
                                            fill="rgba(255,159,67,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="1"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 109.53869791666666 114.438L 109.53869791666666 135.39790034722222Q 113.39409722222221 139.25329965277777 117.24949652777778 135.39790034722222L 117.24949652777778 135.39790034722222L 117.24949652777778 114.438L 117.24949652777778 114.438z"
                                            pathFrom="M 109.53869791666666 114.438L 109.53869791666666 114.438L 117.24949652777778 114.438L 117.24949652777778 114.438L 117.24949652777778 114.438L 109.53869791666666 114.438"
                                            cy="137.3256" cx="154.89633680555556" j="2" val="-60"
                                            barHeight="-22.8876" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3105"
                                            d="M 154.89633680555556 114.438L 154.89633680555556 181.1731003472222Q 158.75173611111111 185.02849965277775 162.60713541666667 181.1731003472222L 162.60713541666667 181.1731003472222L 162.60713541666667 114.438L 162.60713541666667 114.438z"
                                            fill="rgba(255,159,67,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="1"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 154.89633680555556 114.438L 154.89633680555556 181.1731003472222Q 158.75173611111111 185.02849965277775 162.60713541666667 181.1731003472222L 162.60713541666667 181.1731003472222L 162.60713541666667 114.438L 162.60713541666667 114.438z"
                                            pathFrom="M 154.89633680555556 114.438L 154.89633680555556 114.438L 162.60713541666667 114.438L 162.60713541666667 114.438L 162.60713541666667 114.438L 154.89633680555556 114.438"
                                            cy="183.1008" cx="200.25397569444445" j="3" val="-180"
                                            barHeight="-68.66279999999999" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3106"
                                            d="M 200.25397569444445 114.438L 200.25397569444445 150.6563003472222Q 204.109375 154.51169965277776 207.96477430555555 150.6563003472222L 207.96477430555555 150.6563003472222L 207.96477430555555 114.438L 207.96477430555555 114.438z"
                                            fill="rgba(255,159,67,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="1"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 200.25397569444445 114.438L 200.25397569444445 150.6563003472222Q 204.109375 154.51169965277776 207.96477430555555 150.6563003472222L 207.96477430555555 150.6563003472222L 207.96477430555555 114.438L 207.96477430555555 114.438z"
                                            pathFrom="M 200.25397569444445 114.438L 200.25397569444445 114.438L 207.96477430555555 114.438L 207.96477430555555 114.438L 207.96477430555555 114.438L 200.25397569444445 114.438"
                                            cy="152.584" cx="245.61161458333333" j="4" val="-100"
                                            barHeight="-38.145999999999994" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3107"
                                            d="M 245.61161458333333 114.438L 245.61161458333333 135.39790034722222Q 249.46701388888889 139.25329965277777 253.32241319444444 135.39790034722222L 253.32241319444444 135.39790034722222L 253.32241319444444 114.438L 253.32241319444444 114.438z"
                                            fill="rgba(255,159,67,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="1"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 245.61161458333333 114.438L 245.61161458333333 135.39790034722222Q 249.46701388888889 139.25329965277777 253.32241319444444 135.39790034722222L 253.32241319444444 135.39790034722222L 253.32241319444444 114.438L 253.32241319444444 114.438z"
                                            pathFrom="M 245.61161458333333 114.438L 245.61161458333333 114.438L 253.32241319444444 114.438L 253.32241319444444 114.438L 253.32241319444444 114.438L 245.61161458333333 114.438"
                                            cy="137.3256" cx="290.96925347222225" j="5" val="-60"
                                            barHeight="-22.8876" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3108"
                                            d="M 290.96925347222225 114.438L 290.96925347222225 144.9344003472222Q 294.8246527777778 148.78979965277776 298.68005208333335 144.9344003472222L 298.68005208333335 144.9344003472222L 298.68005208333335 114.438L 298.68005208333335 114.438z"
                                            fill="rgba(255,159,67,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="1"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 290.96925347222225 114.438L 290.96925347222225 144.9344003472222Q 294.8246527777778 148.78979965277776 298.68005208333335 144.9344003472222L 298.68005208333335 144.9344003472222L 298.68005208333335 114.438L 298.68005208333335 114.438z"
                                            pathFrom="M 290.96925347222225 114.438L 290.96925347222225 114.438L 298.68005208333335 114.438L 298.68005208333335 114.438L 298.68005208333335 114.438L 290.96925347222225 114.438"
                                            cy="146.8621" cx="336.32689236111116" j="6" val="-85"
                                            barHeight="-32.424099999999996" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3109"
                                            d="M 336.32689236111116 114.438L 336.32689236111116 141.11980034722222Q 340.18229166666674 144.97519965277777 344.03769097222226 141.11980034722222L 344.03769097222226 141.11980034722222L 344.03769097222226 114.438L 344.03769097222226 114.438z"
                                            fill="rgba(255,159,67,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="1"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 336.32689236111116 114.438L 336.32689236111116 141.11980034722222Q 340.18229166666674 144.97519965277777 344.03769097222226 141.11980034722222L 344.03769097222226 141.11980034722222L 344.03769097222226 114.438L 344.03769097222226 114.438z"
                                            pathFrom="M 336.32689236111116 114.438L 336.32689236111116 114.438L 344.03769097222226 114.438L 344.03769097222226 114.438L 344.03769097222226 114.438L 336.32689236111116 114.438"
                                            cy="143.0475" cx="381.6845312500001" j="7" val="-75"
                                            barHeight="-28.609499999999997" barWidth="7.710798611111111"></path>
                                        <path id="SvgjsPath3110"
                                            d="M 381.6845312500001 114.438L 381.6845312500001 150.6563003472222Q 385.53993055555566 154.51169965277776 389.3953298611112 150.6563003472222L 389.3953298611112 150.6563003472222L 389.3953298611112 114.438L 389.3953298611112 114.438z"
                                            fill="rgba(255,159,67,0.85)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="square" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="1"
                                            clip-path="url(#gridRectMaskk5scl973)"
                                            pathTo="M 381.6845312500001 114.438L 381.6845312500001 150.6563003472222Q 385.53993055555566 154.51169965277776 389.3953298611112 150.6563003472222L 389.3953298611112 150.6563003472222L 389.3953298611112 114.438L 389.3953298611112 114.438z"
                                            pathFrom="M 381.6845312500001 114.438L 381.6845312500001 114.438L 389.3953298611112 114.438L 389.3953298611112 114.438L 389.3953298611112 114.438L 381.6845312500001 114.438"
                                            cy="152.584" cx="427.042170138889" j="8" val="-100"
                                            barHeight="-38.145999999999994" barWidth="7.710798611111111"></path>
                                    </g>
                                    <g id="SvgjsG3090" class="apexcharts-datalabels" data:realIndex="0"></g>
                                    <g id="SvgjsG3101" class="apexcharts-datalabels" data:realIndex="1"></g>
                                </g>
                                <line id="SvgjsLine3159" x1="0" y1="0" x2="408.21875" y2="0"
                                    stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                    class="apexcharts-ycrosshairs"></line>
                                <line id="SvgjsLine3160" x1="0" y1="0" x2="408.21875" y2="0"
                                    stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line>
                                <g id="SvgjsG3161" class="apexcharts-yaxis-annotations"></g>
                                <g id="SvgjsG3162" class="apexcharts-xaxis-annotations"></g>
                                <g id="SvgjsG3163" class="apexcharts-point-annotations"></g>
                            </g>
                            <g id="SvgjsG3140" class="apexcharts-yaxis" rel="0"
                                transform="translate(24.78125, 0)">
                                <g id="SvgjsG3141" class="apexcharts-yaxis-texts-g"><text id="SvgjsText3142"
                                        font-family="Helvetica, Arial, sans-serif" x="20" y="11.5" text-anchor="end"
                                        dominant-baseline="auto" font-size="0.86rem" font-weight="400" fill="#b9b9c3"
                                        class="apexcharts-text apexcharts-yaxis-label "
                                        style="font-family: Helvetica, Arial, sans-serif;">
                                        <tspan id="SvgjsTspan3143">300</tspan>
                                    </text><text id="SvgjsText3144" font-family="Helvetica, Arial, sans-serif" x="20"
                                        y="49.646" text-anchor="end" dominant-baseline="auto" font-size="0.86rem"
                                        font-weight="400" fill="#b9b9c3" class="apexcharts-text apexcharts-yaxis-label "
                                        style="font-family: Helvetica, Arial, sans-serif;">
                                        <tspan id="SvgjsTspan3145">200</tspan>
                                    </text><text id="SvgjsText3146" font-family="Helvetica, Arial, sans-serif" x="20"
                                        y="87.792" text-anchor="end" dominant-baseline="auto" font-size="0.86rem"
                                        font-weight="400" fill="#b9b9c3" class="apexcharts-text apexcharts-yaxis-label "
                                        style="font-family: Helvetica, Arial, sans-serif;">
                                        <tspan id="SvgjsTspan3147">100</tspan>
                                    </text><text id="SvgjsText3148" font-family="Helvetica, Arial, sans-serif" x="20"
                                        y="125.938" text-anchor="end" dominant-baseline="auto" font-size="0.86rem"
                                        font-weight="400" fill="#b9b9c3" class="apexcharts-text apexcharts-yaxis-label "
                                        style="font-family: Helvetica, Arial, sans-serif;">
                                        <tspan id="SvgjsTspan3149">0</tspan>
                                    </text><text id="SvgjsText3150" font-family="Helvetica, Arial, sans-serif" x="20"
                                        y="164.084" text-anchor="end" dominant-baseline="auto" font-size="0.86rem"
                                        font-weight="400" fill="#b9b9c3" class="apexcharts-text apexcharts-yaxis-label "
                                        style="font-family: Helvetica, Arial, sans-serif;">
                                        <tspan id="SvgjsTspan3151">-100</tspan>
                                    </text><text id="SvgjsText3152" font-family="Helvetica, Arial, sans-serif" x="20"
                                        y="202.23000000000002" text-anchor="end" dominant-baseline="auto"
                                        font-size="0.86rem" font-weight="400" fill="#b9b9c3"
                                        class="apexcharts-text apexcharts-yaxis-label "
                                        style="font-family: Helvetica, Arial, sans-serif;">
                                        <tspan id="SvgjsTspan3153">-200</tspan>
                                    </text></g>
                            </g>
                            <g id="SvgjsG3078" class="apexcharts-annotations"></g>
                        </svg>
                        <div class="apexcharts-legend" style="max-height: 115px;"></div>
                        <div class="apexcharts-tooltip apexcharts-theme-light">
                            <div class="apexcharts-tooltip-title"
                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                            </div>
                            <div class="apexcharts-tooltip-series-group" style="order: 1;"><span
                                    class="apexcharts-tooltip-marker"
                                    style="background-color: rgb(115, 103, 240);"></span>
                                <div class="apexcharts-tooltip-text"
                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                    <div class="apexcharts-tooltip-y-group"><span
                                            class="apexcharts-tooltip-text-label"></span><span
                                            class="apexcharts-tooltip-text-value"></span></div>
                                    <div class="apexcharts-tooltip-z-group"><span
                                            class="apexcharts-tooltip-text-z-label"></span><span
                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                </div>
                            </div>
                            <div class="apexcharts-tooltip-series-group" style="order: 2;"><span
                                    class="apexcharts-tooltip-marker" style="background-color: rgb(255, 159, 67);"></span>
                                <div class="apexcharts-tooltip-text"
                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                    <div class="apexcharts-tooltip-y-group"><span
                                            class="apexcharts-tooltip-text-label"></span><span
                                            class="apexcharts-tooltip-text-value"></span></div>
                                    <div class="apexcharts-tooltip-z-group"><span
                                            class="apexcharts-tooltip-text-z-label"></span><span
                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                            <div class="apexcharts-yaxistooltip-text"></div>
                        </div>
                    </div>
                </div>
                <div class="resize-triggers">
                    <div class="expand-trigger">
                        <div style="width: 516px; height: 346px;"></div>
                    </div>
                    <div class="contract-trigger"></div>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="card">
                <img src="{{ asset('image/mrt.jpg') }}" alt="" width="100%" height="335px">
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <a href="" type="btn" class="btn btn-info mb-1">Client List</a>
        </div>
        @if (Auth::user()->is_admin == 1 ||
                Auth::user()->is_admin == 3 ||
                Auth::user()->is_admin == 4 ||
                Auth::user()->is_admin == 5)
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_1_color">
                    <div class="card-header ">
                        <div>
                            <p class="card-text">All Clients</p>
                            <h3 class="font-weight-bolder mb-0 h3_title">{{ $customers }}</h3>
                        </div>

                        <div class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="cpu" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_2_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('customerInfo') }}">
                                <p class="card-text text-white">All Active / Billing Clients</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $active_customers }}</h3>
                            </a>
                        </div>
                        <div class="avatar bg-light-success p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="server" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_3_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('customerInactive') }}">
                                <p class="card-text text-white">All Inactive Clients</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $inactive_customers }}</h3>
                            </a>
                        </div>
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="activity" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_3_color">
                    <div class="card-header">
                        <div>
                            <p class="card-text text-white">All Free Clients</p>
                            <h3 class="font-weight-bolder mb-0 h3_title">{{ $free_customers }}</h3>
                        </div>
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="activity" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_4_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('dashboard.bill', 1) }}">
                                <p class="card-text text-white">{{ date('F') }} Total Bill</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $this_monthly_bill }}</h3>
                            </a>
                        </div>
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="dollar-sign" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_4_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('dashboardcollectedbill') }}">
                                <p class="card-text text-white">{{ date('F') }} Collected Bill</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $this_monthly_collected_bill }}</h3>
                            </a>
                        </div>
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="dollar-sign" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_3_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('dashboard.bill', 3) }}">
                                <p class="card-text text-white">{{ date('F') }} Due Bill</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $this_monthly_due_bill }}</h3>
                            </a>

                        </div>
                        <div class="avatar bg-light p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="dollar-sign" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_3_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('dashboard.discount', 3) }}">
                                <p class="card-text text-white">{{ date('F') }} Discount Bill</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $this_monthly_discount_bill }}</h3>
                            </a>

                        </div>
                        <div class="avatar bg-light p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="dollar-sign" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_2_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('today_line_off.index') }}">
                                <p class="card-text text-white">Today Line off</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $today_line_off }}</h3>
                            </a>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_2_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('tomorrow_line_off.index') }}">
                                <p class="card-text text-white">Tomorrow Line off</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $tomorrow_line_off }}</h3>
                            </a>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_1_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('newcustomer') }}">
                                <p class="card-text text-white">New Clients</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $new_customers }}</h3>
                            </a>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_1_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('todays_billings') }}">
                                <p class="card-text text-white">Today's Collected Bill</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $todays_billings }}</h3>
                            </a>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_2_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('totalCollectedBill') }}">
                                <p class="card-text text-white">Total Collected Bill</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $total_billings }}</h3>
                            </a>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_3_color">
                    <div class="card-header">
                        <div>
                            <a href="{{ route('totaldueBill') }}">
                                <p class="card-text text-white">Total Due</p>
                                <h3 class="font-weight-bolder mb-0 h3_title">{{ $total_due }}</h3>
                            </a>

                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_4_color">
                    <div class="card-header">
                        <div>
                            <p class="card-text text-white">Billing Clients</p>
                            <h3 class="font-weight-bolder mb-0 h3_title">{{ $customers }}</h3>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        @endif
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_4_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">Paid Clients</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">{{ $paid_customers }}</h3>
                    </div>

                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_3_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">Partially Clients</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">{{ $partial_customers }}</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_2_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">Unpaid Clients</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">{{ $total_unpaids }}</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (Auth::user()->is_admin == 1)
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_1_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">All Suppliers</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">{{ $suppliers }}</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_1_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">All Products</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">{{ $products }}</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_2_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">All Mac Clients</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">{{ $macsallers }}</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_3_color">
                    <div class="card-header">
                        <div>
                            <p class="card-text text-white">All Bandwidth Clients</p>
                            <h3 class="font-weight-bolder mb-0 h3_title">{{ $bandwith_clients }}</h3>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                                                                                                                                                                                                                            <div class="card card_color">
                                                                                                                                                                                                                                <div class="card-header">
                                                                                                                                                                                                                                    <div>
                                                                                                                                                                                                                                        <h3 class="font-weight-bolder mb-0 h3_title">2</h3>
                                                                                                                                                                                                                                        <p class="card-text">VIP Clients</p>
                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                    <div class="avatar bg-light-warning p-50 m-0">
                                                                                                                                                                                                                                        <div class="avatar-content">
                                                                                                                                                                                                                                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                        </div> -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_4_color">
                    <div class="card-header">
                        <div>
                            <p class="card-text text-white">Left Clients</p>
                            <h3 class="font-weight-bolder mb-0 h3_title">{{ $left_customers }}</h3>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_4_color">
                    <div class="card-header">
                        <div>
                            <p class="card-text text-white">Active Mac Clients</p>
                            <h3 class="font-weight-bolder mb-0 h3_title">{{ $mac_client }}</h3>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_3_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">Disabled Mac Clients</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">2</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_2_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">Discount</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">0</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_1_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">Monthly Bill</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">0</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_1_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">Installation Charge</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">0</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_2_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">Expense</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">0</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_3_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">Paid Salary</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">0</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_4_color">
                <div class="card-header">
                    <div>
                        <p class="card-text text-white">SMS Balance</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">0</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
            {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card gr_4_color">
                <div class="card-header">
                    <div>
                        <p class="card-text">Mac Reseller Fund</p>
                        <h3 class="font-weight-bolder mb-0 h3_title">0</h3>
                    </div>
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="alert-octagon" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_3_color">
                    <div class="card-header">
                        <div>
                            <p class="card-text">Bandwidth Reseller Bill</p>
                            <h3 class="font-weight-bolder mb-0 h3_title">0</h3>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card gr_2_color">
                    <div class="card-header">
                        <div>
                            <p class="card-text">Bandwidth Upstream Bill</p>
                            <h3 class="font-weight-bolder mb-0 h3_title">0</h3>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>
    @endif
    {{-- <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Today Bill Collection</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped verticle-middle table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">Sl</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Customer Phone</th>
                                <th scope="col">Bill Collect</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($billings as $key => $billing)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$billing->getCustomer->name ?? "N/A"}}</td>
                                <td>{{$billing->getCustomer->phone ?? "N/A"}}</td>
                                <td>{{$billing->getBillinfBy->Name ?? "N/A"}}</td>
                                <td>{{$billing->customer_billing_amount ?? "N/A"}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}
    <!--/ Stats Horizontal Card -->
    {{-- <div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">All Clients</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$customers}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Active Clients</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$active_customers}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Inactive Clients</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$inactive_customers}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">New Clients</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$new_customers}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Todays Bill</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$todays_billings}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Total Bill</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$total_billings}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Total Due</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$total_due}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Billing Clients</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$customers}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Paid Clients</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$paid_customers}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Partially Clients</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$partial_customers}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Unpaid Clients</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$total_unpaids}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>


                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Total Supplier</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$suppliers}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Total Product</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$products}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Total Mac Resaller</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$macsallers}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <a href="#">
                                    <div class="card">
                                        <div class="card-body d-flex p-2 justify-content-between card_color">
                                            <div>
                                                <h4 class="fs-18 font-w600 text-nowrap">Total Bandwidth Client</h4>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fs-32 font-w700 mb-0">{{$bandwith_clients}}</h3>
                                                    <span class="d-block ms-4">
                                                        <svg width="21" height="11" viewbox="0 0 21 11" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z"
                                                                fill="#09BD3C"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Today Bill Collection</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-bordered table-striped verticle-middle table-responsive-sm">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Sl</th>
                                                        <th scope="col">Customer Name</th>
                                                        <th scope="col">Customer Phone</th>
                                                        <th scope="col">Bill Collect</th>
                                                        <th scope="col">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($billings as $key => $billing)
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{$billing->getCustomer->name ?? "N/A"}}</td>
                                                        <td>{{$billing->getCustomer->phone ?? "N/A"}}</td>
                                                        <td>{{$billing->getBillinfBy->Name ?? "N/A"}}</td>
                                                        <td>{{$billing->customer_billing_amount ?? "N/A"}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div> --}}
@endsection

@section('chartsctipts')
    <script src="{{ asset('admin_assets/vendor/chart.js/Chart.bundle.min.js') }}"></script>

    <!-- Apex Chart -->
    <script src="{{ asset('admin_assets/vendor/apexchart/apexchart.js') }}"></script>

    <script src="{{ asset('admin_assets/vendor/chart.js/Chart.bundle.min.js') }}"></script>

    <!-- Chart piety plugin files -->
    <script src="{{ asset('admin_assets/vendor/peity/jquery.peity.min.js') }}"></script>
    <!-- Dashboard 1 -->
    <script src="{{ asset('admin_assets/js/dashboard/dashboard-1.js') }}"></script>

    <script src="{{ asset('admin_assets/vendor/owl-carousel/owl.carousel.js') }}"></script>
@endsection
