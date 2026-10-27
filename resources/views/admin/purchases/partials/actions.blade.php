<a class="btn btn-sm btn-outline-primary" href="{{ route('purchases.show', $row->id) }}">View</a>
<a class="btn btn-sm btn-outline-secondary" href="{{ route('purchases.edit', $row->id) }}">Edit</a>
<button class="btn btn-sm btn-outline-danger delete-purchase" data-id="{{ $row->id }}">Delete</button>
