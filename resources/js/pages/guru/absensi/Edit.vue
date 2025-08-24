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
import { computed, ref } from 'vue';

const props = defineProps<{
    absensi: any;
    kelasOptions: Kelas[];
    mapelOptions: MatPel[];
    siswaOptions: { id: number; nama: string }[];
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

const selectedKelasLabel = ref(props.kelasOptions.find((k) => k.id === props.absensi.jadwal_id)?.nama_kelas || 'Pilih Kelas');
const selectedMapelLabel = ref(props.mapelOptions.find((m) => m.id === props.absensi.jadwal_id)?.nama_mapel || 'Pilih Mata Pelajaran');
const selectedSiswaLabel = ref(props.siswaOptions.find((s) => s.id === props.absensi.siswa_id)?.nama || 'Pilih Siswa');
const selectedStatusLabel = ref(form.status.charAt(0).toUpperCase() + form.status.slice(1));
const selectedSemesterLabel = computed(() => {
    const sa = props.semesterDanTahunAjaranList.find((s) => s.id === form.semester_ajaran_id);
    return sa ? `${sa.semester} / ${sa.tahun_ajaran}` : 'Pilih Semester & Tahun Ajaran';
});

const statusOptions = [
    { label: 'Hadir', value: 'hadir' },
    { label: 'Sakit', value: 'sakit' },
    { label: 'Ijin', value: 'ijin' },
    { label: 'Alfa', value: 'alfa' },
];

const submit = () => {
    form.put(route('guru.absensi.update', props.absensi.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Edit Absensi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Absensi</h1>

            <form class="grid grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
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
                                    form.jadwal_id = k.id;
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
                                    form.jadwal_id = m.id;
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
                            <DropdownMenuItem v-for="sa in props.semesterDanTahunAjaranList" :key="sa.id" @click="form.semester_ajaran_id = sa.id"
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
