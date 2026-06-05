<template>
  <div class="form-group">
    <label :for="name">{{ label }}</label>
    <input
      :id="name"
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="form-control"
      :class="{ 'is-invalid': error }"
    />
    <span class="error-msg" v-if="error">{{ error }}</span>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  name: String,
  modelValue: [String, Number],
  type: { type: String, default: 'text' },
  error: String
})
defineEmits(['update:modelValue'])
</script>

<style scoped>
.form-group { margin-bottom: 15px; }
label { display: block; font-weight: 600; margin-bottom: 5px; color: #475569; font-size: 13px; }
.form-control { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; }
.form-control.is-invalid { border-color: #ef4444; background-color: #fef2f2; }
.error-msg { color: #ef4444; font-size: 12px; margin-top: 4px; display: block; font-weight: 500; }
</style>