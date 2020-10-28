<div class="card" style=" height: 135px; overflow-y: auto;">
    <div {{ $attributes->merge(['class' => 'card-body flex-column-'.$class]) }}>
        <h1 class="font-weight-bold">{{ number_format($number, 2) }}</h1>
        <p title="{{ $tooltip ?? '' }}">{{ $title }}</p>
    </div>
</div>