<style>
    .dive {
    {{--img src="{!! asset('assets/pages/img/school.jpg')!!}"--}}
     /*background-image: url(asset('assets/pages/img/school.jpg'));*/
        /*background-image: url('../img/eidyict_logo_op60.png');*/
        /*background-size: 400px 400px;*/
        /*background-color: green;*/

        /*background-repeat: no-repeat;*/
        /*background-attachment: scroll;*/
        background-position: center center;

        /*background-image {*/
        /*background-image-opacity: 0.5;*/
        /*filter: alpha(opacity=50); !* For IE8 and earlier *!*/
        /*}*/
        /*-webkit-background-size: cover;*/
        /*-moz-background-size: cover;*/
        /*background-size: cover;*/
        /*-o-background-size: cover;*/
        text-align: center;
        color: papayawhip;
    }

    .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        text-align: right;
    }

    .m-b-md {
        margin-bottom: 30px;
    }

</style>
@extends('layouts.app')

@section('content')
    <div class="container ">
        <div class="col-md-9">
            <div class=" m-b-md">
                {{--<img src="{!! asset( 'imgs/logos/eis_logo.jpg') !!}" class="user-pic" style="width: 150px; height: 150px;">--}}
                <img src="{!! asset('storage/images/home_banner.png')!!}" alt="home banner"
                     style="height: 200px; width: auto; align: middle ; "/>
            </div>
            <h1> {{ config('app.name', 'EIS') }} <br/>Enterprise Resource Planning <small>(V-2.1)</small></h1>
            <h4 style="color: orangered"> Powered By - Eidy ICT Solutions Ltd. (01716-383038)</h4>
        </div>
        <div class="col-md-3">
            <img src="{!! asset('storage/images/pad_top.png')!!}" alt="logo"
                 class="img-fluid" style="height: 100px; width: auto; align: middle ; " />
        </div>
        <div class="footer">
            <a href="https://www.eidyict.com" target="_blank" class="btn btn-sm blue slidebut">
                <span>Powered By : </span>Eidy ICT Solutions Ltd
            </a>
        </div>
    </div>
@endsection

