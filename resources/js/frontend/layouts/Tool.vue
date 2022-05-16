<template>
    <div class="tool__profile">
        <input type="checkbox" class="profile__input" id="cbox3"/>
        <div class="profile__infoProfile">
            <img class="infoProfile__img" :src="'frontend/images/icons/icProfile.svg'"/>
        </div>
        <div class="profile__listProfile">
            <div class="listProfile__nameUser">
                <p class="nameUser__text">{{fullName}}</p>
            </div>
            <div class="listProfile__listItem">
                <a v-bind:href="routeEdit" class="listItem__item">
                    <div class="item__text">Profile & Settings</div>
                </a>
                <a v-bind:href="routeLogout" class="listItem__item" @click.stop.prevent="logout">
                    <div class="item__text itemlo">Log out</div>
                </a>
                <form id="logout-form" v-bind:action="routeLogout" method="POST" class="d-none">
                    <input type="hidden" name="_token" v-model="csrf"/>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Tool",
        props: ['fullName', 'routeEdit', 'routeLogout', 'csrf'],
        methods: {
            logout: function () {
                document.getElementById('logout-form').submit();
            }
        }
    }
</script>
