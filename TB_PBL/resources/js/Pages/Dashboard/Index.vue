<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Supply Chain Analytics Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Stock Levels</h3>
                                <p class="text-sm text-gray-500">Menampilkan {{ totalProducts }} barang stok gudang.</p>
                            </div>
                            <div class="text-sm text-gray-600">Algoritma mendeteksi stok akan habis dalam 3 hari ke depan.</div>
                            <div class="flex items-center gap-2">
                                <button @click="runAnalysis" :disabled="isAnalyzing" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                    <span v-if="isAnalyzing">Menjalankan...</span>
                                    <span v-else>Jalankan Analisis</span>
                                </button>
                            </div>
                        </div>
                        <canvas ref="stockChart"></canvas>

                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Stock</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Jual/Hari</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diperkirakan Habis (hari)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="item in stockData" :key="item.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.current_stock }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.min_stock_level }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.avg_daily_sales }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.predicted_days_to_depletion }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold" :class="item.needs_reorder ? 'text-red-600' : 'text-green-600'">
                                            {{ item.needs_reorder ? 'Butuh Reorder' : 'Aman' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Pending Reorder Drafts</h3>
                        <ul>
                            <li v-if="reorderDrafts.length === 0" class="text-sm text-gray-500">Tidak ada draft reorder pending.</li>
                            <li v-for="draft in reorderDrafts" :key="draft.id" class="mb-2">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <span class="font-semibold">{{ draft.product.name }}</span>
                                        <span class="text-gray-600">- {{ draft.suggested_quantity }} unit</span>
                                        <p class="text-sm text-gray-500">{{ draft.reason }}</p>
                                    </div>
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded">Approve</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, computed } from 'vue';
import Chart from 'chart.js/auto';

const stockChart = ref(null);
const props = defineProps({
    stockData: Array,
    reorderDrafts: Array,
});

const totalProducts = computed(() => props.stockData.length);
const isAnalyzing = ref(false);

async function runAnalysis() {
    isAnalyzing.value = true;
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
    try {
        const res = await fetch('/dashboard/analyze', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        });
        const data = await res.json();
        if (data.success) {
            alert(data.message || 'Analisis selesai');
            // reload untuk menampilkan perubahan pada daftar draft
            location.reload();
        } else {
            alert(data.message || 'Gagal menjalankan analisis');
        }
    } catch (e) {
        console.error(e);
        alert('Terjadi error saat menjalankan analisis');
    } finally {
        isAnalyzing.value = false;
    }
}

onMounted(() => {
    if (stockChart.value) {
        new Chart(stockChart.value, {
            type: 'bar',
            data: {
                labels: props.stockData.map(item => item.name),
                datasets: [{
                    label: 'Current Stock',
                    data: props.stockData.map(item => item.current_stock),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Min Stock Level',
                    data: props.stockData.map(item => item.min_stock_level),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>