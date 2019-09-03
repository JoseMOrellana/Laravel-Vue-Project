<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de usuarios</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">
                        Añadir <i class="fa fa-user-plus fa-fw"></i> 
                    </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Email</th>
                      <th>Cedula</th>
                      <th>Telefono</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users" :key="user.id">
                      <td>{{ user.id }}</td>
                      <td>{{ user.nombre }}</td>
                      <td>{{ user.apellido }}</td>
                      <td>{{ user.email }}</td>
                      <td>{{ user.cedula }}</td>
                      <td>{{ user.telefono }}</td>
                      <td>
                        <a href="#">
                            <i class="fa fa-edit" @click="editModal(user)"></i>
                        </a>
                        /
                        <a href="#">
                            <i class="fa fa-trash red" @click="deleteUser(user.id)"></i>
                        </a>
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 v-show="!editMode"class="modal-title" id="addNewModalLabel">Crear Usuario</h5>
                <h5 v-show="editMode"class="modal-title" id="addNewModalLabel">Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form @submit.prevent="editMode ? editUser() : createUser()" enctype="multipart/form-data">
                  
                  <div class="modal-body">
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

                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button v-show="editMode" type="submit" class="btn btn-primary">Editar</button>
                    <button v-show="!editMode" type="submit" class="btn btn-success">Crear</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editMode: true,
                users: {},
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
            newModal() {
                this.editMode = false;
                this.form.reset();
                $('#addNewModal').modal('show');


            },
            editModal(user) {
                this.editMode = true;
                this.form.reset();
                $('#addNewModal').modal('show');
                this.form.fill(user);

            },
            createUser() {
                this.$Progress.start();
                this.form.post('api/user')
                .then(() => {
                    Fire.$emit('afterChange');
                    $('#addNewModal').modal('hide')
                    toast.fire({
                        type: "success",
                        title: "Usuario creado"
                    })
                    this.$Progress.finish();

                })
                .catch(() => {
                    this.$Progress.fail();
                })
            },
            editUser() {
                this.$Progress.start();
                this.form.put("api/user/" + this.form.id)
                .then(() => {
                    $("#addNewModal").modal('hide');
                    swal.fire("Editado!","Se ha editado el usuario", "success");
                    this.$Progress.finish();
                    Fire.$emit("afterChange")
                })
                .catch(() => {
                    this.$Progress.fail();
                })
            },
            loadUsers() {
                axios.get('api/user').then(({ data }) => (this.users = data.data))
            },
            deleteUser(id) {
                swal.fire({
                  title: '¿ Esta seguro?',
                  text: "Al desactivar el usuario, éste no podra iniciar sesión",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Si, desactivarlo!',
                  cancelButtonText: "Cancelar"
                }).then((result) => {

                    if(result.value) {
                        this.form.delete("api/user/"+id)
                        .then(() => {
                            swal.fire(
                              'Desactivado!',
                              'El usuario ha sido desactivado',
                              'success'
                            )
                        })
                        .catch(() => {
                            swal("Failed!","Ocurrio un error","warning")
                        })
                        Fire.$emit("afterChange")
                    }
                  
                })
            }
            
        },
        mounted() {
            this.loadUsers();
            Fire.$on('afterChange', () => {
                this.loadUsers();
            })
        }
    }
</script>
