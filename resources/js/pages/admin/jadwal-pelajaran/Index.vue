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
    { title: 'Jadwal Pelajaran', href: route('admin.jadwal-pelajaran.create') },
];

onMounted(() => {
    useDataTable('#data-table', {
        ajax: route('admin.jadwal-pelajaran.data'),
        editRoute: 'admin.jadwal-pelajaran.edit',
        deleteRoute: 'admin.jadwal-pelajaran.destroy',
        detailRoute: 'admin.jadwal-pelajaran.show',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'kelas', name: 'kelas' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'guru', name: 'guru' },
            { data: 'hari', name: 'hari' },
            { data: 'jam', name: 'jam' },
            { data: 'semester', name: 'semester' },
            { data: 'tahun_ajaran', name: 'tahun_ajaran' },
        ],
    });
});
</script>

<template>
    <Head title="Daftar Jadwal Pelajaran" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Daftar Jadwal Pelajaran</h1>
                <Button
                    class="flex items-center justify-center gap-2 rounded-md bg-blue-600 px-2 py-2.5 text-sm text-white hover:cursor-pointer hover:bg-blue-600/90"
                    @click="router.visit(route('admin.jadwal-pelajaran.create'))"
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
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Semester</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
