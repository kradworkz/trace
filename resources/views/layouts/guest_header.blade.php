<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="Department of Science and Technology - Region IVA - MIS Unit">
<title>{{ $page }} || {{ App\Models\Setting::orderBy('s_id', 'asc')->value('s_sysname') }}</title>

<link href="{{ asset('images/logos/trace_logo.ico') }}" rel="shortcut icon">
<link rel="stylesheet" type="text/css" href="{{ asset('extensions/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('extensions/font-awesome/css/font-awesome.min.css') }}">
<script type="text/javascript" src="{{ asset('extensions/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('extensions/bootstrap/js/bootstrap.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('extensions/bootstrap/css/bootstrap.min.css') }}">

<link href="{{ asset('css/login.css') }}" rel="stylesheet">        
<link href="{{ asset('css/public.css') }}" rel="stylesheet">

</head>
	<body>