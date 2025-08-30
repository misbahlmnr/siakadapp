<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Kelas, MatPel } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    absensi: any;
    kelasOptions: Kelas[];
    mapelOptions: MatPel[];
    siswaOptions: { id: number; nama: string }[];
    jadwalPelajaranOptions: any[];
    semesterDanTahunAjaranList: { id: number; semester: string; tahun_ajaran: string }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Absensi Siswa', href: route('guru.absensi.index') },
    { title: 'Edit Absensi', href: route('guru.absensi.edit', props.absensi.id) },
];

type Form = {
    jadwal_id: number | null;
    siswa_id: number | null;
    semester_ajaran_id: number | null;
    pertemuan_ke: number | string;
    tanggal: string;
    status: string;
};

const form = useForm<Form>({
    jadwal_id: props.absensi.jadwal_id,
    siswa_id: props.absensi.siswa_id,
    semester_ajaran_id: props.absensi.semester_ajaran_id,
    pertemuan_ke: props.absensi.pertemuan_ke,
    tanggal: props.absensi.tanggal,
    status: props.absensi.status || 'hadir',
});

// dropdown label refs
const selectedKelasLabel = ref('Pilih Kelas');
const selectedMapelLabel = ref('Pilih Mata Pelajaran');
const selectedSiswaLabel = ref('Pilih Siswa');
const selectedStatusLabel = ref('Pilih Status');
const selectedSemesterLabel = ref('Pilih Semester & Tahun Ajaran');
const selectedJadwalLabel = ref('Pilih Jadwal');

// State untuk filter
const selectedKelasId = ref<number | null>(null);
const selectedMapelId = ref<number | null>(null);

// Status options
const statusOptions = [
    { label: 'Hadir', value: 'hadir' },
    { label: 'Sakit', value: 'sakit' },
    { label: 'Ijin', value: 'ijin' },
    { label: 'Alfa', value: 'alfa' },
];

onMounted(() => {
    // Set selected Jadwal label
    const selectedJadwal = props.jadwalPelajaranOptions.find((j) => j.id === props.absensi.jadwal_id);
    selectedJadwalLabel.value = selectedJadwal ? selectedJadwal.label : 'Pilih Jadwal';

    // Set selected Siswa label
    const selectedSiswa = props.siswaOptions.find((s) => s.id === props.absensi.siswa_id);
    selectedSiswaLabel.value = selectedSiswa ? selectedSiswa.nama : 'Pilih Siswa';

    // Set selected Status label
    const selectedStatus = statusOptions.find((s) => s.value === props.absensi.status);
    selectedStatusLabel.value = selectedStatus ? selectedStatus.label : 'Pilih Status';

    // Set selected Semester label
    const selectedSemester = props.semesterDanTahunAjaranList.find((s) => s.id === props.absensi.semester_ajaran_id);
    selectedSemesterLabel.value = selectedSemester
        ? `${selectedSemester.semester} / ${selectedSemester.tahun_ajaran}`
        : 'Pilih Semester & Tahun Ajaran';

    // Set selected Kelas label based on jadwal's kelas
    if (selectedJadwal) {
        // Extract kelas name from jadwal label (format: "mapel | kelas | hari | jam")
        const parts = selectedJadwal.label.split(' | ');
        selectedKelasLabel.value = parts.length > 1 ? parts[1] : 'Pilih Kelas';
    } else {
        selectedKelasLabel.value = 'Pilih Kelas';
    }

    // Set selected Mapel label based on jadwal's mapel
    if (selectedJadwal) {
        const parts = selectedJadwal.label.split(' | ');
        selectedMapelLabel.value = parts.length > 0 ? parts[0] : 'Pilih Mata Pelajaran';
    } else {
        selectedMapelLabel.value = 'Pilih Mata Pelajaran';
    }
});

const submit = () => {
    form.put(route('guru.absensi.update', props.absensi.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Edit Absensi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Absensi</h1>

            <form class="grid grid-cols-1 gap-4 md:max-w-3xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Jadwal  -->
                <div class="flex flex-col gap-3">
                    <Label for="jadwal_id">Jadwal</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="truncate text-sm">{{ selectedJadwalLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="jadwal in jadwalPelajaranOptions"
                                :key="jadwal.id"
                                @click="
                                    form.jadwal_id = jadwal.id;
                                    selectedJadwalLabel = jadwal.label;
                                "
                            >
                                {{ jadwal.label }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jadwal_id" />
                </div>

                <!-- Kelas -->
                <div class="flex flex-col gap-2">
                    <Label>Kelas</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full justify-between rounded-md border px-3 py-2 text-sm">
                                {{ selectedKelasLabel }}
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 overflow-auto">
                            <DropdownMenuItem
                                v-for="k in props.kelasOptions"
                                :key="k.id"
                                @click="
                                    selectedKelasId = k.id;
                                    selectedKelasLabel = k.nama_kelas;
                                "
                                >{{ k.nama_kelas }}</DropdownMenuItem
                            >
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jadwal_id" />
                </div>

                <!-- Mata Pelajaran -->
                <div class="flex flex-col gap-2">
                    <Label>Mata Pelajaran</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full justify-between rounded-md border px-3 py-2 text-sm">
                                {{ selectedMapelLabel }}
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 overflow-auto">
                            <DropdownMenuItem
                                v-for="m in props.mapelOptions"
                                :key="m.id"
                                @click="
                                    selectedMapelId = m.id;
                                    selectedMapelLabel = m.nama_mapel;
                                "
                                >{{ m.nama_mapel }}</DropdownMenuItem
                            >
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jadwal_id" />
                </div>

                <!-- Siswa -->
                <div class="flex flex-col gap-2">
                    <Label>Siswa</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full justify-between rounded-md border px-3 py-2 text-sm">
                                {{ selectedSiswaLabel }}
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 overflow-auto">
                            <DropdownMenuItem
                                v-for="s in props.siswaOptions"
                                :key="s.id"
                                @click="
                                    form.siswa_id = s.id;
                                    selectedSiswaLabel = s.nama;
                                "
                                >{{ s.nama }}</DropdownMenuItem
                            >
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.siswa_id" />
                </div>

                <!-- Pertemuan -->
                <div class="flex flex-col gap-2">
                    <Label>Pertemuan Ke</Label>
                    <Input
                        type="number"
                        min="1"
                        v-model="form.pertemuan_ke"
                        class="rounded-md border px-3 py-2"
                        placeholder="Masukkan pertemuan ke-"
                    />
                    <InputError :message="form.errors.pertemuan_ke" />
                </div>

                <!-- Tanggal -->
                <div class="flex flex-col gap-2">
                    <Label>Tanggal</Label>
                    <Input type="date" v-model="form.tanggal" class="rounded-md border px-3 py-2" />
                    <InputError :message="form.errors.tanggal" />
                </div>

                <!-- Status -->
                <div class="flex flex-col gap-2">
                    <Label>Status</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full justify-between rounded-md border px-3 py-2 text-sm">
                                {{ selectedStatusLabel }}
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 overflow-auto">
                            <DropdownMenuItem
                                v-for="s in statusOptions"
                                :key="s.value"
                                @click="
                                    form.status = s.value;
                                    selectedStatusLabel = s.label;
                                "
                                >{{ s.label }}</DropdownMenuItem
                            >
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.status" />
                </div>

                <!-- Semester & Tahun Ajaran -->
                <div class="flex flex-col gap-2">
                    <Label>Semester & Tahun Ajaran</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full justify-between rounded-md border px-3 py-2 text-sm">
                                {{ selectedSemesterLabel }}
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 overflow-auto">
                            <DropdownMenuItem
                                v-for="sa in props.semesterDanTahunAjaranList"
                                :key="sa.id"
                                @click="
                                    form.semester_ajaran_id = sa.id;
                                    selectedSemesterLabel = `${sa.semester} / ${sa.tahun_ajaran}`;
                                "
                                >{{ sa.semester }} / {{ sa.tahun_ajaran }}</DropdownMenuItem
                            >
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.semester_ajaran_id" />
                </div>

                <!-- Submit -->
                <div class="mt-4 flex gap-2 md:col-span-2">
                    <Button variant="outline" type="button" @click="router.visit(route('guru.absensi.index'))">Batal</Button>
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Update
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
