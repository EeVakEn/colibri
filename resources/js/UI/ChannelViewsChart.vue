<template>
    <div class="chart-container">
        <bar-chart v-bind="barChartProps" />
    </div>
</template>

<script setup>
import { Chart, registerables } from 'chart.js';
import { BarChart, useBarChart } from 'vue-chart-3';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import moment from 'moment/moment';

Chart.register(...registerables);

const chartData = ref({
    labels: [],
    datasets: []
});

const chartOptions = {
    responsive: true,
    scales: {
        x: {
            type: 'category',
            title: {
                display: true,
                text: 'Date'
            }
        },
        y: {
            title: {
                display: true,
                text: 'Views'
            }
        }
    }
};

const { barChartProps, barChartRef } = useBarChart({
    chartData: computed(() => chartData.value),
    options: chartOptions
});

onMounted(async () => {
    try {
        const response = await axios.get(route('views.getViewsByChannel'));
        const data = response.data;

        const startDate = moment().subtract(15, 'day');
        const endDate = moment();
        const allDates = generateDates(startDate, endDate);

        const labels = allDates.map(date => date.format('MMM Do'));

        const datasets = data.map(channel => ({
            label: channel.label,
            data: allDates.map(date => {
                const view = channel.data.find(v => v.x === date.format('YYYY-MM-DD'));
                return view ? view.y : 0;
            }),
            backgroundColor: getRandomColor(),
            borderColor: getRandomColor(),
            borderWidth: 1
        }));

        chartData.value = {
            labels: labels,
            datasets: datasets
        };
    } catch (error) {
        console.error('Error fetching view data:', error);
    }
});

function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function generateDates(start, end) {
    const dates = [];
    let currentDate = start.clone();
    while (currentDate.isSameOrBefore(end)) {
        dates.push(currentDate.clone());
        currentDate.add(1, 'day');
    }
    return dates;
}
</script>

<style scoped>
.chart-container {
    width: 100%;
    height: 400px;
}
</style>
