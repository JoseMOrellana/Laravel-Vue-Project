<style type="text/css">
    .widget-user-header {
        background-position: center center;
        background-size: cover;
        height: 240px !important;
    }

    .widget-user-image {
        top: 160px !important;
        left: 45% !important;
    }

    .widget-user-image img {
        width: 200px !important;
    }
</style>

<template>
    <div class="container">
        <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white" style="background: url('img/workplace2.jpg') center center;">
                <h2 class="widget-user-username text-right red"> {{ this.form.nombre + " " + this.form.apellido}}</h2>
                <h5 class="widget-user-desc text-right"> {{ this.form.email }}</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" :src="'img/' + this.form.img_url" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">Cedula:</h5>
                      <span class="description-text"> {{ this.form.cedula }}</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header"></h5>
                      <span class="description-text"></span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">Telefono: </h5>
                      <span class="description-text"> {{ this.form.telefono }}</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Actividad</a></li>
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Configuración</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="activity">
                    <h1>Actividad del usuario</h1>
                  </div>

                  

                  <div class="tab-pane" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input v-model="form.nombre" type="text" name="nombre" placeholder="Nombre" class="form-control" :class="{ 'is-invalid': form.errors.has('nombre')}" required>
                        <has-error :form="form" field="nombre"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input v-model="form.apellido" type="text" name="apellido" placeholder="Apellido" class="form-control" :class="{ 'is-invalid': form.errors.has('apellido')}" required>
                        <has-error :form="form" field="apellido"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electronico</label>
                        <input v-model="form.email" type="email" name="email" placeholder="Correo Electronico" class="form-control" :class="{ 'is-invalid': form.errors.has('email')}" required>
                        <has-error :form="form" field="email"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input v-model="form.password" type="password" name="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password')}">
                        <has-error :form="form" field="password"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirmar Contraseña</label>
                        <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-control" :class="{ 'is-invalid': form.errors.has('confirmPassword')}" id="password-confirm">
                        <has-error :form="form" field="confirmPassword"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="cedula">Cedula</label>
                        <input v-model="form.cedula" type="number" name="cedula" placeholder="Cedula" class="form-control" :class="{ 'is-invalid': form.errors.has('cedula')}" required>
                        <has-error :form="form" field="cedula"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Telefono</label>
                        <input v-model="form.telefono" type="number" name="telefono" placeholder="Telefono" class="form-control" :class="{ 'is-invalid': form.errors.has('telefono')}" required>
                        <has-error :form="form" field="telefono"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="imagen">Imagen</label>
                        <input @change="updateProfilePic" type="file" name="imagen">
                        <has-error :form="form" field="imagen"></has-error>
                    </div>

                    <button  @click.prevent="updateInfo" class="btn btn-primary">Editar</button>


                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>


</div>
</template>

<script>
    export default {
        data() {
            return {
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

        methods: {
            updateProfilePic(e) {
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.onload = () => {
                    this.form.img_url = reader.result;
                }
                reader.readAsDataURL(file)
            },
            updateInfo() {
                this.form.put('api/profile')
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
