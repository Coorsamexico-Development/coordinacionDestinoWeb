<template>
    <div class="flex items-center justify-center w-64 h-32 border-2 border-dashed rounded cursor-pointer "
        :class="{ 'text-gray-400': disabled, 'hover:border-blue-400 hover:text-blue-400 border-gray-700': !disabled }"
        @click="selectFile()" @drop="drop" @dragover.prevent="checkDrop">
        <input :ref="'filedropzone'" type="file" @input="$emit('update:modelValue', $event.target.files[0])" class="hidden"
            :accept="accept" @change="setFile" />
        <svg xmlns="http://www.w3.org/2000/svg" :class="{ 'hidden': withFile }" width="16" height="16" fill="currentColor"
            class="bi bi-cloud-upload" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z" />
            <path fill-rule="evenodd"
                d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z" />
        </svg>

        <span class="block " :class="{ 'hidden': withFile, 'text-grey-700': disabled }">Suelta aquí.</span>
        <div class="flex flex-col" v-if="withFile">
            <img class="w-10 h-10 m-auto rounded" :src="urlImage" alt="File Drop" />
            <span class="text-gray-400 text-xm">{{ fileName }}</span>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import { valImageFile } from '../utils/index';

export default defineComponent({
    props: {
        modelValue: {
            require: false
        },
        accept: {
            default: 'images/*',
            require: false
        },
        disabled: {
            require: false,
            default: false
        }
    },

    emits: ['update:modelValue', 'loadFile'],
    data() {
        return {
            withFile: false,
            urlImage: '/assets/img/icono doc 7.png',
            fileName: ''
        }
    },
    methods: {
        focus() {
            this.$refs.filedropzone.focus()
        },
        selectFile() {
            if (this.$props.disabled) {
                return;
            }
            this.$refs.filedropzone.click();
        },
        drop(event) {
            if (this.$props.disabled) {
                return;
            }
            this.error = false
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            this.$emit('update:modelValue', file);
            setTimeout(() => {
                this.setFile()
            }, 200);
        },
        setFile: function () {
            if (this.modelValue) {
                this.withFile = true;
                this.fileName = this.modelValue.name;
                if (valImageFile(this.fileName)) {
                    this.urlImage = URL.createObjectURL(this.modelValue);
                }
                this.$emit('loadFile', this.modelValue);
            } else {
                this.withFile = false;
            }
        },

    }
})
</script>
