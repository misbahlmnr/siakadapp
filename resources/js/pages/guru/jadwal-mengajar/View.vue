<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { FilePenLine } from 'lucide-vue-next';

const props = defineProps<{
    jadwal: {
        id: number;
        hari: string;
        waktu: string;
        kelas: string;
        mapel: string;
        materi: Array<{
            id: number;
            judul: string;
            edit_url: string | null;
        }>;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
    { title: `Detail Jadwal`, href: route('guru.jadwal-mengajar.show', props.jadwal.id) },
];
</script>

<template>
    <Head :title="`Detail Jadwal - ${jadwal.mapel}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 px-10 py-6">
            <!-- Detail Jadwal -->
            <h1 class="text-2xl font-bold">Detail Jadwal Mengajar</h1>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500">Hari</p>
                    <p class="font-semibold">{{ jadwal.hari }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Waktu</p>
                    <p class="font-semibold">{{ jadwal.waktu }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Kelas</p>
                    <p class="font-semibold">{{ jadwal.kelas }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Mata Pelajaran</p>
                    <p class="font-semibold">{{ jadwal.mapel }}</p>
                </div>
            </div>

            <!-- Materi -->
            <div>
                <h2 class="mt-6 mb-2 text-xl font-bold">Materi Pembelajaran</h2>
                <div v-if="jadwal.materi.length > 0" class="grid grid-cols-4 gap-4 space-y-4">
                    <div
                        v-for="materi in jadwal.materi"
                        :key="materi.id"
                        class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md"
                    >
                        <h3 class="text-lg font-semibold text-gray-800">{{ materi.judul }}</h3>
                        <Link v-if="materi.edit_url" href="{jadwal.materi.edit_url}">
                            <FilePenLine :size="20" class="text-orange-500" />
                        </Link>
                    </div>
                </div>

                <div v-else class="rounded-lg border border-dashed border-gray-300 bg-gray-50 py-8 text-center text-gray-500">
                    Belum ada materi untuk jadwal ini.<br />
                    <Button class="mt-4" @click="router.visit(route('guru.materi.create', { jadwal_id: jadwal.id }))"> Upload Materi </Button>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="mt-6">
                <Button variant="outline" @click="router.visit(route('guru.jadwal-mengajar.index'))"> Kembali </Button>
            </div>
        </div>
    </AppLayout>
</template>
