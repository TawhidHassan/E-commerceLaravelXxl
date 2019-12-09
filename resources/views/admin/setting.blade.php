@extends('layouts.adminLayout.admin_design')

@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Form elements</a> <a href="#" class="current">Validation</a> </div>
            <h4>Admin Setting</h4>
        </div>
        <div class="container-fluid"><hr>
            <div class="row-fluid">

                <div class="row-fluid">
                    <div class="span12">
                        <div class="widget-box">
                            <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                                <h5>Password Update</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <form class="form-horizontal" method="post" action="#" name="password_validate" id="password_validate" novalidate="novalidate">
                                    <div class="control-group">
                                        <label class="control-label">Old Password</label>
                                        <div class="controls">
                                            <input type="password" name="oldPassword" id="old_pasd" required />
                                            <span id="checkPass"></span>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Password</label>
                                        <div class="controls">
                                            <input type="password" name="pwd" id="new_pwd" required />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Confirm password</label>
                                        <div class="controls">
                                            <input type="password" name="pwd2" id="conf_pwd" required/>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" value="Update" class="btn btn-success">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
