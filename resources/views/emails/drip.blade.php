DRIP DRIP
{{ $user->email }}

<ul>
@foreach ($user->projects()->outstanding() as $project)
    <li>
        <h4>
        {{ $project->title}}</h4>
        <p>{{ $project->description}}</p>
        <hr>
        <dl>
        @foreach ($project->tasks as $task)
          <li>{{ $task->body }}  || {{ $task->completed }}</li>  
        @endforeach
        </dl>
    </li>
@endforeach
</ul>