<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{
    jadwal_id: number;
    guru_id: number;
}>();

type Form = {
    jadwal_id: number | null;
    guru_id: number | null;
    pertemuan_ke: number | string | undefined;
    judul: string;
    deskripsi: string;
    file_path: File | null;
    link_file: string;
};

const form = useForm<Form>({
    jadwal_id: props.jadwal_id,
    guru_id: props.guru_id,
    pertemuan_ke: '',
    judul: '',
    deskripsi: '',
    file_path: null,
    link_file: '',
});

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.file_path = target.files[0];
    } else {
        form.file_path = null;
    }
};

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
            { title: `Materi Pembelajaran`, href: route('guru.jadwal-mengajar.materi.index', { jadwal_id: props.jadwal_id }) },
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
                    <Input id="judul" v-model="form.judul" placeholder="Masukkan judul materi" />
                    <InputError :message="form.errors.judul" />
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
                    <Label for="file_path">File (ppt, pdf, doc)</Label>
                    <Input id="file_path" type="file" @change="onFileChange" />
                    <InputError :message="form.errors.file_path" />
                </div>

                <!-- Link File -->
                <div class="flex flex-col gap-3">
                    <Label for="link_file">Link File</Label>
                    <Input id="link_file" v-model="form.link_file" placeholder="Masukkan link file (jika ada)" />
                    <InputError :message="form.errors.link_file" />
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
