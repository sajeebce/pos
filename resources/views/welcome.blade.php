@extends('layouts.auth2')
@section('title', config('app.name', 'ultimatePOS'))
@inject('request', 'Illuminate\Http\Request')
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12 tw-pt-20 tw-pb-10 tw-px-5 tw-flex tw-flex-col tw-items-center tw-justify-center mt-5"
        style="background-color: #2F1A52 !important;">
        <div class="tw-text-6xl tw-font-extrabold tw-text-center tw-text-white tw-shadow-lg tw-px-4 tw-py-2 tw-rounded-md"
            style="background-color: #2F1A52 !important;">
            {{ config('app.name', 'UltimatePOS') }}
        </div>

        <p class="tw-text-lg tw-font-medium tw-text-center tw-text-white tw-mt-2 tw-shadow-md tw-rounded-md tw-px-3 tw-py-1"
            style="background-color: #2F1A52 !important;">
            {{ env('APP_TITLE', '') }}
        </p>
    </div>

@endsection
