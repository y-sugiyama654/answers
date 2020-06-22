<template>
    <a title="Click to mark as favorite question (Click again to undo)" :class="classes">
        <i class="fas fa-star fa-2x"></i>
        <span class="favorites-count">{{ count }}</span>
    </a>
</template>
<script>
    export default {
        props: ['question'],

        data () {
            return {
                isFavorited: this.question.is_favorited,
                count: this.question.favorites_count,
                signedIn: true, // あとで修正する
            }
        },

        computed: {
            classes () {
                return [
                   'favorite', 'mt-3',
                    ! this.signedIn ? 'off' : (this.isFavorited ? 'favorited' : '')
                ];
            }
        }
    }
</script>
