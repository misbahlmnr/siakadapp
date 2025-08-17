<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    role: string;
    guru: {
        id: number;
        name: string;
        email: string;
        nip: string | null;
        jenis_kelamin: string | null;
        tempat_lahir: string | null;
        tanggal_lahir: string | null;
        alamat: string | null;
        no_hp: string | null;
        status_kepegawaian: string | null;
        guru_profile: any;
    };
}>();

type Form = {
    name: string;
    email: string;
    password?: string;
    password_confirmation?: string;
    nip: string | null;
    jenis_kelamin: string | null;
    tempat_lahir: string | null;
    tanggal_lahir: string | null;
    alamat: string | null;
    no_hp: string | null;
    status_kepegawaian: string | null;
    role: string;
};

const form = useForm<Form>({
    name: props.guru.name,
    email: props.guru.email,
    password: '',
    password_confirmation: '',
    nip: props.guru.nip,
    jenis_kelamin: props.guru.jenis_kelamin,
    tempat_lahir: props.guru.tempat_lahir,
    tanggal_lahir: props.guru.tanggal_lahir,
    alamat: props.guru.alamat,
    no_hp: props.guru.no_hp,
    status_kepegawaian: props.guru.status_kepegawaian,
    role: 'guru',
});

const selectedStatusGuruLabel = ref(
    props.guru.status_kepegawaian === 'pns' ? 'PNS' : props.guru.status_kepegawaian === 'honorer' ? 'Honorer' : 'Pilih Status',
);

const selectedJenisKelaminLabel = ref(
    props.guru.jenis_kelamin === 'L' ? 'Laki-laki' : props.guru.jenis_kelamin === 'P' ? 'Perempuan' : 'Pilih Jenis Kelamin',
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Data Guru', href: route('admin.users.index', props.role) },
    { title: 'Edit Data Guru', href: route('admin.users.edit', { role: props.role, id: props.guru.id }) },
];

console.log(props.guru);

const submit = () => {
    form.put(route('admin.users.update', { role: props.role, id: props.guru?.guru_profile.user_id }));
};
</script>

<template>
    <Head title="Edit Data Guru" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Data Guru</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Nama -->
                <div class="flex flex-col gap-3">
                    <Label for="name">Nama</Label>
                    <Input id="name" v-model="form.name" placeholder="Masukkan nama lengkap" />
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-3">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" v-model="form.email" placeholder="Masukkan email" />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Password (opsional) -->
                <div class="flex flex-col gap-3">
                    <Label for="password">Password Baru</Label>
                    <Input id="password" type="password" v-model="form.password" placeholder="Kosongkan jika tidak diganti" />
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Konfirmasi Password -->
                <div class="flex flex-col gap-3">
                    <Label for="password_confirmation">Konfirmasi Password</Label>
                    <Input id="password_confirmation" type="password" v-model="form.password_confirmation" placeholder="Ulangi password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <!-- NIP -->
                <div class="flex flex-col gap-3">
                    <Label for="nip">NIP</Label>
                    <Input id="nip" v-model="form.nip" placeholder="Masukkan NIP" />
                    <InputError :message="form.errors.nip" />
                </div>

                <!-- Jenis Kelamin -->
                <div class="flex flex-col gap-3">
                    <Label>Jenis Kelamin</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedJenisKelaminLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="min-w-[200px]">
                            <DropdownMenuItem
                                @click="
                                    form.jenis_kelamin = 'L';
                                    selectedJenisKelaminLabel = 'Laki-laki';
                                "
                            >
                                Laki-Laki
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                @click="
                                    form.jenis_kelamin = 'P';
                                    selectedJenisKelaminLabel = 'Perempuan';
                                "
                            >
                                Perempuan
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jenis_kelamin" />
                </div>

                <!-- Tempat lahir -->
                <div class="flex flex-col gap-3">
                    <Label for="tempat_lahir">Tempat Lahir</Label>
                    <Input id="tempat_lahir" v-model="form.tempat_lahir" placeholder="Masukkan Tempat lahir" />
                    <InputError :message="form.errors.tempat_lahir" />
                </div>

                <!-- Tanggal lahir -->
                <div class="flex flex-col gap-3">
                    <Label for="tanggal_lahir">Tanggal Lahir</Label>
                    <Input id="tanggal_lahir" type="date" v-model="form.tanggal_lahir" />
                    <InputError :message="form.errors.tanggal_lahir" />
                </div>

                <!-- No HP -->
                <div class="flex flex-col gap-3">
                    <Label for="no_hp">No HP</Label>
                    <Input id="no_hp" v-model="form.no_hp" placeholder="08xxxx" />
                    <InputError :message="form.errors.no_hp" />
                </div>

                <!-- Status Kepegawaian -->
                <div class="flex flex-col gap-3">
                    <Label>Status Kepegawaian</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedStatusGuruLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="min-w-[200px]">
                            <DropdownMenuItem
                                @click="
                                    form.status_kepegawaian = 'pns';
                                    selectedStatusGuruLabel = 'PNS';
                                "
                            >
                                PNS
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                @click="
                                    form.status_kepegawaian = 'honorer';
                                    selectedStatusGuruLabel = 'Honorer';
                                "
                            >
                                Honorer
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.status_kepegawaian" />
                </div>

                <!-- Alamat -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="alamat">Alamat</Label>
                    <textarea
                        id="alamat"
                        v-model="form.alamat"
                        placeholder="Masukkan alamat"
                        class="w-full rounded border p-2 text-sm"
                        rows="3"
                    ></textarea>
                    <InputError :message="form.errors.alamat" />
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button variant="outline" type="button" @click="router.visit(route('admin.users.index', 'guru'))">Batal</Button>
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
