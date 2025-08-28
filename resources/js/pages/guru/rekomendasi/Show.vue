<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, BookOpen, Calendar, CheckCircle, Clock, FileText, MessageSquare, User, XCircle } from 'lucide-vue-next';
import { defineProps } from 'vue';

const props = defineProps<{
    rekomendasi: {
        id: number;
        siswa: any;
        evaluasi_pembelajaran: any;
        materi: any;
        alasan_rekomendasi: string;
        status: string;
        created_at: string;
        updated_at: string;
    };
}>();

// Status style config
const statusConfig: Record<string, { icon: any; label: string; color: string }> = {
    pending: {
        icon: Clock,
        label: 'Pending',
        color: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
    },
    diterima: {
        icon: CheckCircle,
        label: 'Diterima',
        color: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    },
    ditolak: {
        icon: XCircle,
        label: 'Ditolak',
        color: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
    },
};
</script>

<template>
    <Head :title="`Detail Rekomendasi Materi - ${props.rekomendasi.siswa?.user?.name || 'Siswa'}`" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Rekomendasi Materi', href: route('guru.rekomendasi-materi.index') },
            { title: 'Detail Rekomendasi', href: route('guru.rekomendasi-materi.show', props.rekomendasi.id) },
        ]"
    >
        <div class="flex flex-col gap-6 px-10 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" @click="router.visit(route('guru.rekomendasi-materi.index'))" class="h-8 w-8">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Rekomendasi Materi</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Informasi lengkap rekomendasi materi pembelajaran</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" @click="router.visit(route('guru.rekomendasi-materi.edit', props.rekomendasi.id))">
                        Edit Rekomendasi
                    </Button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Student Information Card -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <User class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        Informasi Siswa
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama Siswa</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.rekomendasi.siswa?.user?.name || '-' }}</p>
                        </div>
                        <div v-if="props.rekomendasi.siswa?.nis">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">NIS</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ props.rekomendasi.siswa.nis }}</p>
                        </div>
                        <div v-if="props.rekomendasi.siswa?.kelas">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kelas</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ props.rekomendasi.siswa.kelas }}</p>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Information Card -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <BookOpen class="h-5 w-5 text-green-600 dark:text-green-400" />
                        Informasi Evaluasi
                    </h2>
                    <div class="space-y-4">
                        <div v-if="props.rekomendasi.evaluasi_pembelajaran">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Judul Evaluasi</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.rekomendasi.evaluasi_pembelajaran.judul }}</p>
                        </div>
                        <div v-if="props.rekomendasi.evaluasi_pembelajaran?.jadwal?.mataPelajaran">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mata Pelajaran</p>
                            <p class="text-gray-900 dark:text-gray-200">
                                {{ props.rekomendasi.evaluasi_pembelajaran.jadwal.mataPelajaran.nama_mapel }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Material Information Card -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <FileText class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                        Informasi Materi
                    </h2>
                    <div class="space-y-4">
                        <div v-if="props.rekomendasi.materi">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Judul Materi</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.rekomendasi.materi.judul_materi }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status Rekomendasi</p>
                            <div class="flex items-center gap-2">
                                <component :is="statusConfig[props.rekomendasi.status]?.icon" class="h-4 w-4" />
                                <span :class="['rounded-full px-2 py-1 text-xs font-medium', statusConfig[props.rekomendasi.status]?.color]">
                                    {{ statusConfig[props.rekomendasi.status]?.label || props.rekomendasi.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommendation Reason Card -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <MessageSquare class="h-5 w-5 text-orange-600 dark:text-orange-400" />
                        Alasan Rekomendasi
                    </h2>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Keterangan</p>
                        <div v-if="props.rekomendasi.alasan_rekomendasi" class="mt-2 rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="text-[#121212] dark:text-gray-200">{{ props.rekomendasi.alasan_rekomendasi }}</p>
                        </div>
                        <div v-else class="mt-2 rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="text-gray-500 italic dark:text-gray-400">Tidak ada alasan rekomendasi yang diberikan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Information -->
            <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                    <Calendar class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                    Informasi Timeline
                </h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Dibuat Pada</p>
                        <p class="text-gray-900 dark:text-gray-200">
                            {{
                                new Date(props.rekomendasi.created_at).toLocaleDateString('id-ID', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                })
                            }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Diperbarui Pada</p>
                        <p class="text-gray-900 dark:text-gray-200">
                            {{
                                new Date(props.rekomendasi.updated_at).toLocaleDateString('id-ID', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                })
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
