<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    grades: Array<{
        id: number;
        nilai: number;
        status: string;
        feedback: string;
        tanggal_dinilai: string;
        evaluasi_pembelajaran?: any;
    }>;
}>();

// Computed properties for summary
const averageGrade = computed(() => {
    if (props.grades.length === 0) return 0;
    const sum = props.grades.reduce((total, grade) => total + grade.nilai, 0);
    return (sum / props.grades.length).toFixed(2);
});

const highestGrade = computed(() => {
    if (props.grades.length === 0) return 0;
    return Math.max(...props.grades.map((grade) => grade.nilai));
});

// Helper methods
const getGradeColor = (grade: number) => {
    if (grade >= 85) return 'bg-green-100 text-green-800';
    if (grade >= 70) return 'bg-yellow-100 text-yellow-800';
    if (grade >= 60) return 'bg-orange-100 text-orange-800';
    return 'bg-red-100 text-red-800';
};

const getStatusColor = (status: string) => {
    switch (status?.toLowerCase()) {
        case 'lulus':
        case 'passed':
            return 'bg-green-100 text-green-800';
        case 'gagal':
        case 'failed':
            return 'bg-red-100 text-red-800';
        case 'remedial':
            return 'bg-yellow-100 text-yellow-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const formatDate = (dateString: string) => {
    if (!dateString) return 'Tidak ada tanggal';
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Nilai Siswa" />

        <div class="w-full space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Nilai</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Daftar nilai Anda di semester ini</p>
                </div>
            </div>
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-lg border bg-card p-6 text-card-foreground shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="rounded-full bg-primary/10 p-3">
                            <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Jumlah Evaluasi</p>
                            <p class="text-2xl font-bold">{{ grades.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card p-6 text-card-foreground shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="rounded-full bg-green-500/10 p-3">
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Rata-rata Nilai</p>
                            <p class="text-2xl font-bold">{{ averageGrade }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card p-6 text-card-foreground shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="rounded-full bg-purple-500/10 p-3">
                            <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Nilai Tertinggi</p>
                            <p class="text-2xl font-bold">{{ highestGrade }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades Table -->
            <div class="overflow-hidden rounded-lg border bg-white shadow dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Daftar Nilai</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                    Mata Pelajaran
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                    Jenis Evaluasi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                    Nilai
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                    Feedback
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="grade in grades" :key="grade.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ grade.evaluasi_pembelajaran?.jadwal?.mata_pelajaran?.nama_mapel ?? 'Tidak diketahui' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ grade.evaluasi_pembelajaran?.jenis ?? 'Tidak diketahui' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="rounded-full px-2 py-1 text-xs font-semibold" :class="getGradeColor(grade.nilai)">
                                        {{ grade.nilai }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="rounded-full px-2 py-1 text-xs font-semibold" :class="getStatusColor(grade.status)">
                                        {{ grade.status || 'Tidak ada status' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-600 dark:text-gray-400">
                                    {{ formatDate(grade.tanggal_dinilai) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ grade.feedback || 'Tidak ada feedback' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="grades.length === 0" class="py-12 text-center">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada nilai</h3>
                <p class="mt-1 text-sm text-gray-500">Nilai akan muncul di sini setelah guru memberikan evaluasi.</p>
            </div>
        </div>
    </AppLayout>
</template>
