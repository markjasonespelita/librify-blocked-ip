<div>
    @php
        $userId   = $user->id ?? null;
        $userName = $user->name ?? 'Deleted User';
        $photo    = $user->profile_photo_path ?? null;
        $isOnline = $userId ? in_array((int)$userId, array_map('intval', $onlineUserIds ?? [])) : false;

        $names = explode(' ', trim($userName));
        $first = substr($names[0] ?? 'D', 0, 1);
        $last  = substr(end($names) ?: 'U', 0, 1);
        $initials = strtoupper($first.$last);
    @endphp

    <div style="position:relative;width:50px;margin:auto;">

        @if ($photo)
            <img title="{{ $userName }}"
                src="{{ url('storage/'.$photo) }}"
                style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
        @else
            <div title="{{ $userName }}"
                style="width:50px;height:50px;border-radius:50%;background:#EBF4FF;color:black;display:flex;align-items:center;justify-content:center;font-size:16px;">
                {{ $initials }}
            </div>
        @endif

        <span style="
            position:absolute;
            bottom:1px;
            right:1px;
            width:12px;
            height:12px;
            border-radius:50%;
            background: {{ $isOnline ? '#31A24C' : '#C4C4C4' }};
            border:2px solid #FFFFFF;">
        </span>

    </div>
</div>

{{-- usage --}}

{{-- <x-user-profile-component :user="optional($item->users)" /> --}}
