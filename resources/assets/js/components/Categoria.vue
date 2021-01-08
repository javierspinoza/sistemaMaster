<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" @click="abrirModal('categoria','registrar')" class="btn btn-success">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="md-layout">
							<div class="md-layout-item">
								<md-field md-clearable>
									<label>Qué desea Buscar</label>
									<md-input v-model="buscar" @keypress="listarCategoria(1,buscar,criterio)"></md-input>
								</md-field>
							</div>&nbsp;&nbsp;&nbsp;
							<div class="md-layout-item">
								<br/> <md-button class="md-icon-button md-dense md-raised md-primary" @click="listarCategoria(1,buscar,criterio)">
								<md-icon>search</md-icon>
								</md-button>
							</div>
						</div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr class="p-3 mb-2 bg-dark text-white">
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="objeto in arrayCategoria" :key="objeto.id">
                                        <td v-text="objeto.nombre"></td>
                                        <td v-text="objeto.descripcion"></td>
                                        <td>
                                            <div v-if="objeto.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
                                        <td>
                                            <md-button class="md-icon-button" @click="abrirModal('categoria','actualizar',objeto)" title="Desactivar">
                                                    <i class="material-icons Color2">edit</i>
                                            </md-button> &nbsp;
                                            <template v-if="objeto.condicion">
                                                <md-button class="md-icon-button" @click="desactivarCategoria(objeto.id)" title="Desactivar">
                                                    <i class="material-icons Color4">highlight_off</i>
                                                </md-button>
                                            </template>
                                            <template v-else>
                                                <md-button class="md-icon-button" @click="activarCategoria(objeto.id)" title="Activar">
                                                    <i class="material-icons Color5">check_circle_outline</i>
                                                </md-button>
                                            </template>
                                            <md-button class="md-icon-button" @click="eliminarCategoria(objeto)" title="Eliminar">
                                                    <i class="material-icons Color4">delete</i>
                                            </md-button>
                                        </td>
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <md-card-content>
                                    <div class="md-layout">
                                        <div class="md-layout-item md-size-30">
                                            <md-field :class="getValidationClass('nombre')">
                                                <label for="nombre">Nombre</label>
                                                <md-input type="text" id="nombre" name="nombre" autocomplete="given-name" v-model="form.nombre" :disabled="sending" />
                                                <span class="md-error" v-if="!$v.form.nombre.required">El nombre es requerido</span>																																	               
                                            </md-field>
                                        </div>&nbsp;&nbsp;&nbsp;
                                        <div class="md-layout-item md-size-30">
                                            <md-field :class="getValidationClass('descripcion')">
                                                <label for="descripcion">Descripción</label>
                                                <md-input type="text" id="descripcion" name="descripcion" autocomplete="given-name" v-model="form.descripcion" :disabled="sending" />
                                                <span class="md-error" v-if="!$v.form.descripcion.maxlength">la descripción debe tener máximo 30 caracteres</span>																																	               
                                            </md-field>
                                        </div>
                                    </div>
                                </md-card-content>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <md-card-actions>
                                <md-button type="button" class="md-raised" @click="cerrarModal()">Cerrar</md-button>
                            </md-card-actions>
                            <md-card-actions>
                                <md-button type="submit" v-if="tipoAccion==1" class="md-dense md-raised md-primary" :disabled="sending" @click="validarDatos()">Guardar</md-button>
                            </md-card-actions>
                            <md-card-actions>
                                <md-button type="submit" v-if="tipoAccion==2" class="md-dense md-raised md-primary" :disabled="sending" @click="actualizarCategoria()">Actualizar</md-button>
                            </md-card-actions>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    import { validationMixin } from "vuelidate";
    import { MdButton, MdContent, MdField,MdAutocomplete,MdCard, MdMenu, MdList } from "vue-material/dist/components";
    // import VueMaterial from 'vue-material'
    // Vue.use(VueMaterial)
    Vue.use(MdButton);
    Vue.use(MdContent);
    Vue.use(MdField);
    Vue.use(MdAutocomplete);
    Vue.use(MdCard);
    Vue.use(MdMenu);
    Vue.use(MdList);
    import { required, minLength, maxLength } from "vuelidate/lib/validators";

    export default {
        mixins: [validationMixin],

        data() {
            return {

            form: {
                nombre: "",
                descripcion: ""
            },
            sending: false,
            id_categoria: 0,
            arrayCategoria: [],

            modal: 0,
            tituloModal: "",
            tipoAccion: 0,
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0
            },
            offset: 3,
            criterio: "nombre",
            buscar: ""
            };
        },

        validations: {
            form: {
            nombre: {
                required
            },
            descripcion: {
                required,
                maxLength: maxLength(30)
            },
            }
        },

        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {

            getValidationClass(fieldName) {
            const field = this.$v.form[fieldName];
            if (field) {
                return {
                "md-invalid": field.$invalid && field.$dirty
                };
            }
            },
            nameWithLang({ nombre }) {
            return `${nombre}`;
            },
            validarDatos() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    this.registrarCategoria();
                    this.clearForm();
                }
            },
            clearForm() {
            this.$v.$reset();
            this.form.nombre = "";
            this.form.descripcion = "";
            
            },

            listarCategoria (page,buscar,criterio){
                let me=this;
                var url='/categoria?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCategoria = respuesta.categorias.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarCategoria(page,buscar,criterio);
            },
            registrarCategoria(){
                
                let me = this;

                axios.post('/categoria/registrar',{
                    'nombre': this.form.nombre,
                    'descripcion': this.form.descripcion
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarCategoria(){
                
                let me = this;

                axios.put('/categoria/actualizar',{
                    'nombre': this.form.nombre,
                    'descripcion': this.form.descripcion,
                    'id': this.id_categoria
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            eliminarCategoria(data = []) {
			swal({
				title: "¿Esta seguro de eliminar el concepto " + data["nombre"],
				type: "warning",
				title: "¿Estás seguro?",
				text: "¡No podrás revertir esto!",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				cancelButtonText: "¡No, cancélalo!",
				confirmButtonText: "¡Si, bórralo!"
			}).then(result => {
				if (result.value) {
				let me = this;
				this.id_categoria = data["id"];
				axios
					.post("/categoria/eliminar", {
					id: this.id_categoria
					})
					.then(function(response) {
						me.listarCategoria(1, "", "nombre");
					
					me.mensaje("Eliminado", "Eliminó ");
					})
					.catch(function(error) {
					console.log(error);
					});
				} else if (
				// Read more about handling dismissals
				result.dismiss === swal.DismissReason.cancel
				) {
				}
            });
            },
            desactivarCategoria(id){
                swal({  
                title: '¿Está seguro de desactivar esta categoria?',
					type: 'warning',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Aceptar',
					cancelButtonText: 'Cancelar',
					// buttonsStyling: false,
					reverseButtons: true,
					}).then((result) => {
					if (result.value) {
						let me = this;
						axios.put('/categoria/desactivar',{
							'id': id
						}).then(function (response) {
							me.listarCategoria(1,'','nombre');
							me.mensaje("Desactivado","desactivo");
						}).catch(function (error) {
							console.log(error);
						});    
					} else if (
						// Read more about handling dismissals
						result.dismiss === swal.DismissReason.cancel
					) {
					}
				}) 
            },
            activarCategoria(id){
			    swal({
                    title: '¿Está seguro de activar esta categoria?',
                    type: 'warning',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    // buttonsStyling: false,
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;
                        axios.put('/categoria/activar',{
                            'id': id
                        }).then(function (response) {
                            me.listarCategoria(1,'','nombre');
                            me.mensaje("Activado","activo");
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                    }
                }) 
		    },
            cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.clearForm();
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "categoria":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Categoría';
                                this.form.nombre= '';
                                this.form.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar categoría';
                                this.tipoAccion=2;
                                this.id_categoria=data['id'];
                                this.form.nombre = data['nombre'];
                                this.form.descripcion= data['descripcion'];
                                break;
                            }
                        }
                    }
                }
            },
            mensaje(tipo, crud) {
			swal(tipo, "El registro se " + crud + " con éxito.", "success");
		    }
        },
        mounted() {
            this.listarCategoria(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .material-icons.Color1 { color: rgb(31, 33, 34); }
	.material-icons.Color2 { color: rgba(223, 195, 37, 0.966); }
	.material-icons.Color3 { color: rgb(12, 170, 91); }
	.material-icons.Color4 { color: rgba(228, 54, 54, 0.863); }
    .material-icons.Color5 { color: rgba(59, 167, 230, 0.863); }
</style>
