<template>
    <Head title="Detail Rekomendasi" />
    <AppLayout>
        <div class="flex flex-col gap-4">
            <h1 class="text-2xl font-bold">Detail Rekomendasi</h1>
            <div class="rounded-lg bg-white p-4 shadow">
                <h2 class="text-lg font-semibold">Materi: {{ rekomendasi?.materi?.nama_materi || '-' }}</h2>
                <h3 class="text-md">Evaluasi: {{ rekomendasi?.evaluasiPembelajaran?.judul_evaluasi || '-' }}</h3>
                <p>Status: <span v-html="rekomendasi?.status_badge || '-'"></span></p>
                <p>Alasan Rekomendasi: {{ rekomendasi?.alasan_rekomendasi || '-' }}</p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    rekomendasi: Object,
});
</script>
