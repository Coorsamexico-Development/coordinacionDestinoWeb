<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';


defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {

    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />
    <div class="grid min-h-screen grid-cols-3 bg-white" >
       <div class="flex flex-col items-center justify-between py-16 mb-8">
          <img style="width: 30%;" src="../../../assets/img/logo-azul.png" />
          <h1 class="text-2xl tracking-widest text-center uppercase" style="font-family: 'Montserrat';">Bienvenido</h1>
          <div class="flex items-center justify-center">
            <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="block w-full mt-1"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="block w-full mt-1"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Forgot your password?
                </Link>
            <!--
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            -->
            </div>
               
               <button class="bg-[#1D96F1] py-2 px-44" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                 <h1 class="text-white" style="font-family: 'Montserrat';">
                    Iniciar sesión
                 </h1>
               </button>
            </form>
          </div>
       </div>
       <div class="flex flex-col items-end justify-end col-start-2 col-end-4 px-20 py-16 bgImg">
            <h1 class="text-[#A1DEFC] uppercase mr-4"  style="font-family: 'Montserrat'; font-size: 33px;">Plataforma</h1>
            <div class="mb-4">
                <h1 class="my-4 ml-10 text-6xl font-semibold text-white uppercase"  style="font-family: 'Montserrat';">Coordinador</h1>
                <h1 class="text-6xl font-semibold text-white uppercase"  style="font-family: 'Montserrat';">De Plataforma</h1>
            </div>
            <span class="bg-[#1D96F1] w-44 h-2 relative"></span>
     </div>
    </div>
</template>
<style>
.bgImg 
{
    background-image: url('../../../assets/img/fondo.jpg');
    background-size: cover;
    background-repeat: no-repeat;
}
</style>
