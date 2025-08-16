<script setup lang="ts">
import { ArcElement, Chart as ChartJS, Legend, Title, Tooltip } from 'chart.js';
import { Doughnut } from 'vue-chartjs';

// Registrasi komponen chart
ChartJS.register(Title, Tooltip, Legend, ArcElement);

defineProps<{
    labels: string[];
    data: number[];
}>();

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'bottom' as const,
            labels: {
                color: '#9ca3af', // warna teks legend (abu-abu)
            },
        },
        title: {
            display: true,
            text: 'Distribusi Status Adaptive Learning',
            color: '#111827', // warna judul
            font: {
                size: 16,
            },
        },
    },
};
</script>

<template>
    <Doughnut
        :data="{
            labels,
            datasets: [
                {
                    backgroundColor: ['#FBBF24', '#3B82F6', '#10B981'], // kuning, biru, hijau
                    data,
                },
            ],
        }"
        :options="chartOptions"
    />
</template>
