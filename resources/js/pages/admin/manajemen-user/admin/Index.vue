<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import $ from 'jquery';
import { Plus } from 'lucide-vue-next';
import { onMounted, watch } from 'vue';
import { useToast } from 'vue-toastification';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('admin.dashboard') },
    { title: 'Manajemen Data Admin', href: route('admin.users.index', 'admin') },
];

const page = usePage();
const toast = useToast();

// Flash message listener
watch(
    () => page.props.flash,
    (flash: any) => {
        if (flash?.success) toast.success(flash.success);
        if (flash?.error) toast.error(flash.error);
    },
    { immediate: true },
);

const props = defineProps({
    role: String,
});

onMounted(() => {
    $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        pagingType: 'simple_numbers',
        ajax: route('admin.users.data', props.role),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '4%', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at', width: '15%' },
            { data: 'action', name: 'action', orderable: false, width: '10%', searchable: false },
        ],
    });
});
</script>

<template>
    <Head title="Data Admin" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Data Admin</h1>
                <Link :href="route('admin.users.create', 'admin')">
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
