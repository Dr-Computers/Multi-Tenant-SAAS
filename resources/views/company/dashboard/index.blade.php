@extends('layouts.company')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@push('theme-script')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
@endpush

@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li> --}}
@endsection
<style type="text/css">
    .apexcharts-legend {
        display: flex;
        overflow: auto;
        padding: 0 10px;
    }

    .apexcharts-legend.apx-legend-position-bottom,
    .apexcharts-legend.apx-legend-position-top {
        flex-wrap: wrap
    }

    .apexcharts-legend.apx-legend-position-right,
    .apexcharts-legend.apx-legend-position-left {
        flex-direction: column;
        bottom: 0;
    }

    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
    .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
    .apexcharts-legend.apx-legend-position-right,
    .apexcharts-legend.apx-legend-position-left {
        justify-content: flex-start;
    }

    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
    .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
        justify-content: center;
    }

    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
    .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
        justify-content: flex-end;
    }

    .apexcharts-legend-series {
        cursor: pointer;
        line-height: normal;
    }

    .apexcharts-legend.apx-legend-position-bottom .apexcharts-legend-series,
    .apexcharts-legend.apx-legend-position-top .apexcharts-legend-series {
        display: flex;
        align-items: center;
    }

    .apexcharts-legend-text {
        position: relative;
        font-size: 14px;
    }

    .apexcharts-legend-text *,
    .apexcharts-legend-marker * {
        pointer-events: none;
    }

    .apexcharts-legend-marker {
        position: relative;
        display: inline-block;
        cursor: pointer;
        margin-right: 3px;
        border-style: solid;
    }

    .apexcharts-legend.apexcharts-align-right .apexcharts-legend-series,
    .apexcharts-legend.apexcharts-align-left .apexcharts-legend-series {
        display: inline-block;
    }

    .apexcharts-legend-series.apexcharts-no-click {
        cursor: auto;
    }

    .apexcharts-legend .apexcharts-hidden-zero-series,
    .apexcharts-legend .apexcharts-hidden-null-series {
        display: none !important;
    }

    .apexcharts-inactive-legend {
        opacity: 0.45;
    }
