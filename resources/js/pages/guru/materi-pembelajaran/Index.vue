<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import $ from 'jquery';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { onMounted } from 'vue';

const props = defineProps<{
    jadwal_id: number;
    nama_mapel: string;
    materi: {
        id: number;
        pertemuan_ke: number;
        judul: string;
        deskripsi: string;
        file_path: string;
        link_file: string | null;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
    { title: `Materi Pembelajaran`, href: route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.jadwal_id }) },
];

onMounted(() => {
    const table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        pagingType: 'simple_numbers',
        ajax: route('guru.jadwal-mengajar.materi.data', { jadwal_id: props.jadwal_id }),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'pertemuan_ke', name: 'pertemuan_ke' },
            { data: 'judul', name: 'judul' },
            { data: 'deskripsi', name: 'deskripsi' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                width: '20%',
                className: 'flex items-center justify-center gap-2',
                render: (data) => {
                    return `
                        <button class="btn-edit text-blue-500 cursor-pointer" data-id="${data}">Edit</button> |
                        <button class="btn-detail text-green-500 cursor-pointer" data-id="${data}">Detail</button> |
                        <button class="btn-delete text-red-500 cursor-pointer" data-id="${data}">Hapus</button>
                    `;
                },
            },
        ],
        drawCallback: function () {
            $('.btn-edit').on('click', function () {
                const id = $(this).data('id');
                router.visit(route('guru.jadwal-mengajar.materi.edit', { jadwal_id: props.jadwal_id, materi_id: id }));
            });

            $('.btn-detail').on('click', function () {
                const id = $(this).data('id');
                router.visit(route('guru.jadwal-mengajar.materi.show', { jadwal_id: props.jadwal_id, materi_id: id }));
            });

            $('.btn-delete').on('click', function () {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ff0000',
                }).then((result) => {
                    if (result.isConfirmed) {
                        router.delete(route('guru.jadwal-mengajar.materi.destroy', { jadwal_id: props.jadwal_id, materi_id: id }), {
                            onSuccess: () => {
                                table.ajax.reload();
                            },
                        });
                    }
                });
            });
        },
    });
});
</script>

<template>
    <Head :title="`Materi Pembelajaran - ${nama_mapel}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Materi Pembelajaran - {{ nama_mapel }}</h1>
                <Link :href="route('guru.jadwal-mengajar.materi.create', { jadwal_id: props.jadwal_id })">
                    <Button
                        class="flex items-center justify-center gap-2 rounded-md bg-white px-2 py-2.5 text-sm text-black hover:cursor-pointer hover:bg-white/90"
                    >
                        <Plus :size="18" />
                        Tambah Data
                    </Button>
                </Link>
            </div>
            <div>
                <table id="data-table" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pertemuan</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
