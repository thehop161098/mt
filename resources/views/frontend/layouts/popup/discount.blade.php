@if(isset($ads) && !empty($ads))
    <div class="slideAds">
        <div class="btnCloseSlide">
            <img class="imgCloseSlide" src="{{ asset('frontend/images/icons/icCloseSlide.svg') }}"/>
        </div>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($ads as $row)
                    <div class="swiper-slide"
                         style="background-image : url({{$row['image_url']}})"></div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
@endif
