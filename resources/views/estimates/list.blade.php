@inject('estimate_price_calculator','App\Estimates\Calculator')

@extends('layouts.standard')

@section('page')

    <section class="standard-page estimate-listings">

        <h1>Your Estimates</h1>

        <div class="estimate-list">
            <div class="estimate-entry create-new-estimate-tile">
                <div class="estimate-entry-details">
                    <a class="create-symbol" href="{{ route('new-estimate') }}"><i class="fa fa-plus"></i></a>
                    <a class="cta-open-estimate" href="{{ route('new-estimate') }}">Create New</a>
                </div>
            </div>

            @foreach($estimates as $estimate)
                <div class="estimate-entry">
                    <div class="estimate-entry-details">
                        <h2>{{ $estimate->name }}</h2>
                        <h3>{{ $estimate->total_cost }}<br />{{ $estimate->total_time }}</h3>
                        <a class="cta-open-estimate" href="{{ route('get-estimate',[$estimate->id]) }}">Open</a>
                    </div>
                </div>
            @endforeach

        </div>

    </section>

@stop