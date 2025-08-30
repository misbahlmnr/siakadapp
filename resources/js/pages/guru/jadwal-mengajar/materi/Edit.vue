<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { MateriPelajaran } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, File, FileDown, LoaderCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    jadwal_id: number;
    guru_matpel_id: number;
    materi: MateriPelajaran;
    semesterDanTahunAjaranList: { id: number; semester: string; tahun_ajaran: string }[];
}>();

// Form
const form = useForm({
    jadwal_id: props.jadwal_id,
    guru_matpel_id: props.guru_matpel_id,
    semester_ajaran_id: props.materi?.semester_ajaran_id || null,
    pertemuan_ke: props.materi?.pertemuan_ke || '',
    judul_materi: props.materi?.judul_materi || '',
    deskripsi: props.materi?.deskripsi || '',
    file_materi: null,
    link_materi: props.materi?.link_materi || '',
});

// Label semester & tahun ajaran computed
const selectedSemesterDanTahunAjaranLabel = computed(() => {
    const sa = props.semesterDanTahunAjaranList.find((s) => s.id === form.semester_ajaran_id);
    return sa ? `${sa.semester} / ${sa.tahun_ajaran}` : 'Pilih Semester & Tahun Ajaran';
});

// File upload
const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    form.file_materi = target.files?.[0] || null;
};

// Submit
const submit = () => {
    form.transform((data) => ({ ...data, _method: 'PUT' })).post(
        route('guru.jadwal-mengajar.materi.update', { jadwal_id: props.jadwal_id, materi_id: props.materi.id }),
        { preserveScroll: true, forceFormData: true },
    );
};

// Breadcrumbs
const breadcrumbs = [
    { title: 'Beranda', href: route('guru.dashboard') },
    { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
    { title: 'Materi Pembelajaran', href: route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.jadwal_id }) },
    { title: 'Edit Materi', href: route('guru.jadwal-mengajar.materi.edit', { jadwal_id: props.jadwal_id, materi_id: props.materi.id }) },
];
</script>

<template>
    <Head title="Edit Materi Pembelajaran" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Materi Pembelajaran</h1>

            <form @submit.prevent="submit" class="grid gap-4 md:max-w-2xl md:grid-cols-2">
                <!-- Pertemuan Ke -->
                <div class="col-span-2 flex flex-col gap-2">
                    <Label for="pertemuan_ke">Pertemuan Ke</Label>
                    <Input id="pertemuan_ke" type="number" min="1" v-model="form.pertemuan_ke" placeholder="Masukkan nomor pertemuan" />
                    <InputError :message="form.errors.pertemuan_ke" />
                </div>

                <!-- Judul Materi -->
                <div class="col-span-2 flex flex-col gap-2">
                    <Label for="judul_materi">Judul Materi</Label>
                    <Input id="judul_materi" v-model="form.judul_materi" placeholder="Masukkan judul materi" />
                    <InputError :message="form.errors.judul_materi" />
                </div>

                <!-- Deskripsi -->
                <div class="col-span-2 flex flex-col gap-2">
                    <Label for="deskripsi">Deskripsi</Label>
                    <textarea
                        id="deskripsi"
                        v-model="form.deskripsi"
                        placeholder="Masukkan deskripsi"
                        class="w-full rounded border p-2 text-sm"
                        rows="4"
                    ></textarea>
                    <InputError :message="form.errors.deskripsi" />
                </div>

                <!-- File Materi -->
                <div class="flex flex-col gap-3">
                    <Label for="file_materi">File (ppt, pdf, doc)</Label>
                    <Input id="file_materi" type="file" @change="onFileChange" class="mb-2" />

                    <div
                        v-if="props.materi?.file_materi"
                        class="flex w-full max-w-md items-center justify-between gap-2 rounded-lg border border-gray-200 bg-white p-2 shadow-md transition hover:shadow-lg dark:border-gray-700 dark:bg-gray-900"
                    >
                        <File :size="18" />
                        <div class="flex-1 truncate">
                            <span class="truncate text-xs font-medium text-gray-800 dark:text-gray-200">{{
                                props.materi.file_materi.split('/').pop()
                            }}</span>
                        </div>
                        <a
                            :href="props.materi.file_materi"
                            target="_blank"
                            class="inline-flex items-center gap-1 rounded bg-blue-500 px-3 py-1 text-sm font-medium text-white shadow transition hover:bg-blue-600"
                        >
                            <FileDown :size="18" />
                            Download
                        </a>
                    </div>

                    <InputError :message="form.errors.file_materi" />
                </div>

                <!-- Link File -->
                <div class="flex flex-col gap-2">
                    <Label for="link_materi">Link File</Label>
                    <Input id="link_materi" v-model="form.link_materi" placeholder="Masukkan link file (jika ada)" />
                    <InputError :message="form.errors.link_materi" />
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
                            <DropdownMenuItem v-for="sa in props.semesterDanTahunAjaranList" :key="sa.id" @click="form.semester_ajaran_id = sa.id">
                                {{ sa.semester }} / {{ sa.tahun_ajaran }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.semester_ajaran_id" />
                </div>

                <!-- Submit -->
                <div class="col-span-2 mt-4 flex gap-2">
                    <Button
                        variant="outline"
                        type="button"
                        @click="router.visit(route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.jadwal_id }))"
                    >
                        Batal
                    </Button>
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Update
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
