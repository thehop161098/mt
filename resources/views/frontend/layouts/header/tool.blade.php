<div class="tool__profile">
    <input type="checkbox" class="profile__input" id="cbox3"/>
    <div class="profile__infoProfile">
        <img class="infoProfile__img" src="{{asset('frontend/images/icons/icProfile.svg')}}"/>
    </div>
    <div class="profile__listProfile">
        <div class="listProfile__nameUser">
            <p class="nameUser__text">{{Auth::user()->full_name}}</p>
        </div>
        <div class="listProfile__listItem">
            <a href="{{route('user.edit')}}" class="listItem__item">
                <div class="item__text">Profile & Settings</div>
            </a>
            <a href="{{ route('logout') }}" class="listItem__item" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();
                                            ">
                <div class="item__text itemlo">Log out</div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
