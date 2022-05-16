<template>
    <div class="chartTrading__listResult">
        <div class="listResult__blockResult" v-for="histories in circleHistory">
            <div class="blockResult__item"
                v-bind:class="getClassResult(history.result)"
                v-for="history in histories"
            >
                <p class="item__code" v-if="history.round">{{history.round}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import {GREEN, RED, YELLOW} from '../../constants';
    import {getCirCleHistory} from "../services/candleService";

    export default {
        name: "History",
        props: ['lastCandle', 'coinSelected'],
        data: function () {
            return {
                histories: [],
                circleHistory: [],
                errors: []
            }
        },
        watch: {
            coinSelected: function (coinSelected) {
                let $this = this;
                getCirCleHistory(coinSelected.name).then(res => {
                    const circleHistory = [...res.circleHistory];
                    $this.histories = circleHistory;
                    $this.circleHistory = $this.formatCircleHistory(circleHistory);
                }).catch(e => {
                    $this.errors.push(e);
                });
            },
            lastCandle: function (lastCandle) {
                let circleHistory = [...this.histories];
                if (circleHistory.length === 0 || (circleHistory[circleHistory.length - 1]
                    && lastCandle && lastCandle.round !== circleHistory[circleHistory.length - 1].round)) {
                    const newResult = {result: this.getResult(lastCandle), round: lastCandle.round};
                    if (circleHistory.length === 100) circleHistory = [];
                    circleHistory.push(newResult);
                    this.histories = circleHistory;
                    this.circleHistory = this.formatCircleHistory();
                }
            }
        },
        methods: {
            getResult: function (candle) {
                if (candle.open > candle.close) return RED;
                if (candle.open < candle.close) return GREEN;
                if (candle.open === candle.close) return YELLOW;
            },
            getClassResult: function (result) {
                if (result === RED) return 'down';
                if (result === GREEN) return 'up';
                if (result === YELLOW) return 'balance';
            },
            formatCircleHistory: function () {
                const perChunk = 25;
                const result = this.histories.reduce((resultArray, item, index) => {
                    const chunkIndex = Math.floor(index / perChunk)

                    if (!resultArray[chunkIndex]) {
                        resultArray[chunkIndex] = [] // start a new chunk
                    }

                    resultArray[chunkIndex].push(item)

                    return resultArray
                }, []);
                let data = [];
                for (let i = 0; i < 4; i++) {
                    if (result[i]) {
                        data[i] = [...result[i]];
                        for (let j = 0; j < 25 - result[i].length; j++) {
                            data[i].push({result: "", round: ""});
                        }
                    } else {
                        data[i] = [];
                        for (let j = 0; j < 25; j++) {
                            data[i].push({result: "", round: ""});
                        }
                    }
                }
                return data;
            }
        }
    }
</script>
