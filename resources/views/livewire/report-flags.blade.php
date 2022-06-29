<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <a  wire:click.prevent="flag(1)" href="#"> Flag as inappropriate</a> 
    {{ $report->flags}}
</div>
