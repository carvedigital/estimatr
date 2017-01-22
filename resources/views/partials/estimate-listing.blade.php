<div class="estimate-entry">
    <div class="estimate-entry-details">
        <h2>{{ $estimate->name }}</h2>
        <h3>@money($estimate->total_cost)<br />{{ $estimate->total_time }}</h3>
        <a class="cta-open-estimate" href="{{ route('get-estimate',[$estimate->id]) }}">Open</a>
    </div>
</div>