<template>
    <div>
        <b-form-group
                :state="state"
                :label="label"
                :label-for="name"
                :invalid-feedback="label +' es requerido'"
        >
            <b-form-input
                    :id="name"
                    v-model="value"
                    :state="state"
                    v-if="type==='text'"
            ></b-form-input>
            <b-form-datepicker
                    :id="name"
                    v-model="value"
                    locale="es"
                    :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                    v-else-if="type==='datepiker'"
            ></b-form-datepicker>
            <b-form-select
                    :id="name"
                    v-model="value"
                    :options="options"
                    value-field="id"
                    text-field="nombre"
                    v-else-if="type==='select'"
            ></b-form-select>
            <b-form-textarea
                    :id="name"
                    v-model="value"
                    v-else-if="type==='textarea'"
            ></b-form-textarea>
        </b-form-group>
    </div>
</template>
<script>
    export default {
        props:{
            label: String,
            name: String,
            type: String,
            options: Object,
            value: Object,
            state: Object
        }
    }
</script>