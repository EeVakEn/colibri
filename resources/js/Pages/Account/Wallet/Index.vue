<template>
    <div class="container">
        <div class="flex justify-between items-center">
            <h1 class="text-4xl font-black">Wallet</h1>
            <div class="flex gap-2">
                <button class="btn-primary bg-slate-600 hover:bg-slate-500" @click="openDeposit">Deposit</button>
                <button class="btn-primary bg-rose-600 hover:bg-rose-500" @click="openWithdraw">Withdraw</button>
            </div>
        </div>
        <div class="my-4 bg-indigo-600 text-white rounded-lg py-5 px-7 max-w-[300px] w-fit">
            <div class="text-xl font-black">Your account</div>
            <div class="flex items-end justify-end w-full mt-2">
                <div class="text-4xl">$</div>
                <div class="text-6xl font-black">{{ user.balance }}</div>
            </div>
        </div>

        <div>
            <h3 class="text-2xl font-black mb-2">Transaction history</h3>
            <div v-for="transaction in user.transaction_history" :key="transaction.id"
                 class="bg-indigo-50 rounded-lg pt-3 pb-5 px-6 my-2 flex justify-between relative">
                <div class="flex flex-row items-center gap-4">
                    <template v-if="transaction.from">
                        <Avatar :user="transaction.from"/>
                        <span v-if="transaction.from.id === user.id" class="font-black">You</span>
                        <span v-else>{{ transaction.from.name }}</span>
                    </template>
                    <span v-else>Deposit</span>
                    <ChevronRight/>
                    <template v-if="transaction.to">
                        <Avatar :user="transaction.to"/>
                        <span v-if="transaction.to.id === user.id" class="font-black">You</span>
                        <span v-else>{{ transaction.to.name }}</span>
                    </template>
                    <span v-else>Withdraw</span>
                    <div v-if="transaction.note" class="note ml-5">
                        <Info class="inline mr-2" size="20"/>
                        <span v-html="transaction.note"/>
                    </div>
                </div>
                <div class="font-black">
                    <span
                        :class="+transaction.amount < 0 ? 'text-rose-600' : 'text-slate-600'"
                        class="font-black text-2xl">
                        {{ transaction.amount }}
                    </span>
                </div>
                <span class="absolute right-6 bottom-2 text-gray-500">{{ transaction.created_at }}</span>
            </div>
        </div>

        <!-- Deposit Modal -->
        <Modal v-model="isDepositOpen">
            <h2 class="text-xl font-black">Deposit</h2>
            <form @submit.prevent="submitDeposit" class="my-5">
                <label>Amount</label>
                <input v-model="amount" class="input-field" type="number" step="0.01" min="0.01" max="10000"/>
                <button type="submit" class="float-end mt-5 btn-primary bg-slate-600 hover:bg-slate-500">Deposit
                </button>
            </form>
        </Modal>

        <!-- Withdraw Modal -->
        <Modal v-model="isWithdrawOpen">
            <h2 class="text-xl font-black">Withdraw</h2>
            <form @submit.prevent="submitWithdraw" class="my-5">
                <label>Amount</label>
                <input v-model="amount" class="input-field" type="number" step="0.01" min="0.01" :max="user.balance"/>
                <button type="submit" class="float-end mt-5 btn-primary bg-rose-600 hover:bg-rose-500">Withdraw</button>
            </form>
        </Modal>
    </div>
</template>

<script setup>
import Avatar from "@/UI/Avatar.vue";
import {ChevronRight, Info} from "lucide-vue-next";
import Modal from "@/UI/Modal.vue";
import {ref} from "vue";
import {router} from '@inertiajs/vue3';

const isDepositOpen = ref(false);
const isWithdrawOpen = ref(false);
const amount = ref(10);

const openDeposit = () => {
    isDepositOpen.value = true;
    amount.value = 10;
};

const openWithdraw = () => {
    isWithdrawOpen.value = true;
    amount.value = 10;
};

defineProps({
    user: Object,
});

const submitDeposit = () => {
    router.post(route('account.wallet.deposit'), {amount: amount.value}, {
        onSuccess: () => isDepositOpen.value = false
    });
};

const submitWithdraw = () => {
    router.post(route('account.wallet.withdraw'), {amount: amount.value}, {
        onSuccess: () => isWithdrawOpen.value = false
    });
};
</script>
<script>
import AccountLayout from "@/Layouts/AccountLayout.vue";

export default {
    layout: AccountLayout
}
</script>
