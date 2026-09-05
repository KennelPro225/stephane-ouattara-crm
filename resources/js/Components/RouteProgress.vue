<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { router } from '@inertiajs/vue3'

const width = ref(0)
const visible = ref(false)
let timer

function start () {
  clearInterval(timer)
  visible.value = true
  width.value = 15
  timer = setInterval(() => {
    if (width.value < 88) width.value += (90 - width.value) * 0.12
  }, 120)
}
function finish () {
  clearInterval(timer)
  width.value = 100
  setTimeout(() => { visible.value = false; width.value = 0 }, 250)
}

let stopStart, stopFinish
onMounted(() => {
  stopStart = router.on('start', start)
  stopFinish = router.on('finish', finish)
})
onBeforeUnmount(() => { stopStart?.(); stopFinish?.() })
</script>

<template>
  <div v-if="visible" class="route-progress" :style="{ width: `${width}%`, opacity: width >= 100 ? 0 : 1 }" aria-hidden="true" />
</template>
