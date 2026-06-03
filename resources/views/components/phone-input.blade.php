@props([
    'label' => 'Teléfono',
    'countryName' => 'telefono_country',
    'numberName' => 'telefono_national',
    'value' => '',
    'required' => false,
    'dark' => false,
])

@php
    $split = \App\Support\PhoneNumber::split($value);
    $selectedCountry = old($countryName, $split['country']);
    $numberValue = old($numberName, $split['number']);
    $countries = config('phone.countries', []);
    $baseField = $dark
        ? 'border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]'
        : 'border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold text-[#111111] outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20';
@endphp

<div>
    <label class="{{ $dark ? 'text-[10px] font-black uppercase tracking-widest text-[#737373]' : 'block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]' }}">
        {{ $label }}
    </label>

    <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-[minmax(160px,0.42fr)_minmax(0,1fr)]">
        <select name="{{ $countryName }}" @required($required)
            autocomplete="tel-country-code" aria-label="Código de país"
            class="h-12 w-full min-w-0 appearance-auto rounded-2xl border leading-5 {{ $baseField }}">
            @foreach($countries as $code => $country)
                <option value="{{ $code }}" @selected($selectedCountry === $code)>
                    {{ $country['name'] }} ({{ $country['dial'] }})
                </option>
            @endforeach
        </select>

        <input type="tel" name="{{ $numberName }}" value="{{ $numberValue }}" @required($required)
            autocomplete="tel-national" inputmode="tel" placeholder="Número local"
            class="h-12 w-full min-w-0 rounded-2xl border leading-5 {{ $baseField }}">
    </div>

    @error($countryName)<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
    @error($numberName)<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
</div>
