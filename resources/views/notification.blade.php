    @forelse ($notifications as $notification)
    <a class="list-group-item list-group-item-action">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
            </div>
            <div class="flex-grow-1 ms-1">
                <span class="float-end text-muted">3:00 AM</span>
                <p class="text-body mb-1">{{ $notification->fromUser->name }}</p>
                <span class="text-muted">2 min ago</span>
            </div>
        </div>
    </a>
    @empty
        <div class="flex-grow-1 ms-1 text-center">
                Tidak ada pemberitahuan
            </div>
    @endforelse
