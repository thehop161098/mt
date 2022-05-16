<template>
    <div class="bet__blockAmount">
        <div class="blockAmount__form">
            <div class="form__boxInput">
                <p class="boxInput__label">Amount
                </p>
                <p class="boxInput__unit">$</p>
                <input class="boxInput__input" v-model="amountText"
                       autocomplete="off" inputmode="numeric" pattern="[0-9]*" />
            </div>
            <div class="form__boxSelectAmount">
                <button class="boxSelectAmount__item" @click="plusAmount(20)">
                    <p class="item__value">+20</p>
                </button>
                <button class="boxSelectAmount__item" @click="plusAmount(50)">
                    <p class="item__value">+50</p>
                </button>
                <button class="boxSelectAmount__item" @click="plusAmount(100)">
                    <p class="item__value">+100</p>
                </button>
                <button class="boxSelectAmount__item" @click="mulAmount(2)">
                    <p class="item__value">x2</p>
                </button>
                <button class="boxSelectAmount__item" @click="mulAmount(3)">
                    <p class="item__value">x3</p>
                </button>
                <button class="boxSelectAmount__item" @click="allAmount()">
                    <p class="item__value">ALL</p>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {HTTP} from '../../http-common';

    export default {
        name: "AmountBox",
        props: ['amount'],
        computed: {
            amountText: {
                get() {
                    return this.$props.amount
                },
                set(value) {
                    this.$emit('changeAmount', value)
                }
            }
        },
        methods: {
            plusAmount: function (amountPlus) {
                let amount = this.amount ? this.amount : 0;
                amount += amountPlus;
                this.$emit('changeAmount', amount);
            },
            mulAmount: function (amountMul) {
                let amount = this.amount ? this.amount : 1;
                amount *= amountMul;
                this.$emit('changeAmount', amount);
            },
            allAmount: function () {
                HTTP.get('walletGames/getAmountWalletGame').then(response => {
                    if (response.data && response.data.amount) {
                        const amount = response.data.amount;
                        this.$emit('changeAmount', amount);
                    }
                });
            }
        }
    }
</script>
