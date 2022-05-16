<div class="tool__changeCoin">
    <input type="checkbox" class="changeCoin__input" id="cbox1" onclick="selectOnlyCbox(this.id)"/>
    @if (config('coins', NULL))
        @foreach (json_decode(config('coins'), true) as $coin)
            <div class="changeCoin__coinSelect">
                <div class="coinSelect__infoCoin">
                    <img class="infoCoin__img" src="{{ $coin['image']  }}"/>
                    <p class="infoCoin__text">{{ $coin['alias']  }}</p>
                </div>
                <img class="coin__showList" src="{{asset('frontend/images/icons/icDropdown.png')}}"/>
            </div>
        @endforeach
    @endif
</div>