</style>
<style>
    .dashboard-card {
        position: relative;
        height: 100%;
        margin-bottom: 0;
        background-color: #032636;
        border-radius: 10px;
        z-index: 1;
    }

    .dashboard-card-layer {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .dashboard-card .card-inner {
        position: relative;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        border-radius: 10px;
        height: 100%;
        color: #fff;
        gap: 20px;
    }

    .dashboard-card .card-inner .card-content {
        max-width: 70%;
        width: 100%;
    }

    .dashboard-card .card-inner .card-content h2 {
        color: #ffffff;
        text-transform: capitalize;
    }

    .dashboard-card .card-inner .card-content p {
        font-size: 14px;
        max-width: 80%;
        width: 100%;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .dashboard-card .card-inner .card-content .btn {
        padding: 5px 10px;
        border-radius: 4px;
        border: none;
        transition: all ease-in-out 500ms 0s;
    }

    @media screen and (max-width: 1440px) {
        .dashboard-card .card-inner .card-icon {
            padding: 20px;
        }
    }

    .dashboard-card .card-inner .card-icon {
        position: relative;
        background: #1C3B4A;
        border-radius: 50%;
        padding: 25px;
        z-index: 1;
    }

    .dashboard-card .card-inner .card-icon::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        height: 80%;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: -1;
    }

    @media screen and (max-width: 1440px) {
        .dashboard-card .card-inner .card-icon svg {
            width: 70px;
            height: 70px;
        }
    }

    .dashboard-card .card-inner::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background-color: rgba(12, 175, 96, 0.4);
        border-radius: 80% 0 10px;
    }

    /* .dashboard-wrp {
        row-gap: 15px;
        height: 100%;
    }

    .dashboard-project-card {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        align-items: center;
        display: flex;
        background-color: rgba(255, 58, 110, 0.1);
        height: 100%;
        width: 100%;
    }

    .dashboard-project-card .card-inner {
        align-items: flex-start;
        padding: 15px;
        width: 100%;
    }

    .dashboard-project-card .card-content {
        max-width: 70%;
        width: 100%;
    }

    .dashboard-wrp .dashboard-project-card .theme-avtar {
        position: relative;
        border-radius: 4px;
    }

    .theme-avtar {
        width: 45px;
        height: 45px;
        border-radius: 17.3552px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .dashboard-wrp .dashboard-project-card .theme-avtar::before {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #ff3a6e;
        opacity: 30%;
        bottom: 0;
        right: -110%;
        z-index: -1;
    }

    .dashboard-wrp .dashboard-project-card .theme-avtar::after {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ff3a6e;
        opacity: 30%;
        top: 15%;
        right: -25px;
        z-index: -1;
    }

    .dashboard-project-card .card-content h3 {
        font-size: 18px;
        text-transform: capitalize;
        word-break: break-word;
    }*/

    .card {
        margin-bottom: 30px;
        border: 0px;
        border-radius: 0.625rem;
        box-shadow: 6px 11px 41px -28px #a99de7;
    }

    .gradient-1 {
        color: #fff !important;
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
    }

    .gradient-1,
    .dropdown-mega-menu .ext-link.link-1 a,
    .morris-hover,
    .datamaps-hoverover {
        background-image: linear-gradient(230deg, #759bff, #843cf6);
    }
    .card .card-body {
    padding: 1.88rem 1.81rem;
}
.card-title {
    font-size: 18px;
    font-weight: 500;
    line-height: 18px;
}
.gradient-2, .dropdown-mega-menu .ext-link.link-3 a {
    background-image: linear-gradient(230deg, #fc5286, #fbaaa2);
}
.gradient-3, .dropdown-mega-menu .ext-link.link-2 a, .header-right .icons .user-img .activity {
    background-image: linear-gradient(230deg, #ffc480, #ff763b);
}
.gradient-4, .sidebar-right .nav-tabs .nav-item .nav-link.active::after, .sidebar-right .nav-tabs .nav-item .nav-link.active span i::before {
    background-image: linear-gradient(230deg, #0e4cfd, #6a8eff);
}
</style>
@section('content')


        <div class="row row-gap mb-4 ">
            <div class="col-xl-12 col-12">
                <div class="dashboard-card">
                    <img src="https://dash-demo.workdo.io/assets/images/layer.png" class="dashboard-card-layer" alt="layer">
                    <div class="card-inner">
                        <div class="card-content">
                            <h2>Plan Expiring Soon</h2>
                            <p class="my-2">
                                Your plan expiring soon please renew subscription
                            </p>
                            <div class="btn-wrp d-flex gap-3">
                                <a href="#" class="btn btn-primary d-flex align-items-center gap-1 cp_link">
                                    <i class="ti ti-link text-white"></i>
                                    <span>Renew Subscription</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-1">
                        <div class="card-body">
                            <h3 class="card-title text-white">Products Sold</h3>
                            <div class="d-inline-block">
                                <h2 class="text-white">4565</h2>
                                <p class="text-white mb-0">Jan - March 2019</p>
                            </div>
                            <span class="float-right display-5 opacity-5"><i class="fa fa-shopping-cart"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-2">
                        <div class="card-body">
                            <h3 class="card-title text-white">Net Profit</h3>
                            <div class="d-inline-block">
                                <h2 class="text-white">$ 8541</h2>
                                <p class="text-white mb-0">Jan - March 2019</p>
                            </div>
                            <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-3">
                        <div class="card-body">
                            <h3 class="card-title text-white">New Customers</h3>
                            <div class="d-inline-block">
                                <h2 class="text-white">4565</h2>
                                <p class="text-white mb-0">Jan - March 2019</p>
                            </div>
                            <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-4">
                        <div class="card-body">
                            <h3 class="card-title text-white"> Satisfaction</h3>
                            <div class="d-inline-block">
                                <h2 class="text-white">99%</h2>
                                <p class="text-white mb-0">Jan - March 2019</p>
                            </div>
                            <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
        {{-- <div class="row row-gap mb-4 ">
            <div class="col-xxl-12 mb-4">
                <div class="card h-100 mb-0">
                    <div class="card-header">
                        <h5>
                            Sports Booking And Subscription Analytics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="incExpBarChart" style="min-height: 365px;">
                            <div id="apexchartsaxvliwug"
                                class="apexcharts-canvas apexchartsaxvliwug apexcharts-theme-light"
                                style="width: 774px; height: 350px;"><svg id="SvgjsSvg1188" width="774"
                                    height="350" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                    class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS"
                                    transform="translate(0, 0)" style="background: transparent;">
                                    <foreignObject x="0" y="0" width="774" height="350">
                                        <div class="apexcharts-legend apexcharts-align-right apx-legend-position-top"
                                            xmlns="http://www.w3.org/1999/xhtml"
                                            style="right: 0px; position: absolute; left: 0px; top: 4px; max-height: 175px;">
                                            <div class="apexcharts-legend-series" rel="1" seriesname="Bookings"
                                                data:collapsed="false" style="margin: 2px 5px;"><span
                                                    class="apexcharts-legend-marker" rel="1"
                                                    data:collapsed="false"
                                                    style="background: rgb(255, 162, 29) !important; color: rgb(255, 162, 29); height: 12px; width: 12px; left: 0px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span
                                                    class="apexcharts-legend-text" rel="1" i="0"
                                                    data:default-text="Bookings" data:collapsed="false"
                                                    style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Bookings</span>
                                            </div>
                                            <div class="apexcharts-legend-series" rel="2"
                                                seriesname="Subscriptions" data:collapsed="false"
                                                style="margin: 2px 5px;"><span class="apexcharts-legend-marker"
                                                    rel="2" data:collapsed="false"
                                                    style="background: rgb(12, 175, 96) !important; color: rgb(12, 175, 96); height: 12px; width: 12px; left: 0px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span
                                                    class="apexcharts-legend-text" rel="2" i="1"
                                                    data:default-text="Subscriptions" data:collapsed="false"
                                                    style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Subscriptions</span>
                                            </div>
                                        </div>

                                    </foreignObject>
                                    <g id="SvgjsG1190" class="apexcharts-inner apexcharts-graphical"
                                        transform="translate(51.4765625, 53)">
                                        <defs id="SvgjsDefs1189">
                                            <clipPath id="gridRectMaskaxvliwug">
                                                <rect id="SvgjsRect1197" width="699.51171875" height="238.69600000000003"
                                                    x="-3" y="-1" rx="0" ry="0" opacity="1"
                                                    stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                                </rect>
                                            </clipPath>
                                            <clipPath id="forecastMaskaxvliwug"></clipPath>
                                            <clipPath id="nonForecastMaskaxvliwug"></clipPath>
                                            <clipPath id="gridRectMarkerMaskaxvliwug">
                                                <rect id="SvgjsRect1198" width="741.51171875" height="284.696" x="-24"
                                                    y="-24" rx="0" ry="0" opacity="1"
                                                    stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                                </rect>
                                            </clipPath>
                                        </defs>
                                        <line id="SvgjsLine1196" x1="0" y1="0" x2="0"
                                            y2="236.69600000000003" stroke="#b6b6b6" stroke-dasharray="3"
                                            stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0"
                                            width="1" height="236.69600000000003" fill="#b1b9c4" filter="none"
                                            fill-opacity="0.9" stroke-width="1"></line>
                                        <g id="SvgjsG1266" class="apexcharts-xaxis" transform="translate(0, 0)">
                                            <g id="SvgjsG1267" class="apexcharts-xaxis-texts-g"
                                                transform="translate(0, -4)"><text id="SvgjsText1269"
                                                    font-family="Helvetica, Arial, sans-serif" x="0" y="265.696"
                                                    text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                                    font-weight="400" fill="#373d3f"
                                                    class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1270">27-Apr</tspan>
                                                    <title>27-Apr</title>
                                                </text><text id="SvgjsText1272" font-family="Helvetica, Arial, sans-serif"
                                                    x="49.53655133928571" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1273">26-Apr</tspan>
                                                    <title>26-Apr</title>
                                                </text><text id="SvgjsText1275" font-family="Helvetica, Arial, sans-serif"
                                                    x="99.07310267857142" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1276">25-Apr</tspan>
                                                    <title>25-Apr</title>
                                                </text><text id="SvgjsText1278" font-family="Helvetica, Arial, sans-serif"
                                                    x="148.60965401785714" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1279">24-Apr</tspan>
                                                    <title>24-Apr</title>
                                                </text><text id="SvgjsText1281" font-family="Helvetica, Arial, sans-serif"
                                                    x="198.14620535714286" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1282">23-Apr</tspan>
                                                    <title>23-Apr</title>
                                                </text><text id="SvgjsText1284" font-family="Helvetica, Arial, sans-serif"
                                                    x="247.68275669642858" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1285">22-Apr</tspan>
                                                    <title>22-Apr</title>
                                                </text><text id="SvgjsText1287" font-family="Helvetica, Arial, sans-serif"
                                                    x="297.21930803571433" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1288">21-Apr</tspan>
                                                    <title>21-Apr</title>
                                                </text><text id="SvgjsText1290" font-family="Helvetica, Arial, sans-serif"
                                                    x="346.75585937500006" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1291">20-Apr</tspan>
                                                    <title>20-Apr</title>
                                                </text><text id="SvgjsText1293" font-family="Helvetica, Arial, sans-serif"
                                                    x="396.2924107142858" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1294">19-Apr</tspan>
                                                    <title>19-Apr</title>
                                                </text><text id="SvgjsText1296" font-family="Helvetica, Arial, sans-serif"
                                                    x="445.8289620535715" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1297">18-Apr</tspan>
                                                    <title>18-Apr</title>
                                                </text><text id="SvgjsText1299" font-family="Helvetica, Arial, sans-serif"
                                                    x="495.36551339285717" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1300">17-Apr</tspan>
                                                    <title>17-Apr</title>
                                                </text><text id="SvgjsText1302" font-family="Helvetica, Arial, sans-serif"
                                                    x="544.9020647321428" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1303">16-Apr</tspan>
                                                    <title>16-Apr</title>
                                                </text><text id="SvgjsText1305" font-family="Helvetica, Arial, sans-serif"
                                                    x="594.4386160714284" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1306">15-Apr</tspan>
                                                    <title>15-Apr</title>
                                                </text><text id="SvgjsText1308" font-family="Helvetica, Arial, sans-serif"
                                                    x="643.9751674107141" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1309">14-Apr</tspan>
                                                    <title>14-Apr</title>
                                                </text><text id="SvgjsText1311" font-family="Helvetica, Arial, sans-serif"
                                                    x="693.5117187499998" y="265.696" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="12px" font-weight="400"
                                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                    style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1312">13-Apr</tspan>
                                                    <title>13-Apr</title>
                                                </text></g>
                                            <g id="SvgjsG1313" class="apexcharts-xaxis-title"><text id="SvgjsText1314"
                                                    font-family="Helvetica, Arial, sans-serif" x="346.755859375" y="291"
                                                    text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                                    font-weight="900" fill="#373d3f"
                                                    class="apexcharts-text apexcharts-xaxis-title-text "
                                                    style="font-family: Helvetica, Arial, sans-serif;">Date</text>
                                            </g>
                                            <line id="SvgjsLine1315" x1="0" y1="237.69600000000003"
                                                x2="693.51171875" y2="237.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-width="1" stroke-linecap="butt">
                                            </line>
                                        </g>
                                        <g id="SvgjsG1328" class="apexcharts-grid">
                                            <g id="SvgjsG1329" class="apexcharts-gridlines-horizontal">
                                                <line id="SvgjsLine1346" x1="0" y1="0" x2="693.51171875"
                                                    y2="0" stroke="#e0e0e0" stroke-dasharray="4"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1347" x1="0" y1="59.17400000000001"
                                                    x2="693.51171875" y2="59.17400000000001" stroke="#e0e0e0"
                                                    stroke-dasharray="4" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1348" x1="0" y1="118.34800000000001"
                                                    x2="693.51171875" y2="118.34800000000001" stroke="#e0e0e0"
                                                    stroke-dasharray="4" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1349" x1="0" y1="177.52200000000002"
                                                    x2="693.51171875" y2="177.52200000000002" stroke="#e0e0e0"
                                                    stroke-dasharray="4" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1350" x1="0" y1="236.69600000000003"
                                                    x2="693.51171875" y2="236.69600000000003" stroke="#e0e0e0"
                                                    stroke-dasharray="4" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                            </g>
                                            <g id="SvgjsG1330" class="apexcharts-gridlines-vertical"></g>
                                            <line id="SvgjsLine1331" x1="0" y1="237.69600000000003"
                                                x2="0" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1332" x1="49.536551339285715" y1="237.69600000000003"
                                                x2="49.536551339285715" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1333" x1="99.07310267857143" y1="237.69600000000003"
                                                x2="99.07310267857143" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1334" x1="148.60965401785714" y1="237.69600000000003"
                                                x2="148.60965401785714" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1335" x1="198.14620535714286" y1="237.69600000000003"
                                                x2="198.14620535714286" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1336" x1="247.68275669642858" y1="237.69600000000003"
                                                x2="247.68275669642858" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1337" x1="297.2193080357143" y1="237.69600000000003"
                                                x2="297.2193080357143" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1338" x1="346.755859375" y1="237.69600000000003"
                                                x2="346.755859375" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1339" x1="396.2924107142857" y1="237.69600000000003"
                                                x2="396.2924107142857" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1340" x1="445.82896205357144" y1="237.69600000000003"
                                                x2="445.82896205357144" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1341" x1="495.36551339285717" y1="237.69600000000003"
                                                x2="495.36551339285717" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1342" x1="544.9020647321429" y1="237.69600000000003"
                                                x2="544.9020647321429" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1343" x1="594.4386160714286" y1="237.69600000000003"
                                                x2="594.4386160714286" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1344" x1="643.9751674107142" y1="237.69600000000003"
                                                x2="643.9751674107142" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1345" x1="693.5117187499999" y1="237.69600000000003"
                                                x2="693.5117187499999" y2="243.69600000000003" stroke="#e0e0e0"
                                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick">
                                            </line>
                                            <line id="SvgjsLine1352" x1="0" y1="236.69600000000003"
                                                x2="693.51171875" y2="236.69600000000003" stroke="transparent"
                                                stroke-dasharray="0" stroke-linecap="butt"></line>
                                            <line id="SvgjsLine1351" x1="0" y1="1" x2="0"
                                                y2="236.69600000000003" stroke="transparent" stroke-dasharray="0"
                                                stroke-linecap="butt"></line>
                                        </g>
                                        <g id="SvgjsG1199" class="apexcharts-line-series apexcharts-plot-series">
                                            <g id="SvgjsG1200" class="apexcharts-series" seriesName="Bookings"
                                                data:longestSeries="true" rel="1" data:realIndex="0">
                                                <path id="SvgjsPath1232"
                                                    d="M 0 31.06635C 17.337792968749998 31.06635 32.198758370535714 147.935 49.536551339285715 147.935C 66.87434430803572 147.935 81.73530970982144 125.74475000000001 99.07310267857143 125.74475000000001C 116.41089564732142 125.74475000000001 131.27186104910714 48.07887500000001 148.60965401785714 48.07887500000001C 165.94744698660713 48.07887500000001 180.80841238839287 125.74475000000001 198.14620535714286 125.74475000000001C 215.48399832589286 125.74475000000001 230.3449637276786 96.15775000000002 247.68275669642858 96.15775000000002C 265.0205496651786 96.15775000000002 279.8815150669643 22.190249999999992 297.2193080357143 22.190249999999992C 314.5571010044643 22.190249999999992 329.41806640625 199.71225 346.755859375 199.71225C 364.09365234375 199.71225 378.9546177455357 203.41062500000004 396.2924107142857 203.41062500000004C 413.63020368303575 203.41062500000004 428.4911690848214 216.72477500000002 445.82896205357144 216.72477500000002C 463.16675502232147 216.72477500000002 478.02772042410714 162.7285 495.36551339285717 162.7285C 512.7033063616071 162.7285 527.5642717633929 173.82362500000002 544.9020647321429 173.82362500000002C 562.2398577008929 173.82362500000002 577.1008231026785 170.12525000000002 594.4386160714286 170.12525000000002C 611.7764090401786 170.12525000000002 626.6373744419643 181.96005000000002 643.9751674107143 181.96005000000002C 661.3129603794644 181.96005000000002 676.17392578125 147.935 693.51171875 147.935"
                                                    fill="none" fill-opacity="1" stroke="rgba(255,162,29,0.85)"
                                                    stroke-opacity="1" stroke-linecap="butt" stroke-width="2"
                                                    stroke-dasharray="0" class="apexcharts-line" index="0"
                                                    clip-path="url(#gridRectMaskaxvliwug)"
                                                    pathTo="M 0 31.06635C 17.337792968749998 31.06635 32.198758370535714 147.935 49.536551339285715 147.935C 66.87434430803572 147.935 81.73530970982144 125.74475000000001 99.07310267857143 125.74475000000001C 116.41089564732142 125.74475000000001 131.27186104910714 48.07887500000001 148.60965401785714 48.07887500000001C 165.94744698660713 48.07887500000001 180.80841238839287 125.74475000000001 198.14620535714286 125.74475000000001C 215.48399832589286 125.74475000000001 230.3449637276786 96.15775000000002 247.68275669642858 96.15775000000002C 265.0205496651786 96.15775000000002 279.8815150669643 22.190249999999992 297.2193080357143 22.190249999999992C 314.5571010044643 22.190249999999992 329.41806640625 199.71225 346.755859375 199.71225C 364.09365234375 199.71225 378.9546177455357 203.41062500000004 396.2924107142857 203.41062500000004C 413.63020368303575 203.41062500000004 428.4911690848214 216.72477500000002 445.82896205357144 216.72477500000002C 463.16675502232147 216.72477500000002 478.02772042410714 162.7285 495.36551339285717 162.7285C 512.7033063616071 162.7285 527.5642717633929 173.82362500000002 544.9020647321429 173.82362500000002C 562.2398577008929 173.82362500000002 577.1008231026785 170.12525000000002 594.4386160714286 170.12525000000002C 611.7764090401786 170.12525000000002 626.6373744419643 181.96005000000002 643.9751674107143 181.96005000000002C 661.3129603794644 181.96005000000002 676.17392578125 147.935 693.51171875 147.935"
                                                    pathFrom="M -1 236.69600000000003L -1 236.69600000000003L 49.536551339285715 236.69600000000003L 99.07310267857143 236.69600000000003L 148.60965401785714 236.69600000000003L 198.14620535714286 236.69600000000003L 247.68275669642858 236.69600000000003L 297.2193080357143 236.69600000000003L 346.755859375 236.69600000000003L 396.2924107142857 236.69600000000003L 445.82896205357144 236.69600000000003L 495.36551339285717 236.69600000000003L 544.9020647321429 236.69600000000003L 594.4386160714286 236.69600000000003L 643.9751674107143 236.69600000000003L 693.51171875 236.69600000000003">
                                                </path>
                                                <g id="SvgjsG1201" class="apexcharts-series-markers-wrap"
                                                    data:realIndex="0">
                                                    <g id="SvgjsG1203" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1204" r="4" cx="0" cy="31.06635"
                                                            class="apexcharts-marker no-pointer-events wxqnx3p64"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="0" j="0"
                                                            index="0" default-marker-size="4"></circle>
                                                        <circle id="SvgjsCircle1205" r="4" cx="49.536551339285715"
                                                            cy="147.935"
                                                            class="apexcharts-marker no-pointer-events ws7w640dy"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="1" j="1"
                                                            index="0" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1206" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1207" r="4" cx="99.07310267857143"
                                                            cy="125.74475000000001"
                                                            class="apexcharts-marker no-pointer-events w4in94316"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="2" j="2"
                                                            index="0" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1208" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1209" r="4" cx="148.60965401785714"
                                                            cy="48.07887500000001"
                                                            class="apexcharts-marker no-pointer-events w0iqsduao"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="3" j="3"
                                                            index="0" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1210" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1211" r="4" cx="198.14620535714286"
                                                            cy="125.74475000000001"
                                                            class="apexcharts-marker no-pointer-events wvq3kezba"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="4" j="4"
                                                            index="0" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1212" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1213" r="4" cx="247.68275669642858"
                                                            cy="96.15775000000002"
                                                            class="apexcharts-marker no-pointer-events wvirwrogci"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="5" j="5"
                                                            index="0" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1214" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1215" r="4" cx="297.2193080357143"
                                                            cy="22.190249999999992"
                                                            class="apexcharts-marker no-pointer-events wubop4pzr"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="6" j="6"
                                                            index="0" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1216" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1217" r="4" cx="346.755859375"
                                                            cy="199.71225"
                                                            class="apexcharts-marker no-pointer-events wqwlr6xgzi"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="7" j="7"
                                                            index="0" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1218" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1219" r="4" cx="396.2924107142857"
                                                            cy="203.41062500000004"
                                                            class="apexcharts-marker no-pointer-events wjqptvmduk"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="8" j="8"
                                                            index="0" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1220" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1221" r="4" cx="445.82896205357144"
                                                            cy="216.72477500000002"
                                                            class="apexcharts-marker no-pointer-events w13y0syrli"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="9" j="9"
                                                            index="0" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1222" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1223" r="4" cx="495.36551339285717"
                                                            cy="162.7285"
                                                            class="apexcharts-marker no-pointer-events wojueil4j"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="10" j="10"
                                                            index="0" default-marker-size="4">
                                                        </circle>
                                                    </g>
                                                    <g id="SvgjsG1224" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1225" r="4" cx="544.9020647321429"
                                                            cy="173.82362500000002"
                                                            class="apexcharts-marker no-pointer-events w49pkytr7"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="11" j="11"
                                                            index="0" default-marker-size="4">
                                                        </circle>
                                                    </g>
                                                    <g id="SvgjsG1226" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1227" r="4" cx="594.4386160714286"
                                                            cy="170.12525000000002"
                                                            class="apexcharts-marker no-pointer-events wnj2q12yf"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="12" j="12"
                                                            index="0" default-marker-size="4">
                                                        </circle>
                                                    </g>
                                                    <g id="SvgjsG1228" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1229" r="4" cx="643.9751674107143"
                                                            cy="181.96005000000002"
                                                            class="apexcharts-marker no-pointer-events wbdbwhu0m"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="13" j="13"
                                                            index="0" default-marker-size="4">
                                                        </circle>
                                                    </g>
                                                    <g id="SvgjsG1230" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1231" r="4" cx="693.51171875"
                                                            cy="147.935"
                                                            class="apexcharts-marker no-pointer-events w8a96mhvg"
                                                            stroke="#ffffff" fill="#ffa21d" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="14" j="14"
                                                            index="0" default-marker-size="4">
                                                        </circle>
                                                    </g>
                                                </g>
                                            </g>
                                            <g id="SvgjsG1233" class="apexcharts-series" seriesName="Subscriptions"
                                                data:longestSeries="true" rel="2" data:realIndex="1">
                                                <path id="SvgjsPath1265"
                                                    d="M 0 225.2310375C 17.337792968749998 225.2310375 32.198758370535714 199.71225 49.536551339285715 199.71225C 66.87434430803572 199.71225 81.73530970982144 207.10900000000004 99.07310267857143 207.10900000000004C 116.41089564732142 207.10900000000004 131.27186104910714 221.90250000000003 148.60965401785714 221.90250000000003C 165.94744698660713 221.90250000000003 180.80841238839287 196.013875 198.14620535714286 196.013875C 215.48399832589286 196.013875 230.3449637276786 192.31550000000001 247.68275669642858 192.31550000000001C 265.0205496651786 192.31550000000001 279.8815150669643 214.50575000000003 297.2193080357143 214.50575000000003C 314.5571010044643 214.50575000000003 329.41806640625 227.81990000000002 346.755859375 227.81990000000002C 364.09365234375 227.81990000000002 378.9546177455357 207.10900000000004 396.2924107142857 207.10900000000004C 413.63020368303575 207.10900000000004 428.4911690848214 223.38185000000001 445.82896205357144 223.38185000000001C 463.16675502232147 223.38185000000001 478.02772042410714 206.36932500000003 495.36551339285717 206.36932500000003C 512.7033063616071 206.36932500000003 527.5642717633929 213.02640000000002 544.9020647321429 213.02640000000002C 562.2398577008929 213.02640000000002 577.1008231026785 196.75355000000002 594.4386160714286 196.75355000000002C 611.7764090401786 196.75355000000002 626.6373744419643 181.22037500000002 643.9751674107143 181.22037500000002C 661.3129603794644 181.22037500000002 676.17392578125 170.12525000000002 693.51171875 170.12525000000002"
                                                    fill="none" fill-opacity="1" stroke="rgba(12,175,96,0.85)"
                                                    stroke-opacity="1" stroke-linecap="butt" stroke-width="2"
                                                    stroke-dasharray="0" class="apexcharts-line" index="1"
                                                    clip-path="url(#gridRectMaskaxvliwug)"
                                                    pathTo="M 0 225.2310375C 17.337792968749998 225.2310375 32.198758370535714 199.71225 49.536551339285715 199.71225C 66.87434430803572 199.71225 81.73530970982144 207.10900000000004 99.07310267857143 207.10900000000004C 116.41089564732142 207.10900000000004 131.27186104910714 221.90250000000003 148.60965401785714 221.90250000000003C 165.94744698660713 221.90250000000003 180.80841238839287 196.013875 198.14620535714286 196.013875C 215.48399832589286 196.013875 230.3449637276786 192.31550000000001 247.68275669642858 192.31550000000001C 265.0205496651786 192.31550000000001 279.8815150669643 214.50575000000003 297.2193080357143 214.50575000000003C 314.5571010044643 214.50575000000003 329.41806640625 227.81990000000002 346.755859375 227.81990000000002C 364.09365234375 227.81990000000002 378.9546177455357 207.10900000000004 396.2924107142857 207.10900000000004C 413.63020368303575 207.10900000000004 428.4911690848214 223.38185000000001 445.82896205357144 223.38185000000001C 463.16675502232147 223.38185000000001 478.02772042410714 206.36932500000003 495.36551339285717 206.36932500000003C 512.7033063616071 206.36932500000003 527.5642717633929 213.02640000000002 544.9020647321429 213.02640000000002C 562.2398577008929 213.02640000000002 577.1008231026785 196.75355000000002 594.4386160714286 196.75355000000002C 611.7764090401786 196.75355000000002 626.6373744419643 181.22037500000002 643.9751674107143 181.22037500000002C 661.3129603794644 181.22037500000002 676.17392578125 170.12525000000002 693.51171875 170.12525000000002"
                                                    pathFrom="M -1 236.69600000000003L -1 236.69600000000003L 49.536551339285715 236.69600000000003L 99.07310267857143 236.69600000000003L 148.60965401785714 236.69600000000003L 198.14620535714286 236.69600000000003L 247.68275669642858 236.69600000000003L 297.2193080357143 236.69600000000003L 346.755859375 236.69600000000003L 396.2924107142857 236.69600000000003L 445.82896205357144 236.69600000000003L 495.36551339285717 236.69600000000003L 544.9020647321429 236.69600000000003L 594.4386160714286 236.69600000000003L 643.9751674107143 236.69600000000003L 693.51171875 236.69600000000003">
                                                </path>
                                                <g id="SvgjsG1234" class="apexcharts-series-markers-wrap"
                                                    data:realIndex="1">
                                                    <g id="SvgjsG1236" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1237" r="4" cx="0"
                                                            cy="225.2310375"
                                                            class="apexcharts-marker no-pointer-events w7cs0xmji"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="0" j="0"
                                                            index="1" default-marker-size="4"></circle>
                                                        <circle id="SvgjsCircle1238" r="4" cx="49.536551339285715"
                                                            cy="199.71225"
                                                            class="apexcharts-marker no-pointer-events wlxsv46ym"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="1" j="1"
                                                            index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1239" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1240" r="4" cx="99.07310267857143"
                                                            cy="207.10900000000004"
                                                            class="apexcharts-marker no-pointer-events w0oenl48c"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="2" j="2"
                                                            index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1241" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1242" r="4" cx="148.60965401785714"
                                                            cy="221.90250000000003"
                                                            class="apexcharts-marker no-pointer-events wzzeqt7fa"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="3" j="3"
                                                            index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1243" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1244" r="4" cx="198.14620535714286"
                                                            cy="196.013875"
                                                            class="apexcharts-marker no-pointer-events wt2oh7ytk"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="4" j="4"
                                                            index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1245" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1246" r="4" cx="247.68275669642858"
                                                            cy="192.31550000000001"
                                                            class="apexcharts-marker no-pointer-events wts200xe2"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="5" j="5"
                                                            index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1247" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1248" r="4" cx="297.2193080357143"
                                                            cy="214.50575000000003"
                                                            class="apexcharts-marker no-pointer-events wuviaqosf"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="6" j="6"
                                                            index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1249" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1250" r="4" cx="346.755859375"
                                                            cy="227.81990000000002"
                                                            class="apexcharts-marker no-pointer-events wng7mzyel"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="7" j="7"
                                                            index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1251" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1252" r="4" cx="396.2924107142857"
                                                            cy="207.10900000000004"
                                                            class="apexcharts-marker no-pointer-events w6e8of96w"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="8" j="8"
                                                            index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1253" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1254" r="4" cx="445.82896205357144"
                                                            cy="223.38185000000001"
                                                            class="apexcharts-marker no-pointer-events wjn1iwzyo"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="9" j="9"
                                                            index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1255" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1256" r="4" cx="495.36551339285717"
                                                            cy="206.36932500000003"
                                                            class="apexcharts-marker no-pointer-events wnptg4tvi"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="10"
                                                            j="10" index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1257" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1258" r="4" cx="544.9020647321429"
                                                            cy="213.02640000000002"
                                                            class="apexcharts-marker no-pointer-events wk0c1pjgl"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="11"
                                                            j="11" index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1259" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1260" r="4" cx="594.4386160714286"
                                                            cy="196.75355000000002"
                                                            class="apexcharts-marker no-pointer-events w4mxyypp4"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="12"
                                                            j="12" index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1261" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1262" r="4" cx="643.9751674107143"
                                                            cy="181.22037500000002"
                                                            class="apexcharts-marker no-pointer-events w1f9dpza5"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="13"
                                                            j="13" index="1" default-marker-size="4"></circle>
                                                    </g>
                                                    <g id="SvgjsG1263" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskaxvliwug)">
                                                        <circle id="SvgjsCircle1264" r="4" cx="693.51171875"
                                                            cy="170.12525000000002"
                                                            class="apexcharts-marker no-pointer-events wqkt88ggxj"
                                                            stroke="#ffffff" fill="#0caf60" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="0.9" rel="14"
                                                            j="14" index="1" default-marker-size="4"></circle>
                                                    </g>
                                                </g>
                                            </g>
                                            <g id="SvgjsG1202" class="apexcharts-datalabels" data:realIndex="0"></g>
                                            <g id="SvgjsG1235" class="apexcharts-datalabels" data:realIndex="1"></g>
                                        </g>
                                        <line id="SvgjsLine1353" x1="0" y1="0" x2="693.51171875"
                                            y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                            stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                        <line id="SvgjsLine1354" x1="0" y1="0" x2="693.51171875"
                                            y2="0" stroke-dasharray="0" stroke-width="0"
                                            stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                        <g id="SvgjsG1355" class="apexcharts-yaxis-annotations"></g>
                                        <g id="SvgjsG1356" class="apexcharts-xaxis-annotations"></g>
                                        <g id="SvgjsG1357" class="apexcharts-point-annotations"></g>
                                        <rect id="SvgjsRect1358" width="0" height="0" x="0" y="0"
                                            rx="0" ry="0" opacity="1" stroke-width="0"
                                            stroke="none" stroke-dasharray="0" fill="#fefefe"
                                            class="apexcharts-zoom-rect"></rect>
                                        <rect id="SvgjsRect1359" width="0" height="0" x="0" y="0"
                                            rx="0" ry="0" opacity="1" stroke-width="0"
                                            stroke="none" stroke-dasharray="0" fill="#fefefe"
                                            class="apexcharts-selection-rect"></rect>
                                    </g>
                                    <rect id="SvgjsRect1195" width="0" height="0" x="0" y="0"
                                        rx="0" ry="0" opacity="1" stroke-width="0"
                                        stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                    <g id="SvgjsG1316" class="apexcharts-yaxis" rel="0"
                                        transform="translate(21.4765625, 0)">
                                        <g id="SvgjsG1317" class="apexcharts-yaxis-texts-g"><text id="SvgjsText1318"
                                                font-family="Helvetica, Arial, sans-serif" x="20" y="54.4"
                                                text-anchor="end" dominant-baseline="auto" font-size="11px"
                                                font-weight="400" fill="#373d3f"
                                                class="apexcharts-text apexcharts-yaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1319">3200</tspan>
                                                <title>3200</title>
                                            </text><text id="SvgjsText1320" font-family="Helvetica, Arial, sans-serif"
                                                x="20" y="113.57400000000001" text-anchor="end" dominant-baseline="auto"
                                                font-size="11px" font-weight="400" fill="#373d3f"
                                                class="apexcharts-text apexcharts-yaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1321">2400</tspan>
                                                <title>2400</title>
                                            </text><text id="SvgjsText1322" font-family="Helvetica, Arial, sans-serif"
                                                x="20" y="172.74800000000002" text-anchor="end" dominant-baseline="auto"
                                                font-size="11px" font-weight="400" fill="#373d3f"
                                                class="apexcharts-text apexcharts-yaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1323">1600</tspan>
                                                <title>1600</title>
                                            </text><text id="SvgjsText1324" font-family="Helvetica, Arial, sans-serif"
                                                x="20" y="231.92200000000003" text-anchor="end" dominant-baseline="auto"
                                                font-size="11px" font-weight="400" fill="#373d3f"
                                                class="apexcharts-text apexcharts-yaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1325">800</tspan>
                                                <title>800</title>
                                            </text><text id="SvgjsText1326" font-family="Helvetica, Arial, sans-serif"
                                                x="20" y="291.096" text-anchor="end" dominant-baseline="auto"
                                                font-size="11px" font-weight="400" fill="#373d3f"
                                                class="apexcharts-text apexcharts-yaxis-label "
                                                style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1327">0</tspan>
                                                <title>0</title>
                                            </text></g>
                                    </g>
                                    <g id="SvgjsG1191" class="apexcharts-annotations"></g>
                                </svg>
                                <div class="apexcharts-tooltip apexcharts-theme-light">
                                    <div class="apexcharts-tooltip-title"
                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                    <div class="apexcharts-tooltip-series-group" style="order: 1;"><span
                                            class="apexcharts-tooltip-marker"
                                            style="background-color: rgb(255, 162, 29);"></span>
                                        <div class="apexcharts-tooltip-text"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                    <div class="apexcharts-tooltip-series-group" style="order: 2;"><span
                                            class="apexcharts-tooltip-marker"
                                            style="background-color: rgb(12, 175, 96);"></span>
                                        <div class="apexcharts-tooltip-text"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
                                    <div class="apexcharts-xaxistooltip-text"
                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                </div>
                                <div
                                    class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                    <div class="apexcharts-yaxistooltip-text"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

@endsection
