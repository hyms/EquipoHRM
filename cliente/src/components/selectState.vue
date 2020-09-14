<template>
    <b-form-select
            v-model="modelSelect"
            :options="optionSelect"
            v-on:change="post()"
    ></b-form-select>
</template>

<script>
    import Helpers from "../store/funcions";
    import axios from "axios";

    export default {
        name: "selectState",
        data() {
            return {
                modelSelect: "",
                optionSelect: [],
                path: "/api/vacaciones/"
            };
        },
        props: {
            id: undefined,
            value: undefined
        },
        methods: {
            async post() {
                this.message_error = "";
                if (await this.showMsgConfirm()) {
                    let path = "";
                    switch (this.modelSelect) {
                        case 1:
                            path = this.path + "aprobar";
                            break;
                        case 10:
                            path = this.path + "declinar";
                            break;
                        case 2:
                            path = this.path + "registro";
                            break;
                    }
                    if (path !== "" && this.id !== undefined) {
                        await axios
                            .post(path, {id: this.id})
                            .then(({data}) => {
                                if (data["status"] === 0) {
                                    this.getAllData();
                                } else {
                                    this.$refs.childComponent.setValue(data["message"]);
                                    this.message_error = data["message"];
                                }
                            })
                            .catch(err => {
                                console.log(err);
                            });
                    }
                }
            },
            showMsgConfirm() {
                return this.$bvModal
                    .msgBoxConfirm("Esta seguro?.", {
                        title: "Informacion",
                        size: "sm",
                        //okVariant: 'success',
                        okTitle: "SI",
                        cancelVariant: "danger",
                        cancelTitle: "NO",
                        footerClass: "p-2",
                        hideHeaderClose: false
                    })
                    .then(value => {
                        return value;
                    })
                    .catch(() => {
                    });
            }
        },
        created() {
            this.optionSelect = Helpers.stateVacaciones();
            if (this.value !== undefined && this.modelSelect === "") {
                this.modelSelect = this.value;
            }
        }
    };
</script>
