<template>
  <CardTitle>Relatórios</CardTitle>
  <CardBase class="md:w-[750px] w-[90vw]">
    <button class="flex items-center gap-2 text-gray-600 text-base mb-4 hover:text-indigo-600 transition-colors" @click="$router.back()">
      <IconLeft /> Voltar
    </button>
    <Loader v-if="loading" />
    <div v-else-if="stateSeries.length || citySeries.length || ageSeries.length" class="flex flex-col md:flex-row md:flex-wrap gap-6 justify-center items-stretch">
      <div v-if="stateSeries.length" class="flex-1 min-w-[320px] max-w-md mx-auto bg-white rounded-xl shadow p-4 flex flex-col items-center">
        <CardHeader>Segmentação por estado</CardHeader>
        <VueApexCharts
          width="100%"
          height="350"
          type="pie"
          :options="stateChartOptions"
          :series="stateSeries"
        />
      </div>
      <div v-if="citySeries.length" class="flex-1 min-w-[320px] max-w-md mx-auto bg-white rounded-xl shadow p-4 flex flex-col items-center">
        <CardHeader>Segmentação por cidade</CardHeader>
        <VueApexCharts
          width="100%"
          height="350"
          type="pie"
          :options="cityChartOptions"
          :series="citySeries"
        />
      </div>
      <div v-if="ageSeries.length" class="flex-1 min-w-[320px] max-w-md mx-auto bg-white rounded-xl shadow p-4 flex flex-col items-center">
        <CardHeader>Segmentação por faixa etária</CardHeader>
        <VueApexCharts
          width="100%"
          height="350"
          type="pie"
          :options="ageChartOptions"
          :series="ageSeries"
        />
      </div>
      <div v-else class="w-full flex flex-col items-center justify-center min-h-[200px] text-gray-500 text-lg">
        Nenhum relatório disponível no momento.
      </div>
    </div>
  </CardBase>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { ReportClientData, ReportsService } from '@/services/reports'
import VueApexCharts from 'vue3-apexcharts'
import CardBase from '@/components/ui/CardBase.vue'
import CardTitle from '@/components/ui/CardTitle.vue'
import IconLeft from '@/components/icons/IconLeft.vue'
import CardHeader from '@/components/ui/CardHeader.vue'
import Loader from '@/components/ui/Loader.vue'

const loading = ref(true)

const reportsClients = ref<ReportClientData>({
  clients_by_state: [],
  clients_by_city: [],
  clients_by_age: [],
})

async function fetchReport() {
  loading.value = true
  const { data } = await ReportsService.getClients()
  reportsClients.value = data as ReportClientData
  loading.value = false
}

onMounted(fetchReport)

const stateSeries = computed(() => reportsClients.value.clients_by_state.map(item => item.total))
const purplePalette = [
  '#b3aaff', // lilás claro
  '#8c7ae6', // roxo médio
  '#6c47c2', // roxo
  '#4b2991', // roxo escuro
  '#2d1457', // roxo mais escuro
  '#1a0933', // quase preto
]

const legendOptions = {
  position: 'right',
  floating: false,
  fontSize: '12px',
  fontFamily: 'inherit',
  markers: {
    width: 12,
    height: 12,
    radius: 6,
    offsetX: 0,
    offsetY: 0,
  },
  itemMargin: {
    vertical: 4,
    horizontal: 8,
  },
  onItemClick: { toggleDataSeries: true },
  onItemHover: { highlightDataSeries: true },
  formatter: undefined,
}

const stateChartOptions = computed(() => ({
  labels: reportsClients.value.clients_by_state.map(item => item.state || 'Não informado'),
  colors: purplePalette,
  legend: legendOptions,
  responsive: [
    {
      breakpoint: 768,
      options: {
        legend: {
          position: 'bottom',
          horizontalAlign: 'center',
          itemMargin: { vertical: 8, horizontal: 8 },
        },
      },
    },
  ],
}))

const citySeries = computed(() => reportsClients.value.clients_by_city.map(item => item.total))
const cityChartOptions = computed(() => ({
  labels: reportsClients.value.clients_by_city.map(item => item.city || 'Não informado'),
  colors: purplePalette,
  legend: legendOptions,
  responsive: [
    {
      breakpoint: 768,
      options: {
        legend: {
          position: 'bottom',
          horizontalAlign: 'center',
          itemMargin: { vertical: 8, horizontal: 8 },
        },
      },
    },
  ],
}))

const ageSeries = computed(() => reportsClients.value.clients_by_age.map(item => item.total))
const ageChartOptions = computed(() => ({
  labels: reportsClients.value.clients_by_age.map(item => item.age || 'Não informado'),
  colors: purplePalette,
  legend: legendOptions,
  responsive: [
    {
      breakpoint: 768,
      options: {
        legend: {
          position: 'bottom',
          horizontalAlign: 'center',
        },
      },
    },
  ],
}))
</script>
