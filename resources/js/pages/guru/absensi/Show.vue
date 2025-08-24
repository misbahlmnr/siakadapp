<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, BookOpen, Calendar, CheckCircle, User, XCircle } from 'lucide-vue-next';
import { defineProps } from 'vue';

const props = defineProps<{
    absensi: any;
}>();

// Status style config with dark mode
const statusConfig: Record<string, { icon: any; label: string; color: string }> = {
    hadir: { icon: CheckCircle, label: 'Hadir', color: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' },
    sakit: { icon: XCircle, label: 'Sakit', color: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' },
    ijin: { icon: XCircle, label: 'Ijin', color: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' },
    alfa: { icon: XCircle, label: 'Alfa', color: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' },
};

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}
</script>

<template>
    <Head title="Detail Absensi" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Absensi Siswa', href: route('guru.absensi.index') },
            { title: 'Detail Absensi', href: route('guru.absensi.show', props.absensi.id) },
        ]"
    >
        <div class="flex flex-col gap-6 px-10 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" @click="router.visit(route('guru.absensi.index'))">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Absensi</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Informasi lengkap absensi siswa</p>
                    </div>
                </div>
                <div>
                    <Button variant="outline" @click="router.visit(route('guru.absensi.edit', props.absensi.id))"> Edit Absensi </Button>
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
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.absensi.siswa?.user?.name || '-' }}</p>
                        </div>
                        <div v-if="props.absensi.siswa?.nis">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">NIS</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ props.absensi.siswa.nis }}</p>
                        </div>
                        <div v-if="props.absensi.siswa?.kelas">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kelas</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ props.absensi.siswa.kelas?.nama_kelas }}</p>
                        </div>
                    </div>
                </div>

                <!-- Attendance Information Card -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#121212]">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <BookOpen class="h-5 w-5 text-green-600 dark:text-green-400" />
                        Detail Kehadiran
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kelas & Mata Pelajaran</p>
                            <p class="text-gray-900 dark:text-gray-200">
                                {{ props.absensi.jadwal?.kelas?.nama_kelas || '-' }} /
                                {{ props.absensi.jadwal?.mataPelajaran?.nama_mapel || '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pertemuan Ke</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ props.absensi.pertemuan_ke }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal</p>
                            <p class="text-gray-900 dark:text-gray-200">{{ formatDate(props.absensi.tanggal) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                            <div class="flex items-center gap-2">
                                <component :is="statusConfig[props.absensi.status]?.icon" class="h-4 w-4" />
                                <span :class="['rounded-full px-2 py-1 text-xs font-medium', statusConfig[props.absensi.status]?.color]">
                                    {{ statusConfig[props.absensi.status]?.label || props.absensi.status }}
                                </span>
                            </div>
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
                                new Date(props.absensi.created_at).toLocaleDateString('id-ID', {
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
                                new Date(props.absensi.updated_at).toLocaleDateString('id-ID', {
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
