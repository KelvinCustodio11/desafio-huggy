<template>
  <div class="reports-page">
    <div class="reports-container">
      <button class="back-btn" @click="$router.back()">&#8592; Voltar</button>
      <h2 class="reports-title">Dados sobre contatos</h2>
      <div v-if="loading" class="loading">Carregando...</div>
      <div v-else>
        <div class="chart-section">
          <h3>Segmentação por estado</h3>
          <VueApexCharts
            width="350"
            type="pie"
            :options="stateChartOptions"
            :series="stateSeries"
          />
        </div>
        <div class="chart-section">
          <h3>Segmentação por cidade</h3>
          <VueApexCharts
            width="350"
            type="pie"
            :options="cityChartOptions"
            :series="citySeries"
          />
        </div>
        <div class="chart-section">
          <h3>Segmentação por faixa etária</h3>
          <VueApexCharts
            width="350"
            type="pie"
            :options="ageChartOptions"
            :series="ageSeries"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { ReportClientData, ReportsService } from '@/services/reports'
import VueApexCharts from 'vue3-apexcharts'

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
.reports-page {
  min-height: 100vh;
  padding: 24px;
}
.reports-container {
  background: #fff;
  border-radius: 16px;
  max-width: 700px;
  margin: 32px auto;
  padding: 32px 24px;
  box-shadow: 0 2px 8px #0001;
}
.reports-title {
  font-size: 1.4rem;
  font-weight: 500;
  margin-bottom: 24px;
}
.back-btn {
  background: none;
  border: none;
  color: #505050;
  font-size: 1rem;
  cursor: pointer;
  margin-bottom: 16px;
}
.chart-section {
  margin-bottom: 40px;
}
.loading {
  text-align: center;
  color: #505050;
  font-size: 1.1rem;
}
</style>
