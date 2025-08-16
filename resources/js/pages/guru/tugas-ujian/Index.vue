<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import $ from 'jquery';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Tugas & Ujian', href: route('guru.tugas-ujian.index') },
];

onMounted(() => {
    $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        pagingType: 'simple_numbers',
        ajax: route('guru.tugas-ujian.data'),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'judul', name: 'judul' },
            { data: 'jenis', name: 'jenis' },
            { data: 'kelas', name: 'kelas' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'deadline', name: 'deadline' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                width: '20%',
                className: 'flex items-center justify-center gap-1',
                render: (data) => {
                    return `
                        <button class="btn-detail text-green-500 cursor-pointer" data-id="${data}">Detail</button> |
                        <button class="btn-nilai text-blue-500 cursor-pointer" data-id="${data}">Nilai</button>
                    `;
                },
            },
        ],
        drawCallback: function () {
            $('.btn-detail').on('click', function () {
                const id = $(this).data('id');
                router.visit(route('guru.tugas-ujian.show', id));
            });

            $('.btn-nilai').on('click', function () {
                const id = $(this).data('id');
                router.visit(route('guru.tugas-ujian.nilai', id));
            });
        },
    });
});
</script>

<template>
    <Head title="Tugas & Ujian" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Tugas & Ujian</h1>
                <Button class="rounded bg-blue-800 px-4 py-2 text-white hover:bg-blue-700" @click="router.visit(route('guru.tugas-ujian.create'))">
                    Tambah Tugas/Ujian
                </Button>
            </div>
            <div>
                <table id="data-table" class="display w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Jenis</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Deadline</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
