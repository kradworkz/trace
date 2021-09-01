@extends('layouts.guest')
@section('content')
	<style type="text/css">
		a { color: #428bca !important; }
		input[type="text"] { width:250px }
	</style>
	<br><br><br><br>
	<div class="centering-container">
        <div class="centering-content">
            <div class="centering-horizontal">
                <div class="jumbotron">
                    <div class="container">
	                    <div class="row"> 
	                    	<div class="col-md-3">
	                    	</div>
	                    	<div class="col-md-6">
			                	<div class="panel panel-default">
									<div class="panel-body">
										<h1><img src="{{ asset('images/logos/trace_logo.png') }}"/></h1>
										<h4>{{ App\Models\Setting::orderBy('s_id', 'asc')->value('s_sysname') }}</h4>
										<br><br>
										{!! Form::open(['url' => 'login', 'class' => 'form', 'autocomplete' => 'off']) !!}
											@if( $error_message )
												<div class="text-danger text-center"><strong>{{ $error_message }}</strong></div><br>
											@endif
											@if(Session::has('success'))
									        	<div class="alert alert-success fade in" id="alert">
									            	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										          	<strong><span class="fa fa-check-circle"></span> {{ Session::get('success') }}</strong>
									        	</div>
									        @endif
											<fieldset>
												<div class="form-group {{ ($errors->has('u_username') ? ' has-error' : '') }} has-feedback">
													<div class="input-group">
														<span class="input-group-addon">Username</span>
														{!! Form::text('u_username', null, ['class'=>'form-control', 'placeholder'=>'Username', 'required', 'autofocus']) !!}
													</div>
												</div>
												<div class="form-group {{ ($errors->has('u_password') ? ' has-error' : '') }} has-feedback">
													<div class="input-group">
														<span class="input-group-addon">Password&nbsp;</span>
														{!! Form::password('u_password', ['class' => 'form-control', 'placeholder' => 'Password', 'required', 'maxlength' => '255']) !!}
													</div>
												</div>
												{!! Form::button('<i class="fa fa-sign-in"></i> Login', ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) !!}
												<br>
												<!-- <div class="pull-left">
													<a href="{{ URL::to('forgot_password') }}">Forgot Password?</a>
												</div> -->
												<div class="pull-right">
													<!-- <a href="{{ URL::to('register') }}" id="bottomlink">Not Yet Registered?</a> -->
												</div>									
											</fieldset>
										{!! Form::close() !!}
			                		</div>
			                	</div>
		                	</div>
		                	<div class="col-md-3">
	                    	</div>
			            </div>
			        </div>
                </div>
            </div>
        </div>
    </div>
@stop