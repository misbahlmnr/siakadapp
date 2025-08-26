<template>
    <Head title="Daftar Rekomendasi" />
    <AppLayout>
        <div class="flex flex-col gap-4">
            <h1 class="text-2xl font-bold">Daftar Rekomendasi</h1>
            <table id="rekomendasi-table" class="display w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Materi</th>
                        <th>Evaluasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
        ajax: route('guru.adaptive-learning.rekomendasi.data'),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'siswa.nama_lengkap', name: 'siswa.nama_lengkap' },
            { data: 'materi.nama_materi', name: 'materi.nama_materi' },
            { data: 'evaluasiPembelajaran.judul_evaluasi', name: 'evaluasiPembelajaran.judul_evaluasi' },
            { data: 'status', name: 'status' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });
});
</script>
