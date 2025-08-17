<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import 'sweetalert2/dist/sweetalert2.min.css';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('admin.dashboard') },
    { title: 'Manajemen Data User', href: route('admin.users.index', 'siswa') },
];

const props = defineProps({
    role: String,
});

onMounted(() => {
    useDataTable('#user-table', {
        ajax: route('admin.users.data', props.role),
        role: props.role,
        editRoute: 'admin.users.edit',
        deleteRoute: 'admin.users.destroy',
        detailRoute: 'admin.users.show',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'nis', name: 'nis' },
            { data: 'kelas', name: 'kelas' },
            { data: 'status', name: 'status' },
        ],
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
                        class="flex items-center justify-center gap-2 rounded-md bg-blue-600 px-2 py-2.5 text-sm text-white hover:cursor-pointer hover:bg-blue-600/90"
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
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
