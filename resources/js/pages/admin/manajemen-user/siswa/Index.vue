<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import $ from 'jquery';
import { Plus } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('admin.dashboard') },
    { title: 'Manajemen Data User', href: route('admin.users.index', 'siswa') },
];

const props = defineProps({
    role: String,
});

const goToEdit = (id: number) => {
    router.visit(route('admin.users.edit', { role: props.role, id: id }));
};

onMounted(() => {
    const table = $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        pagingType: 'simple_numbers',
        ajax: route('admin.users.data', props.role),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            {
                data: 'name',
                name: 'name',
                render: (data) => data ?? '-',
            },
            {
                data: 'nisn',
                name: 'nisn',
                render: (data) => data ?? '-',
            },
            {
                data: 'kelas',
                name: 'kelas',
                render: (data) => data ?? '-',
            },
            {
                data: 'tahun_masuk',
                name: 'tahun_masuk',
                render: (data) => data ?? '-',
            },
            {
                data: 'status',
                name: 'status',
                render: (data) => data ?? '-',
            },
            {
                data: 'created_at',
                name: 'created_at',
                width: '15%',
                render: (data) => data ?? '-',
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: (data) => {
                    return `<button class="btn-edit text-blue-500 cursor-pointer" data-id="${data}">Edit</button> | <button class="btn-detail text-green-500 cursor-pointer" data-id="${data}">Detail</button> | <button class="btn-delete text-red-500 cursor-pointer" data-id="${data}">Hapus</button>`;
                },
            },
        ],

        drawCallback: function () {
            $('.btn-edit').on('click', function () {
                const id = $(this).data('id');
                goToEdit(id);
            });

            $('.btn-detail').on('click', function () {
                const id = $(this).data('id');
                router.visit(route('admin.users.show', { role: 'siswa', id: id }));
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
                        router.delete(route('admin.users.destroy', { role: 'siswa', id: id }), {
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
    <Head title="Manajemen Data Siswa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Data Siswa</h1>
                <Link :href="route('admin.users.create', 'siswa')">
                    <Button
                        class="flex items-center justify-center gap-2 rounded-md bg-white px-2 py-2.5 text-sm text-black hover:cursor-pointer hover:bg-white/90"
                    >
                        <Plus :size="18" />
                        Tambah Data
                    </Button>
                </Link>
            </div>
            <div>
                <table id="user-table" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Tahun Masuk</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
