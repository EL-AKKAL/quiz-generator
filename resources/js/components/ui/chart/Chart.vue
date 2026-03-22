<script setup lang="ts">
import { useColorMode } from "@vueuse/core";
import { onMounted, reactive, ref, watchEffect } from "vue";
import LoadingIndicator from "./LoadingIndicator.vue";
import EmptyStateIndicator from "./EmptyStateIndicator.vue";

const props = withDefaults(
    defineProps<{
        options: any;
        exportOptions?: any;
        series?: any;
        title: string;
        subtitle?: string;
        chartId: string;
        url?: string;
        formatSeriesUsing?: (data: any) => any;
        contained?: boolean;
        chartClasses?: string;
    }>(),
    {
        contained: false,
    },
);

const emit = defineEmits(["loaded"]);

const baseOptions = {
    exporting: {
        enabled: false,
    },
    title: {
        text: undefined,
    },
    credits: {
        enabled: false,
    },
    tooltip: {
        headerFormat: "",
        pointFormat: `<div class='text-muted-color-emphasis'>
            {point.name}: <b>{point.y}</b>
        </div>`,
    },
    xAxis: {
        type: "category",
        labels: {
            useHTML: true,
            format: `<div class='text-muted-color-emphasis text-nowrap'>
                {value}
            </div>`,
        },
        lineColor: "transparent",
    },
    legend: {
        className: "mt-3.5",
        enabled: true,
        align: "center",
        symbolHeight: 0,
        padding: 0,
        useHTML: true,
        labelFormat: `
               <div class='flex gap-1.5 items-center'>
        			<div style="background-color:{color};" class="size-3 rounded-full"></div>
        			<div class="text-muted-color-emphasis text-xs">{name}</div>
        		</div>
            `,
    },
};

const options = reactive<any>({
    ...baseOptions,
    ...props.options,
    series: props.series || [],
});

const chartRef = ref<any>(null);

watchEffect(() => {
    try {
        options.yAxis.gridLineColor =
            useColorMode().value === "dark"
                ? "var(--p-surface-700)"
                : "var(--p-surface-300)";
    } catch (e) { }
});

const isLoading = ref<boolean>(true);

onMounted(async () => {
    // if (!props.series) {
    //     isLoading.value = false;
    //     return;
    // }
    console.log(props.series);

    options.series = props.formatSeriesUsing
        ? props.formatSeriesUsing(props.series)
        : props.series;

    emit("loaded", props.series);

    isLoading.value = false;
});
</script>
<template>
    <div :id="chartId" :class="{
        card: !contained,
        'card !border-none !shadow-none': contained,
    }">
        <div class="flex justify-between items-center mb-4 gap-2">
            <div class="text-base font-semibold">{{ title }} <span class="text-sm font-light">{{ subtitle }}</span>
            </div>
        </div>

        <LoadingIndicator v-if="isLoading" />

        <EmptyStateIndicator v-else-if="!options.series?.length" />

        <div :class="'mt-8 ' + chartClasses" v-else>
            <highcharts ref="chartRef" :options />
        </div>
    </div>
</template>
