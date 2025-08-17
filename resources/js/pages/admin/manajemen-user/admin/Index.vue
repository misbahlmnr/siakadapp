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
    { title: 'Manajemen Data Admin', href: route('admin.users.index', 'admin') },
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
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at', width: '15%' },
        ],
    });
});
</script>

<template>
    <Head title="Manajemen Data Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Data Admin</h1>
                <Button
                    class="flex items-center justify-center gap-2 rounded-md bg-blue-600 px-2 py-2.5 text-sm text-white hover:cursor-pointer hover:bg-blue-600/90"
                    @click="router.visit(route('admin.users.create', 'admin'))"
                >
                    <Plus :size="18" />
                    Tambah Data
                </Button>
            </div>
            <div>
                <table id="user-table" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
