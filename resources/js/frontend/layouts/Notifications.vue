<template>
    <div class="tool__noti">
        <input type="checkbox" class="noti__input" id="cbox4"/>
        <div class="noti__infoNoti">
            <img class="infoNoti__img" :src="'frontend/images/icons/icNoti.png'"/>
            <div class="infoNoti__count">
                <div class="count__number">{{notis.length > 99 ? '99+' : notis.length}}</div>
            </div>
        </div>
        <div class="noti__listNoti" v-if="notis.length > 0">
            <button class="headerNoti__readAll" @click="readAllNotifications()">
                <img class="readAll__img" :src="'frontend/images/icons/icCheckAll.png'"/>
            </button>
            <a v-bind:href="noti.route_url" v-for="noti in notis">
                <div class="listNoti__itemNoti">
                    <p class="itemNoti__text">{{noti.note}}</p>
                    <p class="itemNoti__time">{{noti.created_at | formatDate}}</p>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
    import {readAllNotifications} from "../services/historyWalletService";

    export default {
        name: "Notifications",
        props: ['notifications'],
        data: function () {
            return {
                notis: []
            }
        },
        created() {
            this.notis = [...this.notifications];
        },
        methods: {
            readAllNotifications: function () {
                readAllNotifications().then(res => {
                    if (res && res.success) {
                        this.notis = [];
                    }
                });
            }
        }
    }
</script>
