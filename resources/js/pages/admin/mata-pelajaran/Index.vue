<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import $ from 'jquery';
import { Plus } from 'lucide-vue-next';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('admin.dashboard') },
    { title: 'Mata Pelajaran', href: route('admin.mata-pelajaran.index') },
];

onMounted(() => {
    const table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        pagingType: 'simple_numbers',
        ajax: route('admin.mata-pelajaran.data'),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'nama_mapel', name: 'nama_mapel' },
            // { data: 'nama_guru', name: 'nama_guru' },
            { data: 'created_at', name: 'created_at', width: '15%' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                width: '15%',
                render: (data) => {
                    return `<button class="btn-edit text-blue-500 cursor-pointer" data-id="${data}">Edit</button> | <button class="btn-detail text-green-500 cursor-pointer" data-id="${data}">Detail</button> | <button class="btn-delete text-red-500 cursor-pointer" data-id="${data}">Hapus</button>`;
                },
            },
        ],
        drawCallback: function () {
            $('.btn-edit').on('click', function () {
                const id = $(this).data('id');
                router.visit(route('admin.users.edit', { role: 'admin', id: id }));
            });

            $('.btn-delete').on('click', function () {
                const id = $(this).data('id');
                if (confirm('Yakin ingin menghapus data admin ini?')) {
                    router.delete(route('admin.users.destroy', { role: 'admin', id: id }), {
                        onSuccess: () => {
                            table.ajax.reload();
                        },
                    });
                }
            });
        },
    });
});
</script>

<template>
    <Head title="Mata Pelajaran" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Data Mata Pelajaran</h1>
                <Link :href="route('admin.mata-pelajaran.create')">
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
                            <th>Nama Pelajaran</th>
                            <!-- <th>Nama Guru</th> -->
                            <th>Created At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
