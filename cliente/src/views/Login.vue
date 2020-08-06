<template>
    <div class="auth-box-w">
        <div class="logo-w">
            <a href="#"><img alt="" :src="images.logo"/></a>
        </div>
        <h4 class="auth-header">
            Ingresar
        </h4>
        <div v-if="m_error" class="col">
            <b-alert show dismissible variant="danger">
                {{ m_error }}
            </b-alert>
        </div>
        <b-form @submit.prevent="login">
            <vue-form-generator :schema="schema" :model="model"></vue-form-generator>
            <div class="buttons-w">
                <b-button type="submit" variant="primary" :disabled="loginState">
          <span v-if="loginState">
            <b-spinner small></b-spinner>
            Ingresando...
          </span>
                    <span v-else>
            Ingresar
          </span>
                </b-button>
            </div>
        </b-form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                model: {
                    username: "",
                    password: ""
                },
                schema: {
                    fields: [
                        {
                            type: "input",
                            inputType: "text",
                            label: "Usuario",
                            model: "username",
                            required: true,
                            attributes: {"placeholder": "Usuario"}
                        },
                        {
                            type: "input",
                            inputType: "password",
                            label: "Contraseña",
                            model: "password",
                            required: true,
                            attributes: {"placeholder": "Contraseña"}
                        }
                    ]
                },
                m_error: false,
                loginState: false,
                images: {
                    logo: require("@/assets/img/logo-big.png")
                }
            };
        },

        methods: {
            login() {
                this.loginState = true;
                this.m_error = null;
                this.$store
                    .dispatch("login", {
                        username: this.model.username,
                        password: this.model.password
                    })
                    .then(() => {
                        if (this.$store.state.user) this.$router.push({name: "Home"});
                        else this.m_error = this.$store.state.message;
                        this.loginState = false;
                    })
                    .catch(() => {
                    });
            }
        }
    };
</script>
<style>
    fieldset {
        margin-top: 0 !important;
    }
</style>