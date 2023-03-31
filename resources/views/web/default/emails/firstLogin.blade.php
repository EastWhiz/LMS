@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">Welcome Message</h1>
        <p>Dear {{$type}}</p>
        <p>Please click the below code to complete your profile.</p>
        <p>
            <a href="{{$url}}">Complete Profile</a>
        </p>
        <p>or copy the link</p>
        <p>{{$url}}</p>
    </td>
@endsection