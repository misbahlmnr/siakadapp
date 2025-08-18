<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('admin.dashboard') },
    { title: 'Adaptive Rules', href: route('admin.adaptive-rules.index') },
];

onMounted(() => {
    useDataTable('#adaptive-rules-table', {
        ajax: route('admin.adaptive-rules.data'),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '4%' },
            { data: 'matpel.nama_mapel', name: 'matpel.nama_mapel' },
            { data: 'materi.judul_materi', name: 'materi.judul_materi' },
            { data: 'operator', name: 'operator', width: '8%' },
            { data: 'nilai_batas', name: 'nilai_batas', width: '8%' },
            { data: 'dibuat_oleh_name', name: 'dibuat_oleh_name', width: '15%' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false, width: '10%' },
        ],
    });
});
</script>

<template>
    <Head title="Adaptive Rules" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Adaptive Rules</h1>
                <Button
                    class="flex items-center gap-2 bg-blue-600 text-white hover:bg-blue-700"
                    @click="router.visit(route('admin.adaptive-rules.create'))"
                >
                    <Plus :size="18" /> Tambah Rule
                </Button>
            </div>

            <!-- Table -->
            <table id="adaptive-rules-table" class="display w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mapel</th>
                        <th>Materi</th>
                        <th>Operator</th>
                        <th>Nilai Batas</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </AppLayout>
</template>
