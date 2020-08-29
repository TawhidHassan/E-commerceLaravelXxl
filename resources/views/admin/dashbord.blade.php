@extends('layouts.adminLayout.admin_design')

@section('content')
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
        </div>
        <!--End-breadcrumbs-->
        @if(Session::has('flash_message_error'))
        <div class="alert alert-error alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button> 
                <strong>{!! session('flash_message_error') !!}</strong>
        </div>
    @endif   
    @if(Session::has('flash_message_success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button> 
                <strong>{!! session('flash_message_success') !!}</strong>
        </div>
    @endif
        <!--Action boxes-->
        <div class="container-fluid">
            <div class="quick-actions_homepage">
                <ul class="quick-actions">
                    <li class="bg_lb"> <a href="index.html"> <i class="icon-dashboard"></i>My Dashboard </a> </li>

                        @if(Session::get('adminDetails')['categories_full_access']==1)
                        <li class="bg_ly"> <a href="{{ url('admin/view-categories') }}"> <i class="icon-inbox"></i><span class="label label-success">101</span> Categories </a> </li>
                        @endif
                        @if(Session::get('adminDetails')['products_access']==1)
                        <li class="bg_lo"> <a href="{{ url('admin/view-products') }}"> <i class="icon-inbox"></i><span class="label label-success">101</span> Products </a> </li>
                        @endif
                        @if(Session::get('adminDetails')['orders_access']==1)
                        <li class="bg_lb"> <a href="{{ url('admin/view-orders') }}"> <i class="icon-inbox"></i><span class="label label-success">101</span> Orders </a> </li>
                        @endif
                        @if(Session::get('adminDetails')['users_access']==1)
                        <li class="bg_lr"> <a href="{{ url('admin/view-users') }}"> <i class="icon-inbox"></i><span class="label label-success">101</span> Users </a> </li>
                        @endif

                </ul>
            </div>
            <!--End-Action boxes-->
            <hr/>
        
        </div>
    </div>
@endsection
