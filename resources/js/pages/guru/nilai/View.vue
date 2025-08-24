<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { AlertCircle, ArrowLeft, BookOpen, Calendar, CheckCircle, MessageSquare, Star, User, XCircle } from 'lucide-vue-next';
import { defineProps } from 'vue';

const props = defineProps<{
    nilai: {
        id: number;
        siswa: any;
        evaluasi_pembelajaran: any;
        nilai: number | string;
        status: string;
        feedback: string;
        tanggal_dinilai: string;
        created_at: string;
        updated_at: string;
    };
}>();

// Status style config
const statusConfig: Record<string, { icon: any; label: string; color: string }> = {
    lulus: {
        icon: CheckCircle,
        label: 'Lulus',
        color: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    },
    remedial: {
        icon: AlertCircle,
        label: 'Remedial',
        color: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
    },
    tidak_lulus: {
        icon: XCircle,
        label: 'Tidak Lulus',
        color: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
    },
};
</script>

<template>
    <Head :title="`Detail Nilai - ${props.nilai.siswa.nama}`" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Nilai Siswa', href: route('guru.nilai.index') },
            { title: 'Detail Nilai', href: route('guru.nilai.show', props.nilai.id) },
        ]"
    >
        <div class="flex flex-col gap-6 px-10 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" @click="router.visit(route('guru.nilai.index'))" class="h-8 w-8">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Nilai Siswa</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Informasi lengkap nilai evaluasi pembelajaran</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" @click="router.visit(route('guru.nilai.edit', props.nilai.id))"> Edit Nilai </Button>
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
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.nilai.siswa.user.name }}</p>
                        </div>
                        <div v-if="props.nilai.siswa.nis">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">NIS</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ props.nilai.siswa.nis }}</p>
                        </div>
                        <div v-if="props.nilai.siswa.kelas">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kelas</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ props.nilai.siswa.kelas }}</p>
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
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Judul Evaluasi</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.nilai.evaluasi_pembelajaran.judul }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jenis Evaluasi</p>
                            <p class="text-gray-900 capitalize dark:text-gray-200">{{ props.nilai.evaluasi_pembelajaran.jenis }}</p>
                        </div>
                        <div v-if="props.nilai.evaluasi_pembelajaran.jadwal.mataPelajaran">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mata Pelajaran</p>
                            <p class="text-gray-900 dark:text-gray-200">
                                {{ props.nilai.evaluasi_pembelajaran.jadwal.mataPelajaran.nama }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Grade Information Card -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <Star class="h-5 w-5 text-yellow-600 dark:text-yellow-400" />
                        Informasi Nilai
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nilai</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ props.nilai.nilai }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                            <div class="flex items-center gap-2">
                                <component :is="statusConfig[props.nilai.status]?.icon" class="h-4 w-4" />
                                <span :class="['rounded-full px-2 py-1 text-xs font-medium', statusConfig[props.nilai.status]?.color]">
                                    {{ statusConfig[props.nilai.status]?.label || props.nilai.status }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Dinilai</p>
                            <p class="text-gray-900 dark:text-gray-200">
                                {{
                                    new Date(props.nilai.tanggal_dinilai).toLocaleDateString('id-ID', {
                                        weekday: 'long',
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                    })
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feedback Card -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <MessageSquare class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                        Feedback Guru
                    </h2>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Komentar</p>
                        <div v-if="props.nilai.feedback" class="mt-2 rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="text-[#121212] dark:text-gray-200">{{ props.nilai.feedback }}</p>
                        </div>
                        <div v-else class="mt-2 rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="text-gray-500 italic dark:text-gray-400">Tidak ada feedback yang diberikan</p>
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
                                new Date(props.nilai.created_at).toLocaleDateString('id-ID', {
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
                                new Date(props.nilai.updated_at).toLocaleDateString('id-ID', {
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
