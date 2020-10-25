<h2 class="text-center p-4 w-100"  wire:loading 
    @isset($target)
        wire:target="{{ $target }}"
    @endisset
    >
        {{ __('timy::titles.loading') }}...
</h2>