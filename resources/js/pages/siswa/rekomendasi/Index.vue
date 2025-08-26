<template>
    <Head title="Rekomendasi" />
    <AppLayout>
        <div class="flex flex-col gap-4">
            <h1 class="text-2xl font-bold">Rekomendasi Materi</h1>
            <table id="rekomendasi-table" class="display w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Materi</th>
                        <th>Evaluasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

onMounted(() => {
    useDataTable('#rekomendasi-table', {
        ajax: route('siswa.rekomendasi.data'),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'materi_nama', name: 'materi_nama' },
            { data: 'evaluasi_nama', name: 'evaluasi_nama' },
            { data: 'status_badge', name: 'status_badge', orderable: false, searchable: false },
        ],
    });
});
</script>
