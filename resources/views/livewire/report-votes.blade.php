<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <a  wire:click.prevent="vote(1)" href="#"> Upvote</a> 
    {{ $report->votes }}
    <a  wire:click.prevent="vote(-1)" href="#"> Downvote</a>
    
</div>
