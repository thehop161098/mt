<template>
    <div class="blockSelectBet__listSelect">
        <div class="listSelect__time">
            <p class="time__label" v-if="second > 29">Waiting for result</p>
            <p class="time__label" v-else>Time Remaining</p>
            <p class="time__number" v-if="second !== ''">{{second > 29 ? 60 - second : 30 - second}}</p>
        </div>
        <div class="listSelect__listBtn">
            <div class="listBtn__item high"
                 v-bind:class="{'disabled': second === '' || second > 29}"
                 @click="order(GREEN)"
            >
                <p class="item__bet">Buy</p>
            </div>
            <div class="listBtn__item balance"
                 v-bind:class="{'disabled': second === '' || second > 29}"
                 @click="order(YELLOW)"
            >
                <p class="item__bet">Balance x7</p>
            </div>
            <div class="listBtn__item low"
                 v-bind:class="{'disabled': second === '' || second > 29}"
                 @click="order(RED)"
            >
                <p class="item__bet">Sell</p>
            </div>
        </div>
    </div>
</template>

<script>
    import {GREEN, RED, YELLOW} from '../../constants';
    import {HTTP} from '../../http-common';

    export default {
        name: "ButtonBox",
        props: ['second', 'amount', 'coinSelected', 'walletSelected'],
        data() {
            return {
                GREEN,
                RED,
                YELLOW,
                isOrder: false
            }
        },
        methods: {
            order: function (type) {
                let $this = this;
                if ($this.second < 30) {
                    if ($this.amount < 1) {
                        toastr.error('Minimum $1');
                        return;
                    }
                    if (!$this.isOrder) {
                        $this.isOrder = true;
                        HTTP.post(
                            `orders/order/${$this.coinSelected.name}/${$this.walletSelected.type}/${type}`,
                            {amount: $this.amount}
                        ).then(response => {
                            if (response.data && response.data) {
                                toastr.success(response.data.message);
                            }
                            $this.isOrder = false;
                        }).catch(error => {
                            if (error.response && error.response.data) {
                                toastr.error(error.response.data.message);
                            }
                            $this.isOrder = false;
                        });
                    }
                }
            }
        }
    }
</script>
