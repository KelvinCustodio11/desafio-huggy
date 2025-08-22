<template>
  <CardTitle>Relatórios</CardTitle>
  <CardBase>
    <button class="back-btn" @click="$router.back()"><IconLeft /> Voltar</button>
    <Loader v-if="loading" />
    <div v-else>
      <div class="chart-section">
        <CardHeader>Segmentação por estado</CardHeader>
        <VueApexCharts
          width="350"
          type="pie"
          :options="stateChartOptions"
          :series="stateSeries"
        />
      </div>
      <div class="chart-section">
        <CardHeader>Segmentação por cidade</CardHeader>
        <VueApexCharts
          width="350"
          type="pie"
          :options="cityChartOptions"
          :series="citySeries"
        />
      </div>
      <div class="chart-section">
        <CardHeader>Segmentação por faixa etária</CardHeader>
        <VueApexCharts
          width="350"
          type="pie"
          :options="ageChartOptions"
          :series="ageSeries"
        />
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
const stateChartOptions = computed(() => ({
  labels: reportsClients.value.clients_by_state.map(item => item.state || 'Não informado'),
  legend: { position: 'right' }
}))

const citySeries = computed(() => reportsClients.value.clients_by_city.map(item => item.total))
const cityChartOptions = computed(() => ({
  labels: reportsClients.value.clients_by_city.map(item => item.city || 'Não informado'),
  legend: { position: 'right' }
}))

const ageSeries = computed(() => reportsClients.value.clients_by_age.map(item => item.total))
const ageChartOptions = computed(() => ({
  labels: reportsClients.value.clients_by_age.map(item => item.age || 'Não informado'),
  legend: { position: 'right' }
}))
</script>

<style scoped>
.back-btn {
  background: none;
  border: none;
  color: #505050;
  font-size: 1rem;
  cursor: pointer;
  margin-bottom: 16px;
}
.chart-section {
  margin: 24px;
}
.loading {
  text-align: center;
  color: #505050;
  font-size: 1.1rem;
}
</style>
