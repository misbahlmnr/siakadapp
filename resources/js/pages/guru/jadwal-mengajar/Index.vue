<script setup lang="ts">
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
];

onMounted(() => {
    useDataTable('#data-table', {
        ajax: route('guru.jadwal-mengajar.data'),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'kelas', name: 'kelas' },
            { data: 'hari', name: 'hari' },
            { data: 'jam', name: 'jam' },
        ],
        actions: (id, row) => {
            return `
                <button data-action="detail" data-id="${id}" title="Detail" class="text-blue-600 cursor-pointer btn-custom-outline hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg></button>
                <button data-action="materi" data-id="${id}" title="Materi" class="text-purple-600 cursor-pointer btn-custom-outline hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-icon lucide-book"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/></svg></button>
                <button data-action="tugas" data-id="${id}" title="Tugas" class="text-orange-600 cursor-pointer btn-custom-outline hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-pen-icon lucide-user-pen"><path d="M11.5 15H7a4 4 0 0 0-4 4v2"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="7" r="4"/></svg></button>
            `;
        },
        onAction: (action, id, row) => {
            if (action === 'materi') {
                router.visit(route('guru.jadwal-mengajar.materi.index', { jadwal_id: id }));
            }
            if (action === 'tugas') {
                router.visit(route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', { jadwal_id: id }));
            }
            if (action === 'detail') {
                router.visit(route('guru.jadwal-mengajar.show', { id }));
            }
        },
    });
});
</script>

<template>
    <Head title="Jadwal Mengajar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 bg-gray-50 p-6 dark:bg-[#0A0A0A]">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jadwal Mengajar</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Berikut adalah list jadwal mengajar</p>
                </div>
            </div>

            <!-- Table Section - Container styling now handled by DataTables CSS -->
            <div class="dt-container">
                <table id="data-table" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
