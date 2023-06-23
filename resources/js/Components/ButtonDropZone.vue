<template>
    <div class="flex items-center justify-center rounded cursor-pointer hover:border-gray-700"
     @click="selectFile()" @drop="drop" @dragover.prevent="checkDrop">

        <input :ref="'filedropzone'" type="file"
                @input="$emit('update:modelValue', $event.target.files[0])"
                class="hidden" :accept="accept"
                @change="setFile"/>
     <span class="block text-xl font-bold text-gray-500" :class="{'hidden':withFile}">+</span>
      <div class="flex flex-col" v-if="withFile">
            <span class="text-gray-400 text-xm">{{fileName}}</span>
      </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue';
    import { valImageFile } from '../utils/index';

    export default defineComponent({
        props: {
            modelValue: {
                require:false
            },
            accept: {
                default:'.xlsx',
                require:false
            }
        },

        emits: ['update:modelValue', 'loadFile'],
        data(){
            return {
                withFile: false,
                urlImage:'/images/fileIcon.png',
                fileName:''
            }
        },
        methods: {
            focus() {
                this.$refs.filedropzone.focus()
            },
            selectFile(){
                this.$refs.filedropzone.click();
            },
            drop(event){
                this.error = false
                event.preventDefault();
                const file = event.dataTransfer.files[0];
                this.$emit('update:modelValue',file);
                setTimeout(()=> {
                    this.setFile()
                },200);
            },
            setFile: function(){
                if(this.modelValue){
                    this.withFile = true;
                    this.fileName = this.modelValue.name;
                    if(valImageFile(this.fileName)){
                        this.urlImage = URL.createObjectURL(this.modelValue);
                    }
                    this.$emit('loadFile', this.modelValue);
                }else{
                    this.withFile = false;
                }
            },

        }
    })
</script>
