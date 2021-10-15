<!DOCTYPE html>
<html>
<body>
Hello <strong>{{ $name }}</strong>, <br>

<b style="color: red;"> {{ $val }}</b> <br>

<p>{{$body}}</p> <br>  

<p>Thank you and stay safe</p>

<a href="{{ url($link.$id) }}"><button> View here </button></a>
</body>

</html>