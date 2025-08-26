<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    jadwal_id: string;
    evaluasi_id: string;
    submission: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
    { title: 'Evaluasi Pembelajaran', href: route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', { jadwal_id: props.jadwal_id }) },
    {
        title: 'Detail Evaluasi',
        href: route('guru.jadwal-mengajar.evaluasi-pembelajaran.show', { jadwal_id: props.jadwal_id, evaluasi_id: props.evaluasi_id }),
    },
    {
        title: 'Detail Pengumpul Tugas',
        href: route('guru.jadwal-mengajar.evaluasi-pembelajaran.pengumpulan-tugas.show', {
            jadwal_id: props.jadwal_id,
            evaluasi_id: props.evaluasi_id,
            pengumpulan_id: props.submission.id,
        }),
    },
];
</script>

<template>
    <Head title="Detail Pengumpulan Tugas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 px-10 py-6">
            <!-- Header -->
            <div class="rounded-lg border bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
                <h1 class="mb-4 text-2xl font-bold text-gray-800 dark:text-gray-200">Detail Pengumpulan Tugas</h1>
            </div>

            <!-- Content -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-6 text-xl font-bold text-gray-800 dark:text-gray-200">Informasi Pengumpulan</h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Siswa</h3>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ props.submission.siswa }}</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Tugas</h3>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ props.submission.nama_tugas }}</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pengumpulan</h3>
                        <span
                            class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900 dark:text-green-200"
                        >
                            Terkumpul
                        </span>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Jawaban Siswa</h3>

                    <div class="space-y-4">
                        <div
                            v-if="props.submission.file_jawaban"
                            class="flex items-center justify-between rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-700 dark:bg-blue-900/20"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">File Jawaban</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Klik untuk mengunduh</p>
                                </div>
                            </div>
                            <a
                                :href="props.submission.file_jawaban"
                                target="_blank"
                                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700"
                            >
                                Download
                            </a>
                        </div>

                        <div
                            v-if="props.submission.link_jawaban"
                            class="flex items-center justify-between rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-700 dark:bg-green-900/20"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-500">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">Link Jawaban</p>
                                    <p class="max-w-xs truncate text-sm text-gray-500 dark:text-gray-400">{{ props.submission.link_jawaban }}</p>
                                </div>
                            </div>
                            <a
                                :href="props.submission.link_jawaban"
                                target="_blank"
                                class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-white transition-colors hover:bg-green-700"
                            >
                                Buka Link
                            </a>
                        </div>

                        <div
                            v-if="!props.submission.file_jawaban && !props.submission.link_jawaban"
                            class="py-8 text-center text-gray-400 dark:text-gray-500"
                        >
                            <svg class="mx-auto mb-3 h-12 w-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                ></path>
                            </svg>
                            <p>Tidak ada jawaban yang dikumpulkan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
