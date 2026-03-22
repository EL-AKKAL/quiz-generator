export default {
    chart: {
        backgroundColor: "transparent",
        type: "column",
        height: 350,
    },
    legend: {
        enabled: true,
        useHTML: true,
        labelFormat: `<div class='!text-muted-color-emphasis' style="text-align: left;">{name}</div>`,
    },
    yAxis: {
        title: {
            enabled: false,
        },
        min: 0,
        max: 100,
        gridLineWidth: 2,
        className: "text-muted-color-emphasis",
        tickInterval: 5,
        allowDecimals: false,
        labels: {
            useHTML: true,
            format: `<div class='!text-muted-color-emphasis'>
                {value}
            </div>`,
        },
    },
    xAxis: {
        type: "category",
        lineColor: "var(--p-content-border-color)",
        categories: [],
        gridLineWidth: 1,
        className: "text-muted-color-emphasis",
        labels: {
            align: "left",
            reserveSpace: true,
            useHTML: true,
            style: {
                textAlign: "center",
                whiteSpace: "nowrap",
            },
            format: `<div class='!text-muted-color-emphasis text-wrap line-clamp-2  !text-xs'>
                        {value}
                    </div>`,
        },
    },
    plotOptions: {
        series: {
            dataLabels: {
                enabled: false,
            },
            pointStart: 0,
            stacking: "normal",
            pointWidth: 27,
        },
        bar: {
            clip: false,
            borderWidth: 0,
        },
    },
    tooltip: {
        formatter: function (this: any): string {
            return `<div class='text-muted-color-emphasis'>
                ${this.series.name}: <b>${this.y}</b>
            </div>`;
        },
    },
};
