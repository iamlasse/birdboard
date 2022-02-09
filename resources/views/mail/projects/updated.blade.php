@component('mail::message')
<div class="project-updated">
  <h1 style="text-align: center;">{{ $project->title }} </h1>

  <h4 style="text-align: center;">Here are the recent changes</h4>
  <ul style="list-style:none; margin:0; padding:0">
  @foreach($changes as $key => $change)
    <li style="margin-bottom: 1.4rem">
      <h2 style="margin-bottom: 5px; border-bottom: 1px solid;">{{ ucwords($key) }}</h2>
      <div class="before"><strong>Before</strong> {{$before[$key]}}</div>
      <div class="after"> <strong>After</strong> {{ $change }}</div>
    </li>
  @endforeach
  </ul>
</div>
@endcomponent