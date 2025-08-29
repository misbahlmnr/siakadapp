<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import 'sweetalert2/dist/sweetalert2.min.css';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('admin.dashboard') },
    { title: 'Mata Pelajaran', href: route('admin.manajemen-guru-mapel.index') },
];

onMounted(() => {
    useDataTable('#data-table', {
        ajax: route('admin.manajemen-guru-mapel.data'),
        editRoute: 'admin.manajemen-guru-mapel.edit',
        deleteRoute: 'admin.manajemen-guru-mapel.destroy',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'nama_guru', name: 'nama_guru' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
        ],
    });
});
</script>

<template>
    <Head title="Daftar List Guru Mata Pelajaran" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Daftar List Guru Mata Pelajaran</h1>
                <Button
                    class="flex items-center justify-center gap-2 rounded-md bg-blue-600 px-2 py-2.5 text-sm text-white hover:cursor-pointer hover:bg-blue-600/90"
                    @click="router.visit(route('admin.manajemen-guru-mapel.create'))"
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
                            <th>Nama Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
