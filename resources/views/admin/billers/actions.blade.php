<div class="dropdown">
    <button
        class="btn btn-primary btn-sm dropdown-toggle"
        type="button"
        id="dropdownMenuButton{{ $row->id }}"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        title="{{ __('messages.action') }}"
    >
        {{ __('messages.action') }}
    </button>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $row->id }}">

        {{-- Add User --}}
        <li>
            <a class="dropdown-item"
                href="{{ route('billers.users.add', $row->id) }}"
                title="{{ __('messages.add_user') }}">
                <i class="bi bi-person-plus me-2"></i>
                {{ __('messages.add_user') }}
            </a>
        </li>

        {{-- List User --}}
        <li>
            <button class="dropdown-item listUser"
                type="button"
                data-id="{{ $row->id }}"
                title="{{ __('messages.list_user') }}">
                <i class="bi bi-people me-2"></i>
                {{ __('messages.list_user') }}
            </button>
        </li>

        {{-- Edit --}}
        <li>
            <a class="dropdown-item"
                href="{{ url('billers/' . $row->id . '/edit') }}"
                title="{{ __('messages.edit') }}">
                <i class="bi bi-pencil-square me-2"></i>
                {{ __('messages.edit') }}
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        {{-- Delete --}}
        <li>
            <button class="dropdown-item text-danger deleteBillerBtn"
                type="button"
                data-id="{{ $row->id }}"
                title="{{ __('messages.delete') }}">
                <i class="bi bi-trash me-2"></i>
                {{ __('messages.delete') }}
            </button>
        </li>

    </ul>
</div>
