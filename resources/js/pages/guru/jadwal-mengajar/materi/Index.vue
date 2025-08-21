<script setup lang="ts">
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { onMounted } from 'vue';

const props = defineProps<{
    jadwal_id: number;
    nama_mapel: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
    { title: `Materi Pembelajaran`, href: route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.jadwal_id }) },
];

onMounted(() => {
    const table = useDataTable('#data-table', {
        ajax: route('guru.jadwal-mengajar.materi.data', { jadwal_id: props.jadwal_id }),
        editRoute: 'guru.jadwal-mengajar.materi.edit',
        deleteRoute: 'guru.jadwal-mengajar.materi.destroy',
        detailRoute: 'guru.jadwal-mengajar.materi.show',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'pertemuan_ke', name: 'pertemuan_ke' },
            { data: 'judul_materi', name: 'judul_materi' },
            { data: 'file_materi', name: 'file_materi' },
            { data: 'link_materi', name: 'link_materi' },
        ],
        actions: (id, row) => `
            <button data-action="detail" data-id="${id}" class="text-green-500 cursor-pointer">Detail</button> |
            <button data-action="edit" data-id="${id}" class="text-blue-500 cursor-pointer">Edit</button> |
            <button data-action="delete" data-id="${id}" class="text-red-500 cursor-pointer">Hapus</button>
        `,
        onAction: (action, id, row) => {
            if (action === 'detail') {
                router.visit(route('guru.jadwal-mengajar.materi.show', { jadwal_id: props.jadwal_id, materi_id: id }));
            } else if (action === 'edit') {
                router.visit(route('guru.jadwal-mengajar.materi.edit', { jadwal_id: props.jadwal_id, materi_id: id }));
            } else if (action === 'delete') {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus materi ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ff0000',
                }).then((result) => {
                    if (result.isConfirmed) {
                        router.delete(route('guru.jadwal-mengajar.materi.destroy', { jadwal_id: props.jadwal_id, materi_id: id }), {
                            onSuccess: () => table.ajax.reload(),
                        });
                    }
                });
            }
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
                <Button
                    class="flex items-center justify-center gap-2 rounded-md bg-blue-600 px-2 py-2.5 text-sm text-white hover:cursor-pointer hover:bg-blue-600/90"
                    @click="router.visit(route('guru.jadwal-mengajar.materi.create', { jadwal_id: props.jadwal_id }))"
                >
                    <Plus :size="18" />
                    Tambah Data
                </Button>
            </div>
            <div>
                <table id="data-table" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pertemuan</th>
                            <th>Judul</th>
                            <th>File</th>
                            <th>Link</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
