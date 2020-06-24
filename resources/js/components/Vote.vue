<template>
    <div class="d-flex flex-column vote-controls">
        <!-- Upボタン -->
        <a :title="title('up')"
           class="vote-up" :class="classes">
            <i class="fas fa-caret-up fa-3x"></i>
        </a>
        <span class="votes-count">{{ count }}</span>
        <!-- Downボタン -->
        <a :title="title('down')"
           class="vote-down" :class="classes">
            <i class="fas fa-caret-down fa-3x"></i>
        </a>
        <!-- Favoriteボタン -->
        <favorite v-if="name === 'question'" :question="model"></favorite>
        <!-- Acceptボタン -->
        <accept v-else :answer="model"></accept>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';
    import Accept from './Accept.vue';

    export default {
        props: ['name', 'model'],

        computed: {
            classes () {
                return this.signedIn ? '' : 'off';
            }
        },

        data () {
            return {
                count: this.model.votes_count,
            }
        },

        components: {
            Favorite,
            Accept,
        },

        methods: {
            title (voteType) {
                let titles = {
                    up: `This ${this.name} is useful`,
                    down: `This ${this.name} is not useful`,
                };
                return titles[voteType];
            }
        }
    }
</script>
