<script setup>
import { computed, onMounted, ref, watch } from 'vue';



const emit = defineEmits(['update:modelValue', 'value']);


const props = defineProps({
    'modelValue': {
        require: true
    },
    'list': {
        require: true
    },
    'disabled': {
        default: false
    },
    'options': {
        default: []
    },
    'keyOption': {
        type: String,
        default: 'SKU'
    },
    'nameOption': {
        type: String,
        default: 'descripcion'
    },
    'value': {
        default: ''
    }
})


const valueText = ref("");

const changeText = (text) => {
    valueText.value = text
}



const error = computed(() => {
    if (valueText.value !== "" || props.modelValue != "") {
        if (valueText.value !== "") 
        {
            //console.log(valueText.value)
            const selectOpcion = props.options.find(opcion => 
            {
                return (opcion[props.keyOption] + ' - ' + opcion[props.nameOption] ) == valueText.value
            });
            if (selectOpcion !== undefined) {
                emit('update:modelValue', selectOpcion[props.keyOption]);
            } else {
                emit('update:modelValue', "");
                return true;
            }
        } else {
            const selectOpcion = props.options.find(opcion => {
                return opcion[props.keyOption] == props.modelValue
            });
            if (selectOpcion !== undefined) {
                valueText.value = selectOpcion[props.nameOption]
            }
        }
    } else {
        emit('update:modelValue', '');
    }
    return false
})


const inputlist = ref(null);

watch((props), () => {
    valueText.value = props.value
})

onMounted(() => {
    if (inputlist.value.hasAttribute('autofocus')) {
        inputlist.value.focus();
    }
});

defineExpose({ focus: () => inputlist.value.focus() });

</script>
<template>
    <div class="">
        <input type="text" :list="list"
            class="w-full border-indigo-300 rounded-full shadow-sm  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 read-only:bg-gray-300"
            :class="{ 'border-red-400': error, 'text-red-400': error }" :value="valueText" @keyup="emit('value', valueText)"
            @input="changeText($event.target.value)" ref="inputlist" :disabled="disabled">
        <datalist :id="list">
            <option v-for="opcion in props.options" :key="opcion[props.keyOption]">
               {{ opcion[props.keyOption] + ' - ' + opcion[props.nameOption]}} 
            </option>
        </datalist>
    </div>
</template>

