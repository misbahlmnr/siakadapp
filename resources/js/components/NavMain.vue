<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    items: NavItem[];
}>();
const items = props.items;

const page = usePage();

// helper active
const isActive = (href: string): boolean => page.url === href;
const isChildActive = (children: NavItem[]) => {
    return children.some((child) => isActive(child.href));
};

// submenu state
const expandedItems = ref<string[]>([]);

const toggleExpand = (title: string) => {
    if (expandedItems.value.includes(title)) {
        expandedItems.value = expandedItems.value.filter((t) => t !== title);
    } else {
        expandedItems.value.push(title);
    }
};

const isExpanded = (title: string) => {
    return expandedItems.value.includes(title);
};

// expand otomatis jika route aktif
onMounted(() => {
    items.forEach((item) => {
        if (item.children && isChildActive(item.children)) {
            expandedItems.value.push(item.title);
        }
    });
});
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Menu</SidebarGroupLabel>

        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <!-- MENU DENGAN SUBMENU -->
                <template v-if="item.children">
                    <SidebarMenuButton
                        as-child
                        :is-active="isActive(item.href) || isChildActive(item.children)"
                        :tooltip="item.title"
                        @click="toggleExpand(item.title)"
                    >
                        <div class="flex w-full items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                            </div>
                            <svg
                                :class="['h-4 w-4 transition-transform', isExpanded(item.title) ? 'rotate-90' : 'rotate-0']"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </SidebarMenuButton>

                    <SidebarMenuSub v-show="isExpanded(item.title)">
                        <SidebarMenuSubItem v-for="child in item.children" :key="child.title">
                            <SidebarMenuSubButton as-child :is-active="isActive(child.href)" :tooltip="child.title">
                                <Link :href="child.href">
                                    <span>{{ child.title }}</span>
                                </Link>
                            </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                    </SidebarMenuSub>
                </template>

                <!-- MENU BIASA (tanpa children) -->
                <template v-else>
                    <SidebarMenuButton as-child :is-active="isActive(item.href)" :tooltip="item.title">
                        <Link :href="item.href">
                            <div class="flex items-center gap-2">
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                            </div>
                        </Link>
                    </SidebarMenuButton>
                </template>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
