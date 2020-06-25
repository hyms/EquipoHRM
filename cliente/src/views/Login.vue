<template>
    <div >
    <div class="all-wrapper menu-side with-pattern">
        <div class="auth-box-w">
            <div class="logo-w">
                <a href="index.html"><img alt="" :src="images.logo"></a>
            </div>
            <h4 class="auth-header">
               Ingresar
            </h4>
            <div v-if="m_error" class="col">
                <b-alert show dismissible variant="danger" v-for="(value, key) in m_error" :key="key">
                    {{ key }}: {{ value }}
                </b-alert>
            </div>
        <b-form @submit.prevent="login">
            <b-form-group
                    id="input-group-1"
                    label="Usuario:"
                    label-for="input-1"
            >
                <b-form-input
                        id="input-1"
                        v-model="email"
                        type="text"
                        placeholder="Usuario"
                ></b-form-input>
                <div class="pre-icon os-icon os-icon-user-male-circle"></div>
            </b-form-group>
            <b-form-group
                    id="input-group-2"
                    label="Contraseña:"
                    label-for="input-2"
            >
                <b-form-input
                        id="input-2"
                        v-model="password"
                        type="password"
                        placeholder="Contraseña"
                ></b-form-input>
                <div class="pre-icon os-icon os-icon-fingerprint"></div>
            </b-form-group>

            <div class="buttons-w">
                <b-button type="submit" variant="primary">Submit</b-button>
            </div>

        </b-form>
        </div>
    </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                email: '',
                password: '',
                m_error: false,
                images:{
                    logo: require('../assets/img/logo-big.png')
                }
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

                try {
                    this.m_error = JSON.parse(this.$store.state.message);
                } catch (e) {
                    this.m_error = {"mensaje":[this.$store.state.message]};
                }
            }
        }
    }
</script>
