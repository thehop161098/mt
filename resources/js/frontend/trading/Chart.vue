<template>
    <div class="chartTrading__chart" ref="chartdiv">
    </div>
</template>

<script>
    import * as am4core from "@amcharts/amcharts4/core";
    import * as am4charts from "@amcharts/amcharts4/charts";
    import am4themes_dark from "@amcharts/amcharts4/themes/dark";

    am4core.useTheme(am4themes_dark);

    export default {
        name: 'Chart',
        props: ['second', 'candles', 'limit', 'arrEma', 'isMobile', 'limit_mobile'],
        mounted() {
            let chart = am4core.create(this.$refs.chartdiv, am4charts.XYChart);

            chart.paddingRight = 20;
            chart.dateFormatter.inputDateFormat = "yyyy-MM-dd HH:mm";
            chart.leftAxesContainer.layout = "vertical";

            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            dateAxis.renderer.grid.template.location = 0;
            dateAxis.renderer.fontSize = "0.5em";

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.tooltip.disabled = true;
            valueAxis.zIndex = 1;
            valueAxis.renderer.baseGrid.disabled = true;
            valueAxis.height = am4core.percent(150);
            valueAxis.renderer.fontSize = "0.5em";
            valueAxis.renderer.labels.template.padding(10);

            var series = chart.series.push(new am4charts.CandlestickSeries());
            series.dataFields.dateX = "date";
            series.dataFields.valueY = "close";
            series.dataFields.openValueY = "open";
            series.dataFields.lowValueY = "low";
            series.dataFields.highValueY = "high";
            series.tooltip.disabled = true;
            series.tooltipText = "Date:{dateX.value.formatDate('yyyy-MM-dd HH:mm')}\nOpen:${openValueY.value}\nLow:${lowValueY.value}\nHigh:${highValueY.value}\nClose:${valueY.value}";

            delete series.riseFromOpenState.properties.fill;
            delete series.dropFromOpenState.properties.fill;
            delete series.riseFromOpenState.properties.stroke;
            delete series.dropFromOpenState.properties.stroke;
            
            series.columns.template.adapter.add("fill", (fill, target) => {
                if (target.dataItem) {
                    const {open, close} = chart.data[target.dataItem.index];
                    if (open === close) {
                        return am4core.color("#E19049");
                    } else if (open < close) {
                        return am4core.color("#02C076");
                    } else {
                        return am4core.color("#D9304E");
                    }
                }
                return am4core.color("#000000");
            });
            
            series.columns.template.adapter.add("stroke", (fill, target) => {
                if (target.dataItem) {
                    const {open, close} = chart.data[target.dataItem.index];
                    if (open === close) {
                        return am4core.color("#E19049");
                    } else if (open < close) {
                        return am4core.color("#02C076");
                    } else {
                        return am4core.color("#D9304E");
                    }
                }
                return am4core.color("#000000");
            });
            
            chart.cursor = new am4charts.XYCursor();
            
            chart.data = [];
            
            let valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis2.tooltip.disabled = true;
            valueAxis2.height = am4core.percent(50);
            valueAxis2.zIndex = 3
            valueAxis2.marginTop = 30;
            valueAxis2.renderer.baseGrid.disabled = true;
            valueAxis2.renderer.inside = true;
            valueAxis2.renderer.labels.template.verticalCenter = "bottom";
            valueAxis2.renderer.fontSize = "0.5em"
            valueAxis2.renderer.labels.template.padding(20);
            
            let chartColumn = chart.series.push(new am4charts.ColumnSeries());
            chartColumn.dataFields.dateX = "date";
            chartColumn.clustered = false;
            chartColumn.dataFields.valueY = "close";
            chartColumn.yAxis = valueAxis2;
            chartColumn.tooltipText = "{valueY.value}";
            
            chartColumn.columns.template.adapter.add("fill", (fill, target) => {
                if (target.dataItem) {
                    const {open, close} = chart.data[target.dataItem.index];
                    if (open === close) {
                        return am4core.color("#E19049");
                    } else if (open < close) {
                        return am4core.color("#02C076");
                    } else {
                        return am4core.color("#D9304E");
                    }
                }
                return am4core.color("#000000");
            });
            
            chartColumn.columns.template.adapter.add("stroke", (fill, target) => {
                if (target.dataItem) {
                    const {open, close} = chart.data[target.dataItem.index];
                    if (open === close) {
                        return am4core.color("#E19049");
                    } else if (open < close) {
                        return am4core.color("#02C076");
                    } else {
                        return am4core.color("#D9304E");
                    }
                }
                return am4core.color("#000000");
            });

            let lineEma12 = chart.series.push(new am4charts.LineSeries());
            lineEma12.dataFields.dateX = "date";
            lineEma12.dataFields.valueY = "ema12";
            lineEma12.yAxis = valueAxis;
            lineEma12.fill = am4core.color("#fab700");
            lineEma12.stroke = am4core.color("#fab700");
            lineEma12.strokeWidth = 0.8;
            lineEma12.tensionX = 0.8;
            // lineEma12.tooltipText = "Ema 12: {valueY.value}";
            
            let lineEma26 = chart.series.push(new am4charts.LineSeries());
            lineEma26.dataFields.dateX = "date";
            lineEma26.dataFields.valueY = "ema26";
            lineEma26.yAxis = valueAxis;
            lineEma26.fill = am4core.color("#0ff225");
            lineEma26.stroke = am4core.color("#0ff225");
            lineEma26.strokeWidth = 0.8;
            lineEma26.tensionX = 0.8;
            // lineEma26.tooltipText = "Ema 26: {valueY.value}";

            let lineEma89 = chart.series.push(new am4charts.LineSeries());
            lineEma89.dataFields.dateX = "date";
            lineEma89.dataFields.valueY = "ema89";
            lineEma89.yAxis = valueAxis;
            lineEma89.fill = am4core.color("#fa0011");
            lineEma89.stroke = am4core.color("#fa0011");
            lineEma89.strokeWidth = 0.8;
            lineEma89.tensionX = 0.8;
            // lineEma89.tooltipText = "Ema 89: {valueY.value}";

            chart.cursor = new am4charts.XYCursor();

            let indicator = chart.tooltipContainer.createChild(am4core.Container);
            indicator.background.fill = am4core.color("#1b2a3b");
            indicator.width = am4core.percent(100);
            indicator.height = am4core.percent(100);

            var indicatorLabel = indicator.createChild(am4core.Label);
            indicatorLabel.text = "Loading...";
            indicatorLabel.align = "center";
            indicatorLabel.valign = "middle";

            chart.events.on("ready", function () {
                indicator.hide();
            });

            this.chart = chart;
            this.series = series;
            this.chartColumn = chartColumn;
        },
        beforeDestroy() {
            if (this.chart) {
                this.chart.dispose();
            }
        },
        watch: {
            candles: function (candles) {
                if (this.candles.length === 0) return;
                const candlesLimit = candles.slice(this.limit * (-1)).filter(candle => candle);

                const timePeriods12 = 12;
                const multiplier12 = 2 / (timePeriods12 + 1);
                const timePeriods26 = 26;
                const multiplier26 = 2 / (timePeriods26 + 1);
                const timePeriods89 = 89;
                const multiplier89 = 2 / (timePeriods89 + 1);
                
                if (this.chart.data && this.chart.data.length !== 0 && candlesLimit[0] &&
                    this.chart.data[0].coin === candlesLimit[0].coin) {
                    let arrEma12 = candlesLimit.slice(-14);
                    arrEma12.pop();
                    arrEma12.pop();
                    let arrEma26 = candlesLimit.slice(-28);
                    arrEma26.pop();
                    arrEma26.pop();
                    let arrEma89 = candlesLimit.slice(-89);
                    arrEma89.pop();
                    arrEma89.pop();
                    if (arrEma89.length !== 89) {
                        arrEma89 = this.arrEma.slice(arrEma89.length - 89).concat(arrEma89);
                    }
                    const lastCandle = {...candlesLimit[candlesLimit.length  - 1]};
                    const lastSecondCandle = {...candlesLimit[candlesLimit.length  - 2]};

                    const initSma12 = arrEma12.reduce((total, elm) => {
                        return total + elm.close;
                    }, 0) / timePeriods12;
                    const ema12 = (lastSecondCandle.close - initSma12) * multiplier12 + initSma12;
                    
                    const initSma26 = arrEma26.reduce((total, elm) => {
                        return total + elm.close;
                    }, 0) / timePeriods26;
                    const ema26 = (lastSecondCandle.close - initSma26) * multiplier26 + initSma26;

                    const initSma89 = arrEma89.reduce((total, elm) => {
                        return total + elm.close;
                    }, 0) / timePeriods89;
                    const ema89 = (lastSecondCandle.close - initSma89) * multiplier89 + initSma89;

                    this.chart.data[this.chart.data.length - 1].ema12 = +ema12.toFixed(2);
                    this.chart.data[this.chart.data.length - 1].ema26 = +ema26.toFixed(2);
                    this.chart.data[this.chart.data.length - 1].ema89 = +ema89.toFixed(2);

                    this.chart.invalidateRawData();
                    if (this.chart.data.length === this.limit) {
                        this.chart.addData({ ...lastCandle }, 1);
                    } else {
                        this.chart.addData({ ...lastCandle }, 1);
                    }

                    this.series.columns.each(function(column) {
                        column.fill = column.fill;
                        column.stroke = column.stroke;
                    })

                    this.chartColumn.columns.each(function(column) {
                        column.fill = column.fill;
                        column.stroke = column.stroke;
                    })

                    return;
                }

                let arrEma12 = this.arrEma.slice(-12);
                let arrEma26 = this.arrEma.slice(-26);
                let arrEma89 = this.arrEma.slice(-89);
                let dataChart = candlesLimit.map((candle) => {
                    const initSma12 = arrEma12.reduce((total, elm) => {
                        return total + elm.close;
                    }, 0) / timePeriods12;
                    const ema12 = (candle.close - initSma12) * multiplier12 + initSma12;
                    arrEma12.shift();
                    arrEma12.push({close: candle.close});

                    const initSma26 = arrEma26.reduce((total, elm) => {
                        return total + elm.close;
                    }, 0) / timePeriods26;
                    const ema26 = (candle.close - initSma26) * multiplier26 + initSma26;
                    arrEma26.shift();
                    arrEma26.push({close: candle.close});

                    const initSma89 = arrEma89.reduce((total, elm) => {
                        return total + elm.close;
                    }, 0) / timePeriods89;
                    const ema89 = (candle.close - initSma89) * multiplier89 + initSma89;
                    arrEma89.shift();
                    arrEma89.push({close: candle.close});
                   
                    return {
                        ...candle,
                        ema12: +ema12.toFixed(2),
                        ema26: +ema26.toFixed(2),
                        ema89: +ema89.toFixed(2)
                    };
                });

                if (this.isMobile) {
                    dataChart = dataChart.slice(this.limit_mobile * (-1));
                }
                this.chart.data = dataChart;
            },
            second: function () {
                if (this.candles.length === 0) return;
                const candlesLimit = this.candles.slice(this.limit * (-1)).filter(candle => candle);
                this.chart.data[this.chart.data.length - 1].close = candlesLimit[candlesLimit.length - 1].close;
                this.chart.data[this.chart.data.length - 1].low = candlesLimit[candlesLimit.length - 1].low;
                this.chart.data[this.chart.data.length - 1].high = candlesLimit[candlesLimit.length - 1].high;
                this.chart.invalidateRawData();
                this.series.columns.each(function(column) {
                    column.fill = column.fill;
                    column.stroke = column.stroke;
                })

                this.chartColumn.columns.each(function(column) {
                    column.fill = column.fill;
                    column.stroke = column.stroke;
                })
            }
        }
    }
</script>
