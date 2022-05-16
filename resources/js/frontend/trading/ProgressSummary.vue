<template>
    <div class="blockSelectBet__infoTrans">
        <div class="infoTrans__progressBar">
            <div class="progressBar__progress">
                <div class="progress__high" v-bind:style="{width: getPercent(GREEN, true) + '%'}">
                    <p class="progress__percent">{{getPercent(GREEN)}}%</p>
                </div>
                <div class="progress__balance" v-bind:style="{width: getPercent(YELLOW, true) + '%'}">
                    <p class="progress__percent">{{getPercent(YELLOW)}}%</p>
                </div>
                <div class="progress__low" v-bind:style="{width: getPercent(RED, true) + '%'}">
                    <p class="progress__percent">{{getPercent(RED)}}%</p>
                </div>
            </div>
        </div>
        <div class="infoTrans__profit">
            <p class="profit__text">Profit : 95%</p>
            <p class="profit__number">+${{ amount * 1.95 | formatNumber }}</p>
        </div>
        <div class="infoTrans__profit">
            <p class="profit__text">Balance x (3-7)</p>
            <p class="profit__number">+ ${{ (amount * 0.95 * 3 + amount) | formatNumber }} - ${{ (amount * 0.95 * 7 +
                amount) |
                formatNumber }}</p>
        </div>
    </div>

</template>

<script>
    import {GREEN, RED, YELLOW} from '../../constants';

    export default {
        name: "ProgressSummary",
        props: ['amount', 'coinSelected'],
        data: function () {
            return {
                GREEN: GREEN,
                RED: RED,
                YELLOW: YELLOW,
                total: 0,
                progress: {}
            }
        },
        created() {
            let $this = this;
            window.Echo.channel('progress')
                .listen('ProgressEvent', function (res) {
                    if (res.arrProgress && $this.coinSelected && res.arrProgress[$this.coinSelected.name] && res.arrProgress[$this.coinSelected.name].length > 0) {
                        const second = res.second;
                        let progress = {
                            [RED]: 0,
                            [GREEN]: 0,
                            [YELLOW]: 0
                        };
                        if (second < 30) {
                            progress = res.arrProgress[$this.coinSelected.name][second];
                        } else {
                            progress = res.arrProgress[$this.coinSelected.name][res.arrProgress[$this.coinSelected.name].length - 1];
                        }

                        let total = 0;
                        for (const type in progress) {
                            if (progress.hasOwnProperty(type)) {
                                if (progress[type]) total += progress[type];
                            }
                        }

                        if (total === 0) {
                            progress = {
                                [RED]: 33,
                                [GREEN]: 33,
                                [YELLOW]: 33
                            };
                        } else {
                            progress = {
                                [RED]: parseInt((progress[RED] * 100 / total)),
                                [GREEN]: parseInt((progress[GREEN] * 100 / total)),
                                [YELLOW]: parseInt((progress[YELLOW] * 100 / total))
                            };
                        }
                        $this.total = total;
                        $this.progress = progress;
                    }
                });
        },
        methods: {
            getPercent: function (type, is_css = false) {
                if (this.total === 0) {
                    return is_css ? 33 : 0;
                } else {
                    return this.progress[type]
                }
            }
        }
    }
</script>
