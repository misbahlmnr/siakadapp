<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';

// ui components
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { ChevronDown } from 'lucide-vue-next';

const props = defineProps<{
    rekomendasi: Array<{
        id: number;
        materi_id: number;
        evaluasi_pembelajaran_id: number;
        siswa_id: number;
        alasan_rekomendasi: string;
        status: string;
        created_at: string;
        updated_at: string;
        materi?: any;
        evaluasi_pembelajaran?: {
            id: number;
            judul_evaluasi: string;
        };
    }>;
    mataPelajaranList: Array<{ id: number; nama_mapel: string }>;
}>();

// filter state
const selectedMataPelajaran = ref('');
const selectedMataPelajaranLabel = ref('Semua Mata Pelajaran');

const filteredRekomendasi = computed(() => {
    if (!selectedMataPelajaran.value) return props.rekomendasi;
    return props.rekomendasi.filter((item) => item.materi?.jadwal?.mataPelajaran?.id === parseInt(selectedMataPelajaran.value));
});

const getStatusText = (status: string) => {
    const texts: Record<string, string> = {
        belum_dibaca: 'Belum Dibaca',
        dibaca: 'Dibaca',
        selesai: 'Selesai',
    };
    return texts[status] || 'Belum Dibaca';
};

const updateStatus = async (id: number, status: string) => {
    try {
        await axios.put(route('siswa.rekomendasi.update-status', id), { status });
        window.location.reload();
    } catch (error) {
        console.error('Error updating status:', error);
    }
};
</script>

<template>
    <AppLayout title="Rekomendasi Materi" description="Halaman ini menampilkan rekomendasi materi untuk siswa.">
        <Head title="Rekomendasi Materi" />

        <div class="rounded-lg p-6 shadow">
            <!-- Filter Section pakai Dropdown -->
            <div class="mb-6 flex flex-col items-center gap-4 sm:flex-row">
                <div class="w-full sm:w-64">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Filter by Mata Pelajaran </label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button
                                type="button"
                                class="flex w-full items-center justify-between rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700"
                            >
                                <span class="truncate">{{ selectedMataPelajaranLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                @click="
                                    selectedMataPelajaran = '';
                                    selectedMataPelajaranLabel = 'Semua Mata Pelajaran';
                                "
                            >
                                Semua Mata Pelajaran
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                v-for="mapel in props.mataPelajaranList"
                                :key="mapel.id"
                                @click="
                                    selectedMataPelajaran = mapel.id.toString();
                                    selectedMataPelajaranLabel = mapel.nama_mapel;
                                "
                            >
                                {{ mapel.nama_mapel }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredRekomendasi.length === 0" class="py-12 text-center">
                <p class="text-lg font-medium text-gray-600 dark:text-gray-300">Belum ada rekomendasi materi</p>
                <p class="text-gray-500 dark:text-gray-400">Sistem akan memberikan rekomendasi materi berdasarkan evaluasi dan nilai Anda.</p>
            </div>

            <!-- Rekomendasi List -->
            <div v-else class="space-y-4">
                <div
                    v-for="item in filteredRekomendasi"
                    :key="item.id"
                    class="rounded-lg border border-gray-200 p-4 transition-shadow hover:shadow-md dark:border-gray-700"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ item.materi?.judul_materi || 'Materi Tidak Ditemukan' }}
                            </h3>

                            <div class="mb-3 flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <span>{{ item.evaluasi_pembelajaran?.judul_evaluasi || 'Evaluasi Umum' }}</span>
                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium dark:bg-gray-700">
                                    {{ getStatusText(item.status) }}
                                </span>
                            </div>

                            <p class="mb-4 text-gray-700 dark:text-gray-300">
                                <strong>Alasan Rekomendasi:</strong>
                                {{ item.alasan_rekomendasi || 'Materi ini direkomendasikan untuk meningkatkan pemahaman Anda' }}
                            </p>

                            <div class="flex items-center gap-3">
                                <Link
                                    :href="route('siswa.rekomendasi.show', item.id)"
                                    class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700 focus:outline-none"
                                >
                                    Lihat Detail
                                </Link>

                                <!-- Dropdown ubah status -->
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <button
                                            type="button"
                                            class="flex items-center justify-between rounded-md border border-gray-300 px-3 py-1 text-sm shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700"
                                        >
                                            <span>{{ getStatusText(item.status) }}</span>
                                            <ChevronDown class="ml-2 h-4 w-4 text-gray-500" />
                                        </button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent class="min-w-[150px]">
                                        <DropdownMenuItem @click="updateStatus(item.id, 'belum_dibaca')"> Belum Dibaca </DropdownMenuItem>
                                        <DropdownMenuItem @click="updateStatus(item.id, 'dibaca')"> Dibaca </DropdownMenuItem>
                                        <DropdownMenuItem @click="updateStatus(item.id, 'selesai')"> Selesai </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
