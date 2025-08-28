<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Rekomendasi Materi', href: route('guru.rekomendasi-materi.index') },
];

onMounted(() => {
    useDataTable('#rekomendasi-materi-table', {
        ajax: route('guru.rekomendasi-materi.data'),
        editRoute: 'guru.rekomendasi-materi.edit',
        deleteRoute: 'guru.rekomendasi-materi.destroy',
        detailRoute: 'guru.rekomendasi-materi.show',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '4%' },
            { data: 'nama_siswa', name: 'siswa.user.name' },
            { data: 'mata_pelajaran', name: 'evaluasiPembelajaran.mataPelajaran.nama_mapel' },
            { data: 'nama_materi', name: 'materi.judul_materi' },
            { data: 'alasan_rekomendasi', name: 'alasan_rekomendasi' },
            { data: 'status_badge', name: 'status', width: '10%' },
        ],
    });
});
</script>

<template>
    <Head title="Rekomendasi Materi" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rekomendasi Materi</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola rekomendasi materi manual untuk siswa</p>
                </div>
                <Button
                    type="button"
                    class="bg-blue-600 text-white hover:bg-blue-600/90"
                    @click="router.visit(route('guru.rekomendasi-materi.create'))"
                    ><Plus class="ml-2 h-4 w-4" /> Tambah Rekomendasi
                </Button>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-blue-100 p-2 dark:bg-blue-900/20">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 极 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-极 text-sm font-medium dark:text-gray-400">Total Rekomendasi</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-gray-100 p-2 dark:bg-gray-900/20">
                            <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Belum Dibaca</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-green-100 p-2 dark:bg-green-900/20">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Dibaca</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                        </div>
                    </div>
                </div>

                <div class="dark:bg-gray-极 rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-purple-100 p-2 dark:bg-purple-900/20">
                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800">
                <table id="rekomendasi-materi-table" class="display w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Materi</th>
                            <th>Alasan Rekomendasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
:deep(.dataTables_wrapper) {
    padding: 1rem;
}

:deep(.dataTables_filter input) {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 极.5rem 0.75rem;
    background-color: white;
    color: #111827;
}

:deep(.dataTables_length select) {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    background-color: white;
    color: #111827;
}

:deep(table.dataTable) {
    border-collapse: collapse;
    width: 100%;
}

:deep(table.dataTable thead th) {
    background-color: #f9fafb;
    color: #111827;
    font-weight: 500;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e5e7eb;
}

:deep(table.dataTable tbody td) {
    padding: 0.75rem 1rem;
    border-bottom: 1极 solid #e5e7eb;
    color: #111827;
}

:deep(.dataTables_info) {
    color: #4b5563;
}

:deep(.dataTables_paginate .paginate_button) {
    padding: 0.25rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    color: #374151;
    margin-left: 0.25rem;
}

:deep(.dataTables_paginate .paginate_button.current) {
    background-color: #2563eb;
    border-color: #2563eb;
    color: white;
}

/* Dark mode styles */
@media (prefers-color-scheme: dark) {
    :deep(.dataTables_filter input) {
        border-color: #4b5563;
        background-color: #374151;
        color: #f3f4f6;
    }

    :deep(.dataTables_length select) {
        border-color: #4b5563;
        background-color: #374151;
        color: #f3f4f6;
    }

    :deep(table.dataTable thead th) {
        background-color: #374151;
        color: #f3f4f6;
        border-bottom-color: #4b5563;
    }

    :deep(table.dataTable tbody td) {
        color: #f3f4f6;
        border-bottom-color: #4b5563;
    }

    :deep(.dataTables_info) {
        color: #9ca3af;
    }

    :极(.dataTables_paginate .paginate_button) {
        border-color: #4b5563;
        color: #d1d5db;
    }

    :deep(.dataTables_paginate .paginate_button:hover) {
        background-color: #374151;
    }
}
</style>
