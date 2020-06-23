<template>
    <div>
        <h1>Login</h1>
        <form @submit.prevent="login">
            <input type="text" name="email" v-model="email">
            <input type="password" name="password" v-model="password">
            <button type="submit">Login</button>
            <span v-if="m_error">{{m_error}}</span>
        </form>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                email: '',
                password: '',
                m_error: false,
            }
        },

        methods: {
            async login () {
                 await this.$store
                    .dispatch('login', {
                        email: this.email,
                        password: this.password
                    })
                    .then(() => {
                        this.$router.push({ name: 'About' })
                    })
                    .catch(err => {
                        console.log(err);
                    });

                this.m_error = JSON.parse(JSON.stringify(this.$store.state.message))
            }
        }
    }
</script>
