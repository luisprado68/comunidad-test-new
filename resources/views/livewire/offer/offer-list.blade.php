<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <h1>Offertas Disponibles</h1>
{{--    @dump($offers)--}}
{{--    @foreach($offers as $offer)--}}
{{--            {{$offer->viewers}}--}}
{{--        {{$offer->viewers}}--}}
{{--        {{$offer->viewers}}--}}
{{--    @endforeach--}}
    @livewire('datatable.offer-table')
</div>
