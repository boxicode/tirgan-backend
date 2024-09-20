@extends('auth::layouts.master')

@section('content')

    <h2>سلام  {{ $first_name  }}</h2>

    <p>ثبت نام شما انجام شد و برای فعالسازی اکانت روی لینک زیر کلیک کنید</p>

    <a href="{{ env('EMAIL_ACTIVE_LINK_PREFIX') . $activation_token  }}">لینک</a>


@endsection
