<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import $ from 'jquery';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
];

onMounted(() => {
    $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        pagingType: 'simple_numbers',
        ajax: route('guru.jadwal-mengajar.data'),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'hari', name: 'hari' },
            { data: 'waktu', name: 'waktu' },
            { data: 'nama_kelas', name: 'nama_kelas', searchable: true },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                width: '20%',
                className: 'flex items-center justify-center',
                render: (data) => {
                    return `
                        <button class="btn-detail text-green-500 cursor-pointer" data-id="${data}">Detail</button>
                    `;
                },
            },
        ],
        drawCallback: function () {
            $('.btn-detail').on('click', function () {
                const id = $(this).data('id');
                router.visit(route('guru.jadwal-mengajar.show', id));
            });
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
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
