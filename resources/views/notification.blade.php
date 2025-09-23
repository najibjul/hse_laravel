    @forelse ($notifications as $notification)

    <form id="notificationUpdate{{ $notification->id }}" method="POST" action="{{ route('notification.update', $notification->id) }}" class="d-none">
        @csrf
        @method('patch')
    </form>

    <a href="#" onclick="event.preventDefault(); document.getElementById(`notificationUpdate{{ $notification->id }}`).submit();" class="list-group-item list-group-item-action {{ !$notification->is_read ? 'bg-light' : '' }}">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
            </div>
            <div class="flex-grow-1 ms-1">
                <p class="text-body mb-1">{{ $notification->title }}</p>
                <span class="text-muted">{{ $notification->body }}</span>
            </div>
        </div>
    </a>
    @empty
        <div class="flex-grow-1 ms-1 text-center">
                Tidak ada pesan
            </div>
    @endforelse
