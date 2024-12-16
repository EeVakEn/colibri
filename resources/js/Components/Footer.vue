<template>
    <footer>
        <div class="mx-auto container text-center">
            <p>&copy; {{moment().format('Y')}} Colibri. All rights reserved.</p>
        </div>
    </footer>
</template>

<script setup>
import moment from "moment";
</script>
