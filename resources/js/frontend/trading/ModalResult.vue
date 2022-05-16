<template>
    <div class="modal fade in" tabindex="-1" role="dialog" style="display: block;">
        <div
            class="modal-dialog"
            v-bind:class="getClassModal()"
            role="document"
        >
            <div class="modalWin__boxContent">
                <div class="boxContent__form">
                    <img class="form__video"
                           :src="getSrc()"
                    />
                    <div class="form__info">
                        <p class="info__result">{{profit >= 0 ? getTextWin() : 'You Lose'}}</p>
                        <p class="info__moneyCount">{{profit >= 0 ? '+ $' : '- $'}}{{getAbsProfit() |
                            formatNumber}}</p>
                        <div class="info__btn">
                            <button class="btn__close" @click="resetProfit">Play Continue !</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ModalResult",
        props: ['profit', 'isYellow', 'prize'],
        methods: {
            resetProfit: function () {
                this.$emit('resetProfit');
            },
            getClassModal: function () {
                return {
                    'modalBalance': this.isYellow,
                    'modalWin': this.profit >= 0 && !this.isYellow,
                    'modalLose': this.profit < 0 && !this.isYellow
                };
            },
            getSrc: function () {
                if (this.isYellow) return 'frontend/video/balance.svg';
                if (this.profit >= 0) return 'frontend/video/win.svg';
                return 'frontend/video/lose.svg';
            },
            getAbsProfit: function () {
                return this.profit ? Math.abs(this.profit) : 0;
            },
            getTextWin: function () {
                if (this.prize !== 1) return 'Win x ' + this.prize;
                return 'You Win';
            }
        }
    }
</script>
