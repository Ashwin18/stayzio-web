@if (count($hourlyPrices) > 0)
    @foreach ($hourlyPrices as $index => $hourlyPrice)
        @php
            $price = App\Models\BookingHour::find($hourlyPrice->hour_id);
        @endphp
        
        {{-- The javascript expects labels with 'bw-dt' and an 'on' class on the initially selected index --}}
        <label class="bw-dt {{ $index == 0 ? 'on' : '' }}">
            <input type="radio"
                   name="price"
                   id="radio_{{ $hourlyPrice->id }}"
                   value="{{ $hourlyPrice->id }}"
                   data-price="{{ $hourlyPrice->price }}"
                   data-hour-label="{{ optional($price)->hour ?? '' }}"
                   {{ $index == 0 ? 'checked' : '' }}
                   hidden>

            <span class="bw-dt-lbl">
                @if (optional($price)->hour == '24')
                    FULLDAY
                @else
                    {{ optional($price)->hour ?? '' }} {{ __('Hrs') }}
                @endif
            </span>

            <span class="bw-dt-pr">
                {{ symbolPrice($hourlyPrice->price) }}
            </span>
        </label>
    @endforeach
@else
    <h6 class="mt-2 text-warning ps-3 pb-2">{{ __('No booking slot available') }}</h6>
@endif