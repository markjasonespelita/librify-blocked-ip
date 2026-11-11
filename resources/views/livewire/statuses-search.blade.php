<div style="max-width:400px;">
    <input type="text" wire:model.live="search" placeholder="Search statuses..." class="form-control">
    @if($search && count($results))
        <ul class="mt-2 list-group">
            @foreach($results as $item)
                <li class="list-group-item">
                    <a href="{{ route('statuses.show', $item->id) }}" class="nav-link text-primary fw-bold">
                        {{ $item->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @elseif($search)
        <div class="mt-2 list-group">
            <div class="list-group-item text-muted">No results found</div>
        </div>
    @endif
</div>