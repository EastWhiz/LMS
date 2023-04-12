@extends(getTemplate() .'.panel.layouts.panel_layout')
@if(auth()->user()->role_name=="organization")
    @include("web.default.panel.organ-dashboard")
@else
    @include("web.default.panel.user-dashboard")
@endif