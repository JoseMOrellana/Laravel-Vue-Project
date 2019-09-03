<template>
    <div class="container">
    <div class="row justify-content-center">
        <img :src="'/img/' + this.form.img_url" class="mb-2" style="width:600px;">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ this.form.nombre + " " + this.form.apellido }} </div>

                <div class="card-body">
                    <ul>
                        <li>{{ this.form.email}} </li>
                        <li>{{ this.form.cedula}} </li>
                        <li> {{ this.form.telefono}}</li>
                    </ul>
                    <a href="" class="btn btn-primary">Editar datos</a>
                    <a href="newpassword" class="btn btn-warning">Cambiar contraseña</a>                    
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        data() {
            return {
                img_url: "/img/",
                form: new Form({
                    id: '',
                    nombre: '',
                    apellido: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    cedula: 0,
                    telefono: 0,
                    img_url: '',
                    activo: 1
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
            axios.get('api/profile')
            .then( ({ data }) => (this.form.fill(data)))
            .catch()
            this.img_url += this.form.img_url

        }
    }
</script>
