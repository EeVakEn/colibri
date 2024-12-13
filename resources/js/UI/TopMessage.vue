<script setup>
import {toast} from 'vue3-toastify';
import {watch} from "vue";
import {usePage} from "@inertiajs/vue3";

import 'vue3-toastify/dist/index.css';

const page = usePage();

watch(
    () => page.props.message,
    (val) =>{
        if (val){
            toast(val, {
                type: 'info',
                autoClose: 3000,
                position: "bottom-right",
            })
        }

    }
);
watch(
    () => page.props.errors,
    (val) =>
    {
        Object.values(val).forEach((m)=>{
            if(m){
                toast(m, {
                    type: "error",
                    autoClose: 3000,
                    position: "bottom-right",
                })
            }
        })
    }
);
</script>
