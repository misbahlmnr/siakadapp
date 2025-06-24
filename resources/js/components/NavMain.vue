<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import SidebarMenuSub from './ui/sidebar/SidebarMenuSub.vue';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();

const isChildActive = (children: NavItem[]) => {
    return children.some((child) => child.href === page.url);
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Menu</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <!-- Item Utama -->
                <div v-if="item.children" class="space-y-1">
                    <!-- Parent Button -->
                    <SidebarMenuButton as-child :is-active="item.href === page.url || isChildActive(item.children)" :tooltip="item.title">
                        <div class="flex items-center gap-2">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </div>
                    </SidebarMenuButton>

                    <!-- Children -->
                    <SidebarMenuSub>
                        <SidebarMenuSubItem v-for="child in item.children" :key="child.title">
                            <SidebarMenuSubButton as-child :is-active="child.href === page.url" :tooltip="child.title">
                                <Link :href="child.href">
                                    <span>{{ child.title }}</span>
                                </Link>
                            </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                    </SidebarMenuSub>
                </div>

                <!-- Item biasa -->
                <SidebarMenuButton v-else as-child :is-active="item.href === page.url" :tooltip="item.title">
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
