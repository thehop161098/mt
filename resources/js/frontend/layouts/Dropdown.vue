<template>
    <div class="header__tool">
        <div class="tool__luckyWheel">
            <img
                class="luckyWheel__btnShow"
                :src="'frontend/images/icons/spinButton.png'"
            />
        </div>
        <coins />
        <wallets />
        <notifications :notifications="JSON.parse(notifications)" />
        <tool
            :fullName="fullName"
            :routeEdit="routeEdit"
            :routeLogout="routeLogout"
            :csrf="csrf"
        />
    </div>
</template>

<script>
import Wallets from "./Wallets";
import Coins from "./Coins";
import GoldCoins from "./GoldCoins";
import Tool from "./Tool";
import Notifications from "./Notifications";

export default {
    name: "Dropdown",
    props: ["fullName", "routeEdit", "routeLogout", "csrf", "notifications"],
    components: {
        Notifications,
        Wallets,
        GoldCoins,
        Coins,
        Tool
    }
};
</script>
