<ul class="list-group">
@forelse ($customer->users as $user)
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span>{{ $user->name }} — {{ $user->email }}</span>
        <a class="btn btn-sm btn-primary" href="{{ route('customers.users.edit', $user->id) }}">Edit</a>
    </li>
@empty
    <li class="list-group-item">No customer accounts</li>
@endforelse
</ul>
