<template>
    <div class="flex items-center justify-center  cursor-pointer bg-[#1D96F1] px-4 py-2 rounded-2xl"
     @click="selectFile()" @drop="drop" @dragover.prevent="checkDrop">
     <svg class="w-6 h-6" fill="white" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.5 18"><path id="Trazado_65" data-name="Trazado 65" class="cls-1" d="M.58,9.01V4.27c-.04-1.62,1.11-3.02,2.66-3.24,.17-.02,.35-.04,.52-.03h6.27c.89-.02,1.74,.35,2.35,1.01,.4,.42,.8,.83,1.2,1.25,.6,.61,.93,1.45,.91,2.32V13.77c.03,1.64-1.14,3.04-2.71,3.24-.12,.02-.24,.02-.36,.02-2.59,0-5.18,.01-7.77,0-1.48,0-2.75-1.09-3.02-2.59-.04-.22-.06-.45-.06-.67,0-1.59,0-3.17,0-4.76Zm6.95-3.4h3.1c.31,.01,.57-.24,.58-.56,0,0,0-.02,0-.02,0-.33-.24-.6-.56-.61H4.59c-.07,0-.13,0-.2,0-.32,.04-.54,.34-.5,.67,.04,.31,.3,.54,.6,.52,1.01,0,2.03,0,3.04,0h0Zm0,4.01h3.08c.32,.02,.59-.23,.61-.56,0-.04,0-.08,0-.12-.04-.32-.31-.55-.62-.53H4.47c-.32-.01-.59,.25-.6,.58-.01,.33,.24,.61,.56,.62,.02,0,.04,0,.06,0,1.01,0,2.02,0,3.04,0h0Zm-1.53,2.81h-1.47c-.1,0-.19,0-.28,.04-.27,.1-.43,.38-.37,.67,.06,.3,.32,.51,.62,.49h2.98c.32,.03,.6-.21,.63-.55,.03-.33-.21-.63-.53-.66-.04,0-.07,0-.11,0-.49,0-.99,0-1.48,0h0Z"></path></svg>
     <input :ref="'filedropzone'" type="file"
                @input="$emit('update:modelValue', $event.target.files[0])"
                class="hidden" :accept="accept"
                @change="setFile"/>
      <div class="flex flex-col" v-if="withFile">
            <span class="text-white text-xm">{{fileName}}</span>
      </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue';
    import { valImageFile } from '../../../utils/index';

    export default defineComponent({
        props: {
            modelValue: {
                require:false
            },
            accept: {
                default:'.pdf',
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
