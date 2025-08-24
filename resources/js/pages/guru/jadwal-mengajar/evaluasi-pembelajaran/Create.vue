<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    jadwal_id: number;
    guru_id: number;
    nama_mapel: string;
    semesterDanTahunAjaranList: { id: number; semester: string; tahun_ajaran: string }[];
}>();

type Form = {
    jadwal_id: number | null;
    guru_id: number | null;
    semester_ajaran_id: number | null;
    judul: string;
    deskripsi: string;
    jenis: string;
    waktu_mulai: string;
    waktu_selesai: string;
    link_soal: string;
    file_soal: File | null;
};

const form = useForm<Form>({
    jadwal_id: props.jadwal_id,
    guru_id: props.guru_id,
    semester_ajaran_id: null,
    judul: '',
    deskripsi: '',
    jenis: '',
    waktu_mulai: '',
    waktu_selesai: '',
    link_soal: '',
    file_soal: null,
});

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.file_soal = target.files[0];
    } else {
        form.file_soal = null;
    }
};

const jenisOptions = ['tugas', 'kuis', 'ujian', 'lainnya'];
const selectedSemesterDanTahunAjaranLabel = ref('Pilih Semester & Tahun Ajaran');
const selectedJenisLabel = ref('Pilih Jenis');

const submit = () => {
    form.post(route('guru.jadwal-mengajar.evaluasi-pembelajaran.store', props.jadwal_id), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Tambah Evaluasi Pembelajaran" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
            { title: 'Evaluasi Pembelajaran', href: route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', props.jadwal_id) },
            { title: 'Tambah Evaluasi', href: route('guru.jadwal-mengajar.evaluasi-pembelajaran.create', { jadwal_id: props.jadwal_id }) },
        ]"
    >
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Tambah Evaluasi Pembelajaran - {{ props.nama_mapel }}</h1>

            <form class="grid w-full max-w-full grid-cols-1 gap-4 md:max-w-lg md:grid-cols-2" @submit.prevent="submit">
                <!-- Judul -->
                <div class="col-span-2 flex flex-col gap-3">
                    <Label for="judul">Judul Evaluasi</Label>
                    <Input id="judul" v-model="form.judul" placeholder="Masukkan judul evaluasi" />
                    <InputError :message="form.errors.judul" />
                </div>

                <!-- Deskripsi -->
                <div class="col-span-2 flex flex-col gap-3">
                    <Label for="deskripsi">Deskripsi</Label>
                    <textarea
                        id="deskripsi"
                        v-model="form.deskripsi"
                        placeholder="Masukkan deskripsi evaluasi"
                        class="w-full rounded border p-2 text-sm"
                        rows="4"
                    ></textarea>
                    <InputError :message="form.errors.deskripsi" />
                </div>

                <!-- Jenis -->
                <div class="flex flex-col gap-3">
                    <Label for="jenis">Jenis</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedJenisLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="j in jenisOptions"
                                :key="j"
                                @click="
                                    form.jenis = j;
                                    selectedJenisLabel = j;
                                "
                            >
                                {{ j }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jenis" />
                </div>

                <!-- Tanggal Mulai -->
                <div class="flex flex-col gap-3">
                    <Label for="waktu_mulai">Waktu Mulai</Label>
                    <Input id="waktu_mulai" type="datetime-local" v-model="form.waktu_mulai" />
                    <InputError :message="form.errors.waktu_mulai" />
                </div>

                <!-- Tanggal Selesai -->
                <div class="flex flex-col gap-3">
                    <Label for="waktu_selesai">Waktu Selesai</Label>
                    <Input id="waktu_selesai" type="datetime-local" v-model="form.waktu_selesai" />
                    <InputError :message="form.errors.waktu_selesai" />
                </div>

                <!-- File Soal -->
                <div class="flex flex-col gap-3">
                    <Label for="file_soal">File Soal (ppt, pdf, doc)</Label>
                    <Input id="file_soal" type="file" @change="onFileChange" />
                    <InputError :message="form.errors.file_soal" />
                </div>

                <!-- Link Soal -->
                <div class="flex flex-col gap-3">
                    <Label for="link_soal">Link Soal</Label>
                    <Input id="link_soal" v-model="form.link_soal" placeholder="Masukkan link soal (jika ada)" />
                    <InputError :message="form.errors.link_soal" />
                </div>

                <!-- Semester & Tahun Ajaran -->
                <div class="flex flex-col gap-3">
                    <Label for="semester_ajaran">Semester & Tahun Ajaran</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedSemesterDanTahunAjaranLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="sa in props.semesterDanTahunAjaranList"
                                :key="sa.id"
                                @click="
                                    form.semester_ajaran_id = sa.id;
                                    selectedSemesterDanTahunAjaranLabel = `${sa.semester} - ${sa.tahun_ajaran}`;
                                "
                            >
                                {{ sa.semester }} - {{ sa.tahun_ajaran }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.semester_ajaran_id" />
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
