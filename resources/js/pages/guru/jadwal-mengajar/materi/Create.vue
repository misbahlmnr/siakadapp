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
    semesterDanTahunAjaranList: { id: number; semester: string; tahun_ajaran: string }[];
}>();

type Form = {
    jadwal_id: number | null;
    guru_id: number | null;
    semester_ajaran_id: number | null;
    pertemuan_ke: number | string | undefined;
    judul_materi: string;
    deskripsi: string;
    file_materi: File | null;
    link_materi: string;
};

const form = useForm<Form>({
    jadwal_id: props.jadwal_id,
    guru_id: props.guru_id,
    semester_ajaran_id: null,
    pertemuan_ke: '',
    judul_materi: '',
    deskripsi: '',
    file_materi: null,
    link_materi: '',
});

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.file_materi = target.files[0];
    } else {
        form.file_materi = null;
    }
};

const selectedSemesterDanTahunAjaranLabel = ref('Pilih Semester & Tahun Ajaran');

const submit = () => {
    form.post(route('guru.jadwal-mengajar.materi.store', props.jadwal_id), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Tambah Materi Pembelajaran" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Beranda', href: route('admin.dashboard') },
            { title: 'Jadwal Mengajar', href: route('guru.jadwal-mengajar.index') },
            { title: 'Materi Pelajaran', href: route('guru.jadwal-mengajar.materi.index', props.jadwal_id) },
            { title: 'Tambah Materi', href: route('guru.jadwal-mengajar.materi.create', { jadwal_id: props.jadwal_id }) },
        ]"
    >
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Tambah Materi Pembelajaran</h1>

            <form class="grid w-full max-w-full grid-cols-1 gap-4 md:max-w-lg md:grid-cols-2" @submit.prevent="submit">
                <!-- Pertemuan Ke -->
                <div class="col-span-2 flex flex-col gap-3">
                    <Label for="pertemuan_ke">Pertemuan Ke</Label>
                    <Input id="pertemuan_ke" type="number" min="1" v-model="form.pertemuan_ke" placeholder="Masukkan nomor pertemuan" />
                    <InputError :message="form.errors.pertemuan_ke" />
                </div>

                <!-- Judul -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="judul">Judul Materi</Label>
                    <Input id="judul" v-model="form.judul_materi" placeholder="Masukkan judul materi" />
                    <InputError :message="form.errors.judul_materi" />
                </div>

                <!-- Deskripsi -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="deskripsi">Deskripsi</Label>
                    <textarea
                        id="deskripsi"
                        v-model="form.deskripsi"
                        placeholder="Masukkan deskripsi materi"
                        class="w-full rounded border p-2 text-sm"
                        rows="4"
                    ></textarea>
                    <InputError :message="form.errors.deskripsi" />
                </div>

                <!-- File Path -->
                <div class="flex flex-col gap-3">
                    <Label for="file_materi">File (ppt, pdf, doc)</Label>
                    <Input id="file_materi" type="file" @change="onFileChange" />
                    <InputError :message="form.errors.file_materi" />
                </div>

                <!-- Link File -->
                <div class="flex flex-col gap-3">
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
