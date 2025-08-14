<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    jadwal: {
        nama_mapel: string;
        kelas: string;
    };
    guru: {
        nama: string;
    };
    materi: {
        id: number;
        jadwal_id: number;
        guru_id: number;
        pertemuan_ke: number;
        judul: string;
        deskripsi: string;
        file_path?: string | null;
        link_file?: string | null;
    };
}>();
</script>

<template>
    <Head :title="`Detail Materi: ${props.materi.judul}`" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Beranda', href: route('admin.dashboard') },
            { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
            { title: 'Materi Pembelajaran', href: route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.materi.jadwal_id }) },
            { title: props.materi.judul, href: '#' },
        ]"
    >
        <div class="flex flex-col gap-6 px-8 py-6">
            <h1 class="text-2xl font-bold">Detail Materi Pembelajaran</h1>

            <!-- Informasi umum -->
            <div class="grid max-w-full grid-cols-1 gap-6 md:max-w-2xl md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-400">Pertemuan Ke</p>
                    <p class="font-semibold">{{ props.materi.pertemuan_ke }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Guru</p>
                    <p class="font-semibold">{{ props.guru.nama || '-' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Mata Pelajaran</p>
                    <p class="font-semibold">{{ props.jadwal.nama_mapel || '-' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Kelas</p>
                    <p class="font-semibold">{{ props.jadwal.kelas || '-' }}</p>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <p class="text-sm text-gray-400">Deskripsi</p>
                <p class="font-semibold whitespace-pre-line">{{ props.materi.deskripsi || '-' }}</p>
            </div>

            <!-- File Materi -->
            <div>
                <p class="text-sm text-gray-400">File Materi</p>
                <div v-if="props.materi.file_path">
                    <a :href="props.materi.file_path" target="_blank" class="text-blue-500 hover:underline"> 📄 Download File </a>
                </div>
                <div v-else>-</div>
            </div>

            <!-- Link File -->
            <div>
                <p class="text-sm text-gray-400">Link File</p>
                <div v-if="props.materi.link_file">
                    <a :href="props.materi.link_file" target="_blank" class="text-blue-500 hover:underline"> 🔗 Buka Link </a>
                </div>
                <div v-else>-</div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-6 flex gap-4">
                <Link :href="route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.materi.jadwal_id })">
                    <Button variant="outline">Kembali</Button>
                </Link>
                <Link :href="route('guru.jadwal-mengajar.materi.edit', { jadwal_id: props.materi.jadwal_id, materi_id: props.materi.id })">
                    <Button>Edit Data</Button>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
