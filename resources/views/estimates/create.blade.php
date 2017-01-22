@extends('layouts.standard')

@section('page')

    <section class="create-estimate-dialog">
        <h1>Start A New Estimate</h1>
        <form method="POST" action="{{ route('do-create') }}">
            {{ csrf_field() }}
            <input type="text" autocomplete="off" placeholder="Estimate Name..." name="name" class="styled-input" />
            <input type="number" autocomplete="off" placeholder="{{ $user->rate_time_unit == 'daily' ? 'Daily Rate...' : 'Hourly Rate...' }}" name="rate" value="{{ $user->default_rate }}" class="styled-input" />
            <input type="submit" value="Next..." />
        </form>
    </section>

@stop