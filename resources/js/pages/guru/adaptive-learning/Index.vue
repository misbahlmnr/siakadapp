<template>
    <Head title="Adaptive Learning" />
    <AppLayout>
        <div class="flex flex-col gap-6">
            <h1 class="text-2xl font-bold">Adaptive Learning</h1>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Card Daftar Rekomendasi -->
                <div class="rounded-lg bg-white p-6 shadow transition-shadow hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-blue-100 p-3">
                            <Sparkles class="h-8 w-8 text-blue-600" />
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold">Daftar Rekomendasi</h2>
                            <p class="text-sm text-gray-600">Kelola rekomendasi materi untuk siswa</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <Button
                            class="w-full bg-blue-600 text-white hover:bg-blue-700"
                            @click="router.visit(route('guru.adaptive-learning.rekomendasi.index'))"
                        >
                            Lihat Daftar
                        </Button>
                    </div>
                </div>

                <!-- Card Progress Siswa -->
                <div class="rounded-lg bg-white p-6 shadow transition-shadow hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-green-100 p-3">
                            <BarChart class="h-8 w-8 text-green-600" />
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold">Progress Siswa</h2>
                            <p class="text-sm text-gray-600">Monitor progress belajar siswa</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <Button
                            class="w-full bg-green-600 text-white hover:bg-green-700"
                            @click="router.visit(route('guru.adaptive-learning.progress-siswa.index'))"
                        >
                            Lihat Progress
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { BarChart, Sparkles } from 'lucide-vue-next';
</script>
