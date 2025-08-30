<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    mataPelajaran: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('siswa.dashboard') },
    { title: 'Materi Belajar', href: route('siswa.materi.index') },
];

interface Materi {
    id: number;
    pertemuan_ke: number;
    judul_materi: string;
    deskripsi?: string;
    created_at: string;
    jadwal_id: number;
    guru_id: number;
    semester_ajaran_id: number;
    file_materi?: string;
    link_materi?: string;
}

const selectedMataPelajaran = ref<number | null>(null);
const filteredMateri = ref<Materi[]>([]);

const updateMateri = () => {
    const selected = props.mataPelajaran.find((matpel: any) => matpel.id === selectedMataPelajaran.value);

    if (selected) {
        // Ambil semua materi dari semua guru
        filteredMateri.value = selected.guru_mata_pelajaran.map((guru: any) => guru.jadwal_pelajaran.map((jadwal: any) => jadwal.materi)).flat(2); // flat 2 karena ada 2 level array
    } else {
        filteredMateri.value = [];
    }
};
</script>

<template>
    <Head title="Materi Belajar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <h1 class="text-2xl font-bold">Materi Belajar</h1>
            <p class="-mt-3 mb-10 text-sm text-gray-600 dark:text-gray-400">Silakan pilih mata pelajaran untuk melihat materi</p>

            <!-- Dropdown untuk memilih mata pelajaran -->
            <div class="mb-6 w-md">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"> Pilih Mata Pelajaran </label>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button type="button" class="flex w-full justify-between rounded border px-4 py-2">
                            <span class="text-sm">
                                {{
                                    selectedMataPelajaran
                                        ? props.mataPelajaran.find((m) => m.id === selectedMataPelajaran)?.nama_mapel
                                        : 'Pilih Mata Pelajaran'
                                }}
                            </span>
                            <ChevronDown />
                        </button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                        <DropdownMenuItem
                            v-for="matpel in props.mataPelajaran"
                            :key="matpel.id"
                            @click="
                                selectedMataPelajaran = matpel.id;
                                updateMateri();
                            "
                        >
                            {{ matpel.nama_mapel }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <div class="rounded-lg border border-dashed border-gray-700/90 p-4">
                <!-- Tampilkan materi berdasarkan pilihan mata pelajaran -->
                <div v-if="filteredMateri.length > 0" class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        Materi {{ props.mataPelajaran.find((m) => m.id === selectedMataPelajaran)?.nama_mapel }}
                    </h2>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="m in filteredMateri"
                            :key="m.id"
                            class="rounded-lg bg-white p-4 shadow-md transition-shadow duration-200 hover:shadow-lg dark:bg-gray-900"
                        >
                            <div class="mb-3 flex items-center justify-between">
                                <span class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    Pertemuan {{ m.pertemuan_ke }}
                                </span>
                            </div>

                            <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ m.judul_materi }}
                            </h3>

                            <p v-if="m.deskripsi" class="mb-4 line-clamp-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ m.deskripsi }}
                            </p>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ new Date(m.created_at).toLocaleDateString('id-ID') }}
                                </span>
                                <a
                                    :href="route('siswa.materi.show', m.id)"
                                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-blue-700"
                                >
                                    Buka Materi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tampilan jika belum memilih mata pelajaran -->
                <div v-else-if="selectedMataPelajaran === null" class="py-12 text-center">
                    <div class="text-gray-400 dark:text-gray-500">
                        <svg class="mx-auto mb-4 h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            ></path>
                        </svg>
                        <p class="text-lg font-medium">Pilih mata pelajaran untuk melihat materi</p>
                        <p class="text-sm">Silakan pilih mata pelajaran dari dropdown di atas</p>
                    </div>
                </div>

                <!-- Tampilan jika mata pelajaran yang dipilih tidak memiliki materi -->
                <div v-else class="py-12 text-center">
                    <div class="text-gray-400 dark:text-gray-500">
                        <svg class="mx-auto mb-4 h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        <p class="text-lg font-medium">Belum ada materi</p>
                        <p class="text-sm">Mata pelajaran ini belum memiliki materi pembelajaran</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
