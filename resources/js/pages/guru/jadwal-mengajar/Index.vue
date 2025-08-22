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
                <button data-action="detail" data-id="${id}" class="text-green-500 cursor-pointer">Detail</button> |
                <button data-action="materi" data-id="${id}" class="text-purple-500 cursor-pointer">Materi</button> |
                <button data-action="tugas" data-id="${id}" class="text-orange-500 cursor-pointer">Tugas</button>
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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Jadwal Mengajar</h1>
            </div>
            <div>
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
