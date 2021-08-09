<ul>
@foreach($users as $index => $user)
    <li>{{$user->id}}</li>
@endforeach
</ul>
{{$users->links()}}
