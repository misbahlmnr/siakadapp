<template>
    <Head title="Tambah Rekomendasi" />
    <AppLayout>
        <div class="flex flex-col gap-4">
            <h1 class="text-2xl font-bold">Tambah Rekomendasi</h1>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="siswa_id" class="block text-sm font-medium text-gray-700">Siswa</label>
                    <select v-model="form.siswa_id" id="siswa_id" required>
                        <option v-for="siswa in siswas" :key="siswa.id" :value="siswa.id">{{ siswa.nama_lengkap }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="evaluasi_id" class="block text-sm font-medium text-gray-700">Evaluasi</label>
                    <select v-model="form.evaluasi_id" id="evaluasi_id" required>
                        <option v-for="evaluasi in evaluasis" :key="evaluasi.id" :value="evaluasi.id">{{ evaluasi.judul_evaluasi }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="materi_id" class="block text-sm font-medium text-gray-700">Materi</label>
                    <select v-model="form.materi_id" id="materi_id" required>
                        <option v-for="materi in materis" :key="materi.id" :value="materi.id">{{ materi.nama_materi }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="alasan_rekomendasi" class="block text-sm font-medium text-gray-700">Alasan Rekomendasi</label>
                    <textarea
                        v-model="form.alasan_rekomendasi"
                        id="alasan_rekomendasi"
                        rows="3"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm"
                    ></textarea>
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select v-model="form.status" id="status" required>
                        <option value="belum_dibaca">Belum Dibaca</option>
                        <option value="dibaca">Dibaca</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Simpan</button>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

interface Siswa {
    id: number;
    nama_lengkap: string;
}

interface Evaluasi {
    id: number;
    judul_evaluasi: string;
}

interface Materi {
    id: number;
    nama_materi: string;
}

const form = useForm({
    siswa_id: '',
    evaluasi_id: '',
    materi_id: '',
    alasan_rekomendasi: '',
    status: 'belum_dibaca',
});

const siswas = ref<Siswa[]>([]);
const evaluasis = ref<Evaluasi[]>([]);
const materis = ref<Materi[]>([]);

onMounted(() => {
    // Fetch data for siswa, evaluasi, and materi
    fetchSiswas();
    fetchEvaluasis();
    fetchMateris();
});

const fetchSiswas = async () => {
    // Fetch siswa data from API
};

const fetchEvaluasis = async () => {
    // Fetch evaluasi data from API
};

const fetchMateris = async () => {
    // Fetch materi data from API
};

const submit = () => {
    form.post(route('guru.adaptive-learning.rekomendasi.store'));
};
</script>
