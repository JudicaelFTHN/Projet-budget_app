<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow">
            <h1 class="text-2xl font-bold text-blue-600">{{ message }}</h1>
            <p class="text-gray-500 mt-2">Séance 1 — Stack opérationnelle ✅</p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['message']
}
</script>
