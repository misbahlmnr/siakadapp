<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    tugas: {
        id: number;
        judul: string;
        deskripsi: string;
        mata_pelajaran: string;
        guru: string;
        waktu_mulai: string;
        waktu_selesai: string;
        file_soal: string | null;
        link_soal: string | null;
        pengumpulan: {
            file_jawaban: string | null;
            link_jawaban: string | null;
            status: string;
            waktu_pengumpulan: string;
        } | null;
        nilai: {
            nilai: number | null;
            feedback: string | null;
            status: string;
        } | null;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('siswa.dashboard') },
    { title: 'List Tugas', href: route('siswa.tugas.index') },
    { title: 'Detail Tugas', href: route('siswa.tugas.show', props.tugas.id) },
];

const form = useForm({
    file_jawaban: null as File | null,
    link_jawaban: '',
});

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.file_jawaban = target.files[0];
    }
};

const submitJawaban = () => {
    form.post(route('siswa.tugas.submit', { id: props.tugas.id }), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getStatusClass = (status: string) => {
    const classes: Record<string, string> = {
        dikumpulkan: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        telat: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

const getStatusText = (status: string) => {
    const texts: Record<string, string> = {
        dikumpulkan: 'Sudah Dikumpulkan',
        telat: 'Terlambat',
    };
    return texts[status] || status;
};

const getNilaiStatusClass = (status: string) => {
    const classes: Record<string, string> = {
        lulus: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        tidak_lulus: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        menunggu: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="tugas.judul" />

        <div class="space-y-6 p-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ tugas.judul }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ tugas.deskripsi }}</p>
            </div>

            <!-- Task Details -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Tugas</h2>
                    <div class="mt-4 space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Mata Pelajaran:</span>
                            <p class="text-gray-900 dark:text-white">{{ tugas.mata_pelajaran }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat oleh:</span>
                            <p class="text-gray-900 dark:text-white">{{ tugas.guru }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Mulai:</span>
                            <p class="text-gray-900 dark:text-white">{{ formatDateTime(tugas.waktu_mulai) }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Deadline:</span>
                            <p class="text-gray-900 dark:text-white">{{ formatDateTime(tugas.waktu_selesai) }}</p>
                        </div>
                    </div>
                </div>

                <!-- File/Link Soal -->
                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">File/Link Soal</h2>
                    <div class="mt-4 space-y-3">
                        <div v-if="tugas.file_soal">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">File Soal:</span>
                            <a :href="'/storage/' + tugas.file_soal" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">
                                Download File Soal
                            </a>
                        </div>
                        <div v-if="tugas.link_soal">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Link Soal:</span>
                            <a :href="tugas.link_soal" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">
                                {{ tugas.link_soal }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submission Status -->
            <div v-if="tugas.pengumpulan" class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status Pengumpulan</h2>
                <div class="mt-4 space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status:</span>
                        <span :class="getStatusClass(tugas.pengumpulan.status)" class="rounded-full px-3 py-1 text-xs font-medium">
                            {{ getStatusText(tugas.pengumpulan.status) }}
                        </span>
                    </div>
                    <div v-if="tugas.pengumpulan.waktu_pengumpulan">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Pengumpulan:</span>
                        <p class="text-gray-900 dark:text-white">{{ formatDateTime(tugas.pengumpulan.waktu_pengumpulan) }}</p>
                    </div>
                    <div v-if="tugas.pengumpulan.file_jawaban">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">File Jawaban:</span>
                        <a
                            :href="'/storage/' + tugas.pengumpulan.file_jawaban"
                            target="_blank"
                            class="text-blue-600 hover:underline dark:text-blue-400"
                        >
                            Download File Jawaban
                        </a>
                    </div>
                    <div v-if="tugas.pengumpulan.link_jawaban">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Link Jawaban:</span>
                        <a :href="tugas.pengumpulan.link_jawaban" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">
                            {{ tugas.pengumpulan.link_jawaban }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Submission Form -->
            <div v-if="!tugas.pengumpulan || tugas.pengumpulan.status === 'telat'" class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Kirim Jawaban</h2>
                <form @submit.prevent="submitJawaban" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">File Jawaban</label>
                        <input
                            type="file"
                            @change="handleFileUpload"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            accept=".pdf,.doc,.docx,.zip,.rar"
                        />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: PDF, DOC, DOCX, ZIP, RAR (max 10MB)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link Jawaban</label>
                        <input
                            type="url"
                            v-model="form.link_jawaban"
                            placeholder="https://..."
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="!form.file_jawaban && !form.link_jawaban"
                        class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Kirim Jawaban
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
