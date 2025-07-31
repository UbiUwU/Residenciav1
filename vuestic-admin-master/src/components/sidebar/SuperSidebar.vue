<template>
  <VaSidebar 
    v-model="writableVisible" 
    :width="sidebarWidth" 
    :color="color" 
    minimized-width="0"
    class="original-sidebar"
  >
    <VaAccordion v-model="value" multiple>
      <VaCollapse 
        v-for="(route, index) in SuperRoutes" 
        :key="index"
        class="sidebar-collapse"
      >
        <template #header="{ value: isCollapsed }">
          <VaSidebarItem
            :to="route.children ? undefined : { name: route.name }"
            :active="routeHasActiveChild(route)"
            :active-color="activeColor"
            :text-color="textColor(route)"
            :aria-label="`${route.children ? 'Open category ' : 'Visit'} ${$t(route.displayName)}`"
            role="button"
            hover-opacity="0.10"
          >
            <VaSidebarItemContent class="py-3 pr-2 pl-4 sidebar-item-content">
              <VaIcon
                v-if="route.meta.icon"
                aria-hidden="true"
                :name="route.meta.icon"
                size="20px"
                :color="iconColor(route)"
              />
              <VaSidebarItemTitle class="flex justify-between items-center leading-5 font-semibold sidebar-item-title">
                {{ $t(route.displayName) }}
                <VaIcon 
                  v-if="route.children" 
                  :name="arrowDirection(isCollapsed)" 
                  size="20px" 
                  class="sidebar-arrow"
                />
              </VaSidebarItemTitle>
            </VaSidebarItemContent>
          </VaSidebarItem>
        </template>

        <template #body>
          <div 
            v-for="(childRoute, index2) in route.children" 
            :key="index2"
            class="sidebar-subitem-wrapper"
          >
            <VaSidebarItem
              :to="{ name: childRoute.name }"
              :active="isRouteActive(childRoute.name)"
              :active-color="activeColor"
              :text-color="textColor(childRoute)"
              :aria-label="`Visit ${$t(route.displayName)}`"
              hover-opacity="0.10"
              class="sidebar-subitem"
            >
              <VaSidebarItemContent class="py-3 pr-2 pl-11">
                <VaSidebarItemTitle class="leading-5 font-semibold">
                  {{ $t(childRoute.displayName) }}
                </VaSidebarItemTitle>
              </VaSidebarItemContent>
            </VaSidebarItem>
          </div>
        </template>
      </VaCollapse>
    </VaAccordion>
  </VaSidebar>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useColors } from 'vuestic-ui'
import { useI18n } from 'vue-i18n'
import { SuperRoutes } from './SuperRoutes'

const props = defineProps({
  visible: { type: Boolean, default: true },
  mobile: { type: Boolean, default: false }
})

const emit = defineEmits(['update:visible'])

const { t } = useI18n()
const route = useRoute()
const { getColor, colorToRgba } = useColors()

// Estado reactivo
const value = ref<boolean[]>([])

// Computed
const writableVisible = computed({
  get: () => props.visible,
  set: (v: boolean) => emit('update:visible', v)
})

const sidebarWidth = computed(() => props.mobile ? '100vw' : '280px')
const color = computed(() => getColor('background-secondary'))
const activeColor = computed(() => colorToRgba(getColor('focus'), 0.1))

// Métodos
const isRouteActive = (name: string) => route.name === name

const routeHasActiveChild = (route: any) => {
  if (!route.children) return isRouteActive(route.name)
  return route.children.some((child: any) => isRouteActive(child.name))
}

const iconColor = (route: any) => routeHasActiveChild(route) ? 'primary' : 'secondary'
const textColor = (route: any) => routeHasActiveChild(route) ? 'primary' : 'textPrimary'
const arrowDirection = (state: boolean) => state ? 'va-arrow-up' : 'va-arrow-down'

const setActiveExpand = () => {
  value.value = SuperRoutes.map(route => routeHasActiveChild(route))
}

// Watchers
watch(() => route.fullPath, setActiveExpand, { immediate: true })
</script>

<style lang="scss" scoped>
.original-sidebar {
  background: white;
  border-right: 1px solid #e2e2e2;

  .va-sidebar-item {
    &--active {
      background-color: rgba(52, 140, 212, 0.1);
    }

    &:hover:not(.va-sidebar-item--active) {
      background-color: rgba(0, 0, 0, 0.05);
    }
  }

  .sidebar {
    &-collapse {
      margin-bottom: 4px;
    }

    &-item {
      &-content {
        transition: all 0.2s ease;
      }

      &-title {
        color: inherit;
        transition: color 0.2s ease;
      }
    }

    &-subitem {
      &-wrapper {
        margin-left: 8px;
      }

      .va-sidebar-item-content {
        padding-left: 44px !important;
      }
    }

    &-arrow {
      color: var(--va-secondary);
      transition: transform 0.2s ease, color 0.2s ease;
    }
  }
}
</style>