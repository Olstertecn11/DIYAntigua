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
    $themeClass = $dark ? 'phone-input-group-dark' : 'phone-input-group-light';
@endphp

@once
    <style>
        .phone-input-group {
            width: 100%;
        }

        .phone-input-fields {
            display: grid;
            grid-template-columns: 1fr;
            gap: .65rem;
            width: 100%;
            margin-top: .5rem;
        }

        .phone-input-field {
            width: 100%;
            min-width: 0;
            height: 48px;
            border-radius: 1rem;
            border: 1px solid rgba(0, 0, 0, .10);
            background: #fbfaf7;
            color: #111111;
            padding: 0 1rem;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.2;
            outline: none;
            box-shadow: none;
            -webkit-tap-highlight-color: transparent;
        }

        .phone-country-picker {
            position: relative;
            width: 100%;
        }

        .phone-country-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            text-align: left;
            cursor: pointer;
        }

        .phone-country-button span {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .phone-country-button i {
            flex-shrink: 0;
            color: #6b7280;
            font-size: .85rem;
        }

        .phone-country-menu {
            position: absolute;
            left: 0;
            right: 0;
            top: calc(100% + .4rem);
            z-index: 100000;
            max-height: min(320px, 58vh);
            overflow-y: auto;
            padding: .35rem;
            border-radius: 1rem;
            border: 1px solid rgba(0, 0, 0, .12);
            background: #ffffff;
            box-shadow: 0 24px 70px rgba(0, 0, 0, .18);
        }

        .phone-country-menu[hidden] {
            display: none;
        }

        .phone-country-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            width: 100%;
            border: 0;
            border-radius: .8rem;
            background: transparent;
            color: #111111;
            padding: .8rem .9rem;
            font-size: 14px;
            font-weight: 800;
            text-align: left;
        }

        .phone-country-option:hover,
        .phone-country-option:focus {
            background: rgba(252, 202, 0, .16);
            outline: none;
        }

        .phone-country-option.is-selected {
            background: #fcca00;
        }

        .phone-country-dial {
            flex-shrink: 0;
            color: #4b5563;
        }

        .phone-input-field:focus {
            border-color: #fcca00;
            box-shadow: 0 0 0 .25rem rgba(252, 202, 0, .20);
        }

        .phone-input-field::placeholder {
            color: #9ca3af;
            opacity: 1;
        }

        .phone-input-group-dark .phone-input-field {
            border-color: rgba(255, 255, 255, .10);
            background: #000000;
            color: #ffffff;
        }

        .phone-input-group-dark .phone-country-menu {
            border-color: rgba(255, 255, 255, .12);
            background: #111111;
        }

        .phone-input-group-dark .phone-country-option {
            color: #ffffff;
        }

        .phone-input-group-dark .phone-country-dial,
        .phone-input-group-dark .phone-country-button i {
            color: rgba(255, 255, 255, .64);
        }

        .phone-input-group-dark .phone-input-field::placeholder {
            color: rgba(255, 255, 255, .55);
        }
    </style>

@endonce

<div class="phone-input-group {{ $themeClass }}">
    <label class="{{ $dark ? 'text-[10px] font-black uppercase tracking-widest text-[#737373]' : 'block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]' }}">
        {{ $label }}
    </label>

    <div class="phone-input-fields">
        <div class="phone-country-picker" data-phone-picker>
            <input type="hidden" name="{{ $countryName }}" value="{{ $selectedCountry }}" data-phone-value>

            <button type="button" class="phone-input-field phone-country-button" data-phone-toggle
                aria-label="Seleccionar código de país">
                <span data-phone-label>
                    {{ $countries[$selectedCountry]['name'] ?? 'Guatemala' }}
                    ({{ $countries[$selectedCountry]['dial'] ?? '+502' }})
                </span>
                <i class="fas fa-chevron-down"></i>
            </button>

            <div class="phone-country-menu" data-phone-menu hidden>
            @foreach($countries as $code => $country)
                <button type="button"
                    class="phone-country-option {{ $selectedCountry === $code ? 'is-selected' : '' }}"
                    data-phone-option="{{ $code }}"
                    data-phone-label="{{ $country['name'] }} ({{ $country['dial'] }})">
                    <span>{{ $country['name'] }}</span>
                    <span class="phone-country-dial">{{ $country['dial'] }}</span>
                </button>
            @endforeach
            </div>
        </div>

        <input type="tel" name="{{ $numberName }}" value="{{ $numberValue }}" @required($required)
            autocomplete="tel-national" inputmode="tel" placeholder="Número local"
            class="phone-input-field">
    </div>

    @error($countryName)<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
    @error($numberName)<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
</div>

<script>
    window.phoneInputToggleCountryMenu = function(button) {
        const picker = button.closest('[data-phone-picker]');
        const menu = picker?.querySelector('[data-phone-menu]');
        const card = button.closest('.form-section-card');

        if (!menu) {
            return;
        }

        const willOpen = menu.hidden;
        document.querySelectorAll('[data-phone-menu]').forEach(openMenu => {
            openMenu.hidden = true;
            openMenu.closest('.form-section-card')?.classList.remove('phone-menu-open');
        });
        menu.hidden = !willOpen;
        card?.classList.toggle('phone-menu-open', willOpen);
    };

    window.phoneInputSelectCountry = function(button) {
        const picker = button.closest('[data-phone-picker]');
        const input = picker?.querySelector('[data-phone-value]');
        const label = picker?.querySelector('[data-phone-label]');
        const menu = picker?.querySelector('[data-phone-menu]');

        if (input) {
            input.value = button.dataset.phoneOption;
        }

        if (label) {
            label.textContent = button.dataset.phoneLabel;
        }

        picker?.querySelectorAll('[data-phone-option]').forEach(option => {
            option.classList.toggle('is-selected', option === button);
        });

        if (menu) {
            menu.hidden = true;
        }

        picker?.closest('.form-section-card')?.classList.remove('phone-menu-open');
    };

    if (!window.__phoneInputEventsBound) {
        window.__phoneInputEventsBound = true;

        document.addEventListener('click', function(event) {
            const toggle = event.target.closest('[data-phone-toggle]');
            if (toggle) {
                event.preventDefault();
                window.phoneInputToggleCountryMenu(toggle);
                return;
            }

            const option = event.target.closest('[data-phone-option]');
            if (option) {
                event.preventDefault();
                window.phoneInputSelectCountry(option);
                return;
            }

            if (event.target.closest('[data-phone-picker]')) {
                return;
            }

            document.querySelectorAll('[data-phone-menu]').forEach(menu => {
                menu.hidden = true;
                menu.closest('.form-section-card')?.classList.remove('phone-menu-open');
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key !== 'Escape') {
                return;
            }

            document.querySelectorAll('[data-phone-menu]').forEach(menu => {
                menu.hidden = true;
                menu.closest('.form-section-card')?.classList.remove('phone-menu-open');
            });
        });
    }
</script>
