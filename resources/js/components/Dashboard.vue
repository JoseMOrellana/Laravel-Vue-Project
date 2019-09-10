<template>
    <div class="container">
            <h2>Tablero de archivos</h2>
            <div class="card mt-4">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                      <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Todos</a></li>
                      <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Mios</a></li>
                    </ul>
                    <div class="card-tools">
                        <button class="btn btn-success" @click="newModal">
                            Añadir <i class="fa fa-file-medical fa-fw"></i> 
                        </button>
                    </div>
                </div><!-- /.card-header -->

                <div class="card-body">
                    <div class="tab-content">
                      <div class="tab-pane active" id="activity">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Nombre</th>
                              <th>Autor</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="file in files" :key="file.id" > 
                              <td> {{ file.id }} </td>
                              <td> <a :href="'./pdf/' + file.nombre + '.pdf'"> {{ file.nombre}}</a></td>
                              <td> {{ file.user_nombre }}</td>
                              <td>
                                <a :href="'api/downloadFile/' + file.id">
                                    <i class="fas fa-download green" ></i>
                                </a>
                                 /
                                <a href="#">
                                    <i class="fa fa-edit" @click="editModal(file.id,file.nombre)"></i>
                                </a>
                                /
                                <a href="#">
                                    <i class="fa fa-trash red" @click="deleteFile(file.id)"></i>
                                </a>
                              </td>
                            </tr>
                            
                          </tbody>
                        </table>
                      </div>

                      

                      <div class="tab-pane" id="settings">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Nombre</th>
                              <th>Autor</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="file in ownFiles" :key="file.id">
                              <td> {{ file.id }} </td>
                              <td> <a :href="'./pdf/' + file.nombre + '.pdf'"> {{ file.nombre}}</a></td>
                              <td> {{ file.user_nombre }} </td>
                              <td>
                                <a :href="'api/downloadFile/' + file.id">
                                    <i class="fas fa-download green" ></i>
                                </a>
                                 /
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
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>

            <div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 v-show="!editMode"class="modal-title" id="addNewModalLabel">Crear Archivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <form @submit.prevent="createFile()" enctype="multipart/form-data">
                  
                  <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input v-model="form.nombre" type="text" name="nombre" placeholder="Nombre" class="form-control" :class="{ 'is-invalid': form.errors.has('nombre')}" required>
                        <has-error :form="form" field="nombre"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="imagen">Archivo</label>
                        <input @change="uploadFile" type="file" name="imagen">
                        <has-error :form="form" field="imagen"></has-error>
                    </div>

                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Crear</button>
                  </div>
              </form>
                </div>
              </div>
            </div>

            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Nombre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <form @submit.prevent="editFile()" enctype="multipart/form-data">
                  
                  <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input v-model="changeNameForm.nombre" type="text" name="nombre" placeholder="Nombre" class="form-control" :class="{ 'is-invalid': changeNameForm.errors.has('nombre')}" required>
                        <has-error :form="changeNameForm" field="nombre"></has-error>
                    </div>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Editar</button>
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
                files: {},
                ownFiles: {},
                form: new Form({
                    id: '',
                    nombre: '',
                    file: '',
                    user_id: '',
                    user_nombre: ''
                }),
                changeNameForm: new Form({
                    id: '',
                    old_name: '',
                    nombre: ''
                })
            }
        },
        methods: {
            uploadFile(e) {
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.onload = () => {
                    this.form.file = reader.result;
                    console.log(this.form.file);

                }
                reader.readAsDataURL(file)
            },
            loadAllFiles() {
                axios.get('api/files').then(({ data }) => (this.files = data.data))
            },
            loadOwnFiles() {
                axios.get('api/ownFiles/' + this.form.user_id).then(({ data }) => (this.ownFiles = data.data))
            },
            newModal() {
                this.editMode = false;
                this.form.reset();
                this.loadAuthUser();
                $('#addNewModal').modal('show');


            },
            createFile() {
                this.$Progress.start();
                this.form.post('api/files')
                .then(() => {
                    Fire.$emit('afterChange');
                    $('#addNewModal').modal('hide')
                    toast.fire({
                        type: "success",
                        title: "Archivo Creado"
                    })
                    this.$Progress.finish();

                })
                .catch(() => {
                    this.$Progress.fail();
                })
            },
            editFile() {
                this.$Progress.start();
                this.changeNameForm.put("api/files/" + this.changeNameForm.id)
                .then(() => {
                    $("#editModal").modal('hide');
                    swal.fire("Editado!","Se ha editado el nombre del archivo", "success");
                    this.$Progress.finish();
                    Fire.$emit("afterChange")
                })
                .catch(() => {
                    this.$Progress.fail();
                })
            },
            deleteFile(id) {
                swal.fire({
                  title: '¿ Esta seguro?',
                  text: "Esta acción es irreversible",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Si, eliminar!',
                  cancelButtonText: "Cancelar"
                }).then((result) => {

                    if(result.value) {
                        this.form.delete("api/files/"+id)
                        .then(() => {
                            swal.fire(
                              'Eliminado!',
                              'El archivo ha sido eliminado',
                              'success'
                            )
                        })
                        .catch(() => {
                            swal("Failed!","Ocurrio un error","warning")
                        })
                        Fire.$emit("afterChange")
                    }
                  
                })
            },
            loadAuthUser() {
                axios.get('api/profile')
                .then( ({ data }) => {
                    this.form.user_id = data.id;
                    this.form.user_nombre =  data.nombre + " " + data.apellido ;
                    this.loadAllFiles();
                    this.loadOwnFiles();
                })
                .catch()
            },
            editModal(id, nombre) {
                this.changeNameForm.reset();
                this.changeNameForm.id = id;
                this.changeNameForm.old_name = nombre;
                this.changeNameForm.nombre = nombre;
                $('#editModal').modal('show');
            }
        },
        mounted() {
            this.loadAuthUser();
            Fire.$on('afterChange', () => {
                this.loadAllFiles();
                this.loadOwnFiles();
            })
        }
    }
</script>
