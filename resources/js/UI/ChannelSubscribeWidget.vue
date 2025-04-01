<script setup>

import Avatar from "@/UI/Avatar.vue";
import {Link} from '@inertiajs/vue3'
import moment from "moment/moment";
import Modal from "@/UI/Modal.vue";
import {ref, defineProps} from "vue";
import { toast } from 'vue3-toastify'
import axios from "axios";

const formatData = (data) => {
    return moment(data).fromNow()
}

const props = defineProps({
    channel: {
        type: Object,
    },
    className: {
        type: String,
        default: 'cursor-pointer border-2 border-indigo-700 w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden'
    },
    user: Object,
})

let isOpenModal = ref(false)
let localChannel = ref(props.channel)
let isDepositModalOpen = ref(false);
const subscribe = async (isForce = false) => {
    if (!props.channel.is_free && !isForce) {
        open();
        return;
    }

    try {
        const response = await axios.post(route('subscription.subscribe', props.channel.id), []);
        localChannel.value = response.data.channel;
        toast.success(response.data.success || "Successfully subscribed!");
    } catch (e) {
        console.log(e.response)
        if (e.response.status === 402) {
            openDepositModal();
        } else {
            toast.error(e.response?.data?.error || "Subscription failed!");
        }
    } finally {
        close();
    }
};

const unsubscribe = async () => {
    try {
        const response = await axios.post(route('subscription.unsubscribe', props.channel.id), []);
        localChannel.value = response.data.channel;
        console.log(localChannel.value)

        // Show success toast
        toast.success(response.data.success || "Successfully unsubscribed!");
    } catch (e) {
        // Show error toast
        toast.error(e.response?.data?.error || "Unsubscription failed!");
    } finally {
        close();
    }
};

const openDepositModal = () => {
    isDepositModalOpen.value = true;
};
const closeDepositModal = () => {
    isDepositModalOpen.value = false;
};

const open = () => {
    isOpenModal.value = true;
}
const close = () => {
    isOpenModal.value = false;
}
</script>

<template>
    <div class="flex flex-row flex-nowrap items-center gap-3">
        <Link :href="route('account.channels.show', localChannel.id)">
            <Avatar :user="localChannel"></Avatar>
        </Link>
        <div class="flex flex-col items-left">
            <Link :href="route('account.channels.show', channel.id)"><b>{{ localChannel.name }}</b></Link>
            <span>{{ localChannel.subs_count ?? 0 }} Subscriptions</span>
        </div>
        <a v-if="!localChannel.is_subscribed" class="btn-primary" @click="subscribe(false)">
            Subscribe
            <span v-if="!localChannel.is_free"> - ${{ localChannel.subscription_price }}</span>
        </a>
        <a v-else class="btn-muted" @click="unsubscribe">Unsubscribe</a>
    </div>
    <modal v-model="isOpenModal">
        <div class="flex flex-col gap-5">
            <h2><b>Subscription is PAID</b></h2>
            <div>${{ localChannel.subscription_price }} will be debited from your wallet.</div>
            <div class="flex justify-end">
                <div class="flex flex-row flex-nowrap gap-2">
                    <a class="btn-muted" @click.prevent="close">Cancel</a>
                    <a class="btn-primary" @click.prevent="subscribe(true)">Subscribe <span
                        v-if="!localChannel.is_free"> - ${{ localChannel.subscription_price }}</span></a>
                </div>
            </div>
        </div>
    </modal>
    <Modal v-model="isDepositModalOpen">
        <div class="flex flex-col gap-5">
            <h2><b>Insufficient funds</b></h2>
            <p>Your balance is <b>${{ props.user.balance }}</b>, but you need <b>${{ localChannel.subscription_price }}</b>.</p>
            <div class="flex justify-end">
                <div class="flex flex-row flex-nowrap gap-2">
                    <a class="btn-muted" @click.prevent="closeDepositModal">Cancel</a>
                    <Link :href="route('account.wallet.index')" target="_blank" class="btn-primary">Top up wallet</Link>
                </div>
            </div>
        </div>
    </Modal>
</template>

<style scoped>

</style>
