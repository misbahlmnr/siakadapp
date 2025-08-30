<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

type MateriProps = {
    id: number;
    judul_materi: string;
    pertemuan_ke: number;
    mata_pelajaran: string;
    kode_mapel: string;
    deskripsi: string;
    file_materi: string;
    link_materi: string;
    nama_guru: string;
    created_at: string;
};

const props = defineProps<{
    materi: MateriProps;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('siswa.dashboard') },
    { title: 'Materi Belajar', href: route('siswa.materi.index') },
    { title: 'Detail Materi', href: route('siswa.materi.show', props.materi.id) },
];

const page = usePage();
const user = computed(() => page.props.auth.user);

console.log(props.materi);
</script>

<template>
    <Head :title="`Materi - ${materi.judul_materi}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ materi.judul_materi }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Pertemuan {{ materi.pertemuan_ke }} - {{ materi.mata_pelajaran }}</p>
                </div>
                <span class="rounded bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                    {{ new Date(materi.created_at).toLocaleDateString('id-ID') }}
                </span>
            </div>

            <!-- Content -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Deskripsi Materi -->
                    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Deskripsi Materi</h2>
                        <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                            {{ materi.deskripsi }}
                        </p>
                    </div>

                    <!-- File Materi -->
                    <div v-if="materi.file_materi" class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">File Materi</h2>
                        <div class="flex items-center justify-between rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                            <div class="flex items-center space-x-3">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    ></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">File Materi.pdf</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Dokumen pembelajaran</p>
                                </div>
                            </div>
                            <a
                                :href="`/storage/${materi.file_materi}`"
                                target="_blank"
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                            >
                                Download
                            </a>
                        </div>
                    </div>

                    <!-- Link Materi -->
                    <div v-if="materi.link_materi" class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Link Referensi</h2>
                        <a
                            :href="materi.link_materi"
                            target="_blank"
                            class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700"
                        >
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                ></path>
                            </svg>
                            Buka Link
                        </a>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Informasi Guru -->
                    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Pengajar</h2>
                        <div class="flex items-center space-x-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                <span class="font-semibold text-blue-800 dark:text-blue-300">
                                    {{ materi.nama_guru.charAt(0) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ materi.nama_guru }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Guru {{ materi.mata_pelajaran }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Mata Pelajaran -->
                    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Informasi Pelajaran</h2>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Mata Pelajaran</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ materi.mata_pelajaran }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Kode Mapel</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ materi.kode_mapel }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Pertemuan</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white"> Ke-{{ materi.pertemuan_ke }} </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.leading-relaxed {
    line-height: 1.625;
}
</style>
