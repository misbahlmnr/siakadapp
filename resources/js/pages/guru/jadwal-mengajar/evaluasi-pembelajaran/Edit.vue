<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, File, FileDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    jadwal_id: number;
    guru_id: number;
    nama_mapel: string;
    evaluasi: {
        jadwal_id: number;
        guru_id: number;
        id: number;
        judul: string;
        deskripsi: string;
        jenis: 'tugas' | 'kuis' | 'ujian';
        tahun_ajaran: string;
        semester: string;
        waktu_mulai: string;
        waktu_selesai: string;
        link_soal: string | null;
        file_soal: string | null;
    };
}>();

type Form = {
    jadwal_id: number | null;
    guru_id: number | null;
    judul: string;
    deskripsi: string;
    jenis: string;
    tahun_ajaran: string;
    semester: string;
    waktu_mulai: string;
    waktu_selesai: string;
    link_soal: string;
    file_soal: File | null;
};

const form = useForm<Form>({
    jadwal_id: props.jadwal_id,
    guru_id: props.guru_id,
    judul: props.evaluasi.judul,
    deskripsi: props.evaluasi.deskripsi,
    jenis: props.evaluasi.jenis,
    tahun_ajaran: props.evaluasi.tahun_ajaran,
    semester: props.evaluasi.semester,
    waktu_mulai: props.evaluasi.waktu_mulai,
    waktu_selesai: props.evaluasi.waktu_selesai,
    link_soal: props.evaluasi.link_soal ?? '',
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

const semesterOptions = ['Ganjil', 'Genap'];
const jenisOptions = ['tugas', 'kuis', 'ujian', 'lainnya'];

const selectedSemesterLabel = ref(props.evaluasi.semester || 'Pilih Semester');
const selectedJenisLabel = ref(props.evaluasi.jenis || 'Pilih Jenis');

const submit = () => {
    form.transform((data) => ({ ...data, _method: 'PUT' })).post(
        route('guru.jadwal-mengajar.evaluasi-pembelajaran.update', { jadwal_id: props.jadwal_id, evaluasi_id: props.evaluasi.id }),
        { preserveScroll: true, forceFormData: true },
    );
};
</script>

<template>
    <Head title="Edit Evaluasi Pembelajaran" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
            { title: 'Evaluasi Pembelajaran', href: route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', { jadwal_id: props.jadwal_id }) },
            {
                title: 'Edit Evaluasi',
                href: route('guru.jadwal-mengajar.evaluasi-pembelajaran.edit', { jadwal_id: props.jadwal_id, evaluasi_id: props.evaluasi.id }),
            },
        ]"
    >
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Evaluasi Pembelajaran - {{ props.nama_mapel }}</h1>

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

                <!-- Waktu Mulai -->
                <div class="flex flex-col gap-3">
                    <Label for="waktu_mulai">Waktu Mulai</Label>
                    <Input id="waktu_mulai" type="datetime-local" v-model="form.waktu_mulai" />
                    <InputError :message="form.errors.waktu_mulai" />
                </div>

                <!-- Waktu Selesai -->
                <div class="flex flex-col gap-3">
                    <Label for="waktu_selesai">Waktu Selesai</Label>
                    <Input id="waktu_selesai" type="datetime-local" v-model="form.waktu_selesai" />
                    <InputError :message="form.errors.waktu_selesai" />
                </div>

                <!-- File Soal -->
                <div class="flex flex-col gap-3">
                    <Label for="file_soal">File Soal (ppt, pdf, doc)</Label>
                    <Input id="file_soal" type="file" @change="onFileChange" />
                    <div
                        v-if="props.evaluasi?.file_soal"
                        class="flex w-full max-w-md items-center justify-between gap-2 rounded-lg border border-gray-200 bg-white p-2 shadow-md transition hover:shadow-lg dark:border-gray-700 dark:bg-gray-900"
                    >
                        <!-- Icon File -->
                        <File :size="18" />

                        <!-- Nama File -->
                        <div class="flex-1 truncate">
                            <span class="truncate text-xs font-medium text-gray-800 dark:text-gray-200">{{
                                props.evaluasi.file_soal.split('/').pop()
                            }}</span>
                        </div>

                        <!-- Download Button -->
                        <a
                            :href="props.evaluasi.file_soal"
                            target="_blank"
                            class="inline-flex items-center gap-1 rounded bg-blue-500 px-3 py-1 text-xs font-medium text-white shadow transition hover:bg-blue-600"
                        >
                            <FileDown :size="18" />
                            Download
                        </a>
                    </div>
                    <InputError :message="form.errors.file_soal" />
                </div>

                <!-- Link Soal -->
                <div class="flex flex-col gap-3">
                    <Label for="link_soal">Link Soal</Label>
                    <Input id="link_soal" v-model="form.link_soal" placeholder="Masukkan link soal (jika ada)" />
                    <InputError :message="form.errors.link_soal" />
                </div>

                <!-- Semester -->
                <div class="flex flex-col gap-3">
                    <Label for="semester">Semester</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedSemesterLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="s in semesterOptions"
                                :key="s"
                                @click="
                                    form.semester = s;
                                    selectedSemesterLabel = s;
                                "
                            >
                                {{ s }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.semester" />
                </div>

                <!-- Tahun Ajaran -->
                <div class="flex flex-col gap-3">
                    <Label for="tahun_ajaran">Tahun Ajaran</Label>
                    <Input id="tahun_ajaran" v-model="form.tahun_ajaran" placeholder="Masukkan tahun ajaran" />
                    <InputError :message="form.errors.tahun_ajaran" />
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button
                        variant="outline"
                        type="button"
                        @click="router.visit(route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', { jadwal_id: jadwal_id }))"
                        >Batal</Button
                    >
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
