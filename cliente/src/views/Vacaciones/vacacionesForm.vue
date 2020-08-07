<template>
    <div>
        <b-modal
                :id="nameModal"
                :title="(idForm ? 'Modificar' : 'Nueva') + ' Vacacion'"
                @show="loadModal"
                @hidden="resetModal"
                @ok="handleOk"
                okTitle="Guardar"
                cancelTitle="Cancelar"
                size="lg"
        >
            <b-alert
                    show
                    dismissible
                    variant="danger"
                    v-for="(value, key) in m_error"
                    :key="key"
            >
                {{ value }}
            </b-alert>
            <form ref="form" @submit.stop.prevent="handleSubmit">

                <b-card
                        header="Datos del Empleado"
                        header-tag="header"
                >
                    <div class="row">
                        <b-col md="2">
                            <b-form-group
                                    :state="form.ci.state"
                                    :label="form.ci.label"
                                    label-for="ci"
                            >
                                <b-form-input
                                        id="ci"
                                        v-model="form.ci.value"
                                        :state="form.ci.state"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col md="5">
                            <b-form-group
                                    :state="form.nombre.state"
                                    :label="form.nombre.label"
                                    label-for="name"
                            >
                                <b-form-input
                                        id="name"
                                        v-model="form.nombre.value"
                                        :state="form.nombre.state"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col md="5">
                            <b-form-group
                                    :state="form.unidad.state"
                                    :label="form.unidad.label"
                                    label-for="unidad"
                            >
                                <b-form-input
                                        id="unidad"
                                        v-model="form.unidad.value"
                                        :state="form.unidad.state"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col md="4">
                            <b-form-group
                                    :state="form.fecha_ingreso.state"
                                    :label="form.fecha_ingreso.label"
                                    label-for="fecha_ingreso"
                                    label-cols-md="4"
                            >
                                <b-form-input
                                        id="fecha_ingreso"
                                        v-model="form.fecha_ingreso.value"
                                        :state="form.fecha_ingreso.state"></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col md="4">
                            <b-form-group
                                    :state="form.disponible.state"
                                    :label="form.disponible.label"
                                    label-for="disponible"
                                    label-cols-md="4"
                            >
                                <b-form-input
                                        id="disponible"
                                        v-model="form.disponible.value"
                                        :state="form.disponible.state"></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col md="4">
                            <b-form-group
                                    :state="form.ano_cumplido.state"
                                    :label="form.ano_cumplido.label"
                                    label-for="ano_cumplido"
                                    label-cols-md="4"
                            >
                                <b-form-input
                                        id="ano_cumplido"
                                        v-model="form.ano_cumplido.value"
                                        :state="form.ano_cumplido.state"></b-form-input>
                            </b-form-group>
                        </b-col>
                    </div>
                </b-card>
                <br>
                <b-card
                        header="Periodo de disfrute de vacaciones"
                        header-tag="header"
                >
                    <div class="row">
                        <b-col md="6">
                            <b-form-group
                                    :state="form.fecha_desde.state"
                                    :label="form.fecha_desde.label"
                                    label-for="fecha_desde"
                                    label-cols-md="4"
                            >
                                <b-form-input
                                        id="fecha_desde"
                                        v-model="form.fecha_desde.value"
                                        :state="form.fecha_desde.state"
                                        type="date"></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                    :state="form.fecha_hasta.state"
                                    :label="form.fecha_hasta.label"
                                    label-for="fecha_hasta"
                                    label-cols-md="4"
                            >
                                <b-form-input
                                        id="fecha_hasta"
                                        v-model="form.fecha_hasta.value"
                                        :state="form.fecha_hasta.state"
                                        type="date"></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                    :label="form.jornada.label"
                                    label-for="jornada"
                                    label-cols-md="4">
                                <b-form-radio-group
                                        id="jornada"
                                        v-model="form.jornada.state"
                                        :options="form.jornada.opciones"
                                        name="jornada"
                                ></b-form-radio-group>
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                    :state="form.numero_dias.state"
                                    :label="form.numero_dias.label"
                                    label-for="numero_dias"
                                    label-cols-md="4">
                                <b-form-input
                                        id="numero_dias"
                                        v-model="form.numero_dias.value"
                                        :state="form.numero_dias.state"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group
                                    :state="form.observaciones.state"
                                    :label="form.observaciones.label"
                                    label-for="observaciones"
                                    label-cols-md="3"
                            >
                                <b-form-textarea
                                        id="observaciones"
                                        v-model="form.observaciones.value"
                                        :state="form.observaciones.state"
                                ></b-form-textarea>
                            </b-form-group>
                        </b-col>
                    </div>
                </b-card>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                path: "/api/vacaciones",
                form: {
                    nombre: {value: "", state: null, label: "Nombre"},
                    ci: {value: "", state: null, label: "DNI"},
                    unidad: {value: "", state: null, label: "Unidad"},
                    fecha_ingreso: {value: "", state: null, label: "Fecha Ingreso"},
                    disponible: {value: "", state: null, label: "Disponible"},
                    ano_cumplido: {value: "", state: null, label: "Año Cumplido"},
                    fecha_desde: {value: "", state: null, label: "Fecha desde"},
                    fecha_hasta: {value: "", state: null, label: "Fecha hasta"},
                    jornada: {value: "", state: null, label: "Jornada", opciones: ['Media', 'Completa']},
                    numero_dias: {value: "", state: null, label: "Numero de dias"},
                    observaciones: {value: "", state: null, label: "Observaciones"},
                    estado: {value: false, state: null, label: "Estado"}
                },
                m_error: false
            };
        },
        props: {
            idForm: Number,
            nameModal: String
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity();
                //this.form.nombre.state = valid;
                this.m_error = false;
                return valid;
            },
            resetModal() {
                Object.keys(this.form).forEach(key => {
                    this.form[key].value = "";
                    this.form[key].state = null;
                });
                this.m_error = false;
            },
            loadModal() {
                this.resetModal();
                if (this.idForm) {
                    // Push the name to submitted names
                    axios
                        .get(this.path, {
                            params: {id: this.idForm}
                        })
                        .then(({data}) => {
                            if (data["status"] === 0) {
                                Object.entries(data["data"]).forEach(([key, value]) => {
                                    if (this.form[key]) this.form[key].value = value;
                                });
                            } else {
                                this.m_error = data["data"];
                            }
                        })
                        .catch();
                }
            },
            handleOk(bvModalEvt) {
                // Prevent modal from closing
                bvModalEvt.preventDefault();
                // Trigger submit handler
                this.handleSubmit();
            },
            handleSubmit() {
                // Exit when the form isn't valid
                /* if (!this.checkFormValidity()) {
                   return;
                 }*/
                // Push the name to submitted names
                let formData = {};
                Object.entries(this.form).forEach(([key, value]) => {
                    formData[key] = value.value;
                });

                if (this.idForm) formData["id"] = this.idForm;

                axios
                    .post(this.path, formData)
                    .then(({data}) => {
                        if (data["status"] === 0) {
                            // Hide the modal manually
                            this.$nextTick(() => {
                                this.$emit("finish", true);
                                this.$bvModal.hide(this.nameModal);
                            });
                        } else {
                            this.m_error = data["data"];
                        }
                    })
                    .catch();
            },
            close() {
            }
        }
    };
</script>
