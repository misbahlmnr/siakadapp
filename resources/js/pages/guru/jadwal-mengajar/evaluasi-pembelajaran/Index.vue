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
    { title: `Evaluasi Pembelajaran`, href: route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', { jadwal_id: props.jadwal_id }) },
];

onMounted(() => {
    const table = useDataTable('#data-table', {
        ajax: route('guru.jadwal-mengajar.evaluasi-pembelajaran.data', { jadwal_id: props.jadwal_id }),
        editRoute: 'guru.jadwal-mengajar.evaluasi-pembelajaran.edit',
        deleteRoute: 'guru.jadwal-mengajar.evaluasi-pembelajaran.destroy',
        detailRoute: 'guru.jadwal-mengajar.evaluasi-pembelajaran.show',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'judul', name: 'judul' },
            { data: 'jenis', name: 'jenis' },
            { data: 'waktu', name: 'waktu' },
            { data: 'file_soal', name: 'file_soal' },
            { data: 'link_soal', name: 'link_soal' },
        ],
        actions: (id, row) => `
            <button data-action="edit" class="btn-edit text-yellow-500 btn-custom-outline" title="Edit" data-id="${id}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button>
            <button data-action="detail" class="btn-detail text-green-500 cursor-pointer btn-custom-outline" title="Detail" data-id="${id}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg></button>
            <button data-action="delete" class="btn-delete text-red-500 cursor-pointer btn-custom-outline" title="Hapus" data-id="${id}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
        `,
        onAction: (action, id, row) => {
            if (action === 'detail') {
                router.visit(route('guru.jadwal-mengajar.evaluasi-pembelajaran.show', { jadwal_id: props.jadwal_id, evaluasi_id: id }));
            } else if (action === 'edit') {
                router.visit(route('guru.jadwal-mengajar.evaluasi-pembelajaran.edit', { jadwal_id: props.jadwal_id, evaluasi_id: id }));
            } else if (action === 'delete') {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus evaluasi ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ff0000',
                }).then((result) => {
                    if (result.isConfirmed) {
                        router.delete(route('guru.jadwal-mengajar.evaluasi-pembelajaran.destroy', { jadwal_id: props.jadwal_id, evaluasi_id: id }), {
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
    <Head :title="`Evaluasi Pembelajaran - ${nama_mapel}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Evaluasi Pembelajaran - {{ nama_mapel }}</h1>
                <Button
                    class="flex items-center justify-center gap-2 rounded-md bg-blue-600 px-2 py-2.5 text-sm text-white hover:cursor-pointer hover:bg-blue-600/90"
                    @click="router.visit(route('guru.jadwal-mengajar.evaluasi-pembelajaran.create', { jadwal_id: props.jadwal_id }))"
                >
                    <Plus :size="18" />
                    Tambah Tugas
                </Button>
            </div>
            <div>
                <table id="data-table" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Jenis</th>
                            <th>Waktu</th>
                            <th>File Soal</th>
                            <th>Link Soal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
