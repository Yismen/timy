<template>
<nav aria-label="">
    <ul class="pagination mt-2 mb-2">
        <li class="page-item" :class="{'disabled': ! links.prev}">
            <a class="page-link" href="#" aria-label="Previous" @click.prevent="fetchData(links.prev)">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>       
        <li class="page-item" :class="{'disabled': ! links.prev}">
            <a class="page-link" href="#" aria-label="Previous" @click.prevent="fetchData(links.first)">
                First
            </a>
        </li>  
        <li class="page-item" :class="{'disabled': ! links.next}">
            <a class="page-link" href="#" aria-label="Previous" @click.prevent="fetchData(links.last)">
                Last
            </a>
        </li>
        <li class="page-item" :class="{'disabled': ! links.next}">
            <a class="page-link" href="#" aria-label="Next" @click.prevent="fetchData(links.next)">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        <li class="page-item description-link pl-2">
             Page {{ meta.current_page }} of {{ meta.last_page }}, from {{ meta.from }} to {{ meta.to }} of {{ meta.total }} records...
        </li>
    </ul>
</nav>
</template>

<script>
export default {
    props: ['links', 'meta'],

    methods: {
        fetchData(url) {
            this.$emit('pagination-updated', url)
        }
    }
}
</script>

<style scoped>
    .description-link {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>