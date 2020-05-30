<template>
    <div class="card mb-2">
        <div class="card-body">
            <h1 class="card-title">{{ title }}</h1>
            <div class="card-text" :title="description">{{ truncateElipse(description, 20) }}</div>
        </div>
    </div>
</template>

<script>
import {functions} from '../mixins'

export default {
    props: ['title', 'description'],

    mixins: [functions]
}
</script>

<style scoped>
    .card{
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
        overflow-x: auto;
    }
</style>