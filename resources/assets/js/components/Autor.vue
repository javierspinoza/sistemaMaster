<template>
    <main class="main">
    <!-- Breadcrumb -->
        <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
			<div class="card">
				<div class="card-header">
					<i class="fa fa-align-justify"></i> Autores
					<button type="button" @click="mostraFormulario()" class="btn btn-success btn-sm">
						<i class="icon-plus"></i>&nbsp;Nuevo
					</button>
				</div>
				<div class="card-body">
					<template v-if="listado==1">
						<div class="table-responsive">
							<table class="table table-striped table-bordered display" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>                    
										<th>Nombre</th>
										<th>Apellidos</th>
										<th>Email</th>
										<th>Pais</th>
										<!-- <th>Estado</th> -->
										<th>Opciones</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
								<tfoot>
									<tr>                    
										<th>Nombre</th>
										<th>Apellidos</th>
										<th>Email</th>
										<th>Pais</th>
										<!-- <th>Estado</th> -->
										<th>Opciones</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</template>
					<template v-else-if="listado==0">
						<div class="card-body">
							<form action method="post" enctype="multipart/form-data" class="form-horizontal">
								<md-card-content>
									<div class="md-layout ">
										<div class="md-layout-item">
											<md-field :class="getValidationClass('nombre')">
												<label>Nombre</label>
												<md-input autocomplete="given-name" v-model="form.nombre" :disabled="sending"/>
												<span class="md-error" v-if="!$v.form.nombre.required">El nombre es requerido</span>
											</md-field>
										</div>&nbsp;&nbsp;&nbsp;
                                        <div class="md-layout-item">
											<md-field :class="getValidationClass('apellidos')">
												<label>Apellidos</label>
												<md-input autocomplete="given-name" v-model="form.apellidos" :disabled="sending"/>
												<span class="md-error" v-if="!$v.form.apellidos.required">Los apellidos son requeridos</span>
											</md-field>
										</div>
									</div>
									<div class="md-layout">
                                        <div class="md-layout-item">
											<md-field>
												<label for="email">Email</label>
												<md-input type="text" id="email" name="email" autocomplete="given-name" v-model="email" :disabled="sending" />
											</md-field>
										</div>&nbsp;&nbsp;&nbsp;
                                        <div class="md-layout-item">
											<md-field md-clearable>
												<label>Pais</label>
												<md-select v-model="id_pais" md-dense>
												<md-option
													v-for="(paises,index) in arrayPais" :key="`paises-${index}`" :value="paises.id">{{paises.nombre}}</md-option>
												</md-select>
											</md-field>
										</div>
									</div>
								</md-card-content>
							</form>
						</div>
						<div class="modal-footer">
							<md-card-actions>
							<md-button type="button" class="md-raised" @click="cerrarFormulario()">Cerrar</md-button>
								<md-button type="submit" v-if="tipoAccion==1" class="md-dense md-raised md-primary" :disabled="sending" @click="validarDatos()">Guardar</md-button>
								<md-button type="submit" v-if="tipoAccion==2" class="md-dense md-raised md-primary" :disabled="sending" @click="actualizarAutor()">Actualizar</md-button>
							</md-card-actions>
						</div>
					</template>
				</div>
			</div>
        </div>
    </main>
</template>
<script>

    import { validationMixin } from "vuelidate";
    import Multiselect from "vue-multiselect";
    import Toasted from 'vue-toasted';
    import vSelect from "vue-select";
    import {
		MdButton,  
		MdContent,
		MdField,
		MdCard,
		MdMenu,
		MdSwitch,
		MdDatepicker,
		MdList
    } from "vue-material/dist/components";

    Vue.use(Toasted,  {
        iconPack : 'material' // set your iconPack, defaults to material. material|fontawesome|custom-class
    });
    Vue.use(MdButton);

    Vue.use(MdContent);
    Vue.use(MdField);
    Vue.use(MdCard);
    Vue.use(MdMenu);
    Vue.use(MdSwitch);
    Vue.use(MdList);
    Vue.use(MdDatepicker);
    import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
	mixins: [validationMixin],
	data() {
    
		return {

		email:"",
		// datas:"",
		form: {
			nombre:"",
			apellidos:"",
		},
		id_pais: "",
		tipoAccion: 1,
		listado: 1,
		sending: false,
		arrayPais: [],
		arrayAutor:[],
		id_autor:0,
		modal: 0,
		tituloModal: "",
		tipoAccion: 0,
		};
	},
	components: {
		vSelect,
		Multiselect
	},
	validations: {
		form: {
			nombre: {
				required
			},
			apellidos: {
				required
			},
			
		}
	},
	computed: {
		submittableDateTime() {
			const date = new Date(this.date);
			console.log(date);
			return date;
		},
		dateFormat() {
			return this.$material.locale.dateFormat ||"YYYY-MM-dd";
			// return moment(date).format('LL')
		},
		mdType() {
			switch (this.mdTypeValue) {
				case "fecCompra":
				return String;
			}
		}
	},
	methods: {
		//metodo que valida
		getValidationClass(fieldName) {
			const field = this.$v.form[fieldName];
			if (field) {
				return {
				"md-invalid": field.$invalid && field.$dirty
				};
			}
		},
		validarDatos() {
			this.$v.$touch();
			if (!this.$v.$invalid) {
				this.registrarAutor();
				this.clearForm();
			}
		},
		clearForm() {
			this.id_pais = "";
			this.email="";
			this.form.nombre="";
			this.form.apellidos="";
		},
		listarAutor() {
            let me = this;
            var url = "/autor";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
					me.arrayAutor = respuesta.autores.data;
					me.myTable(me.arrayAutor);
                    // me.pagination = respuesta.pagination;
                }).catch(function (error) {
                    console.log(error);
            });
		},
		myTable(datas){
		let me = this;

			$(document).ready(function() {
			
				var table = $('#dataTable').DataTable({destroy: true,
					stateSave: true,
					data:datas,
					"createdRow": function( row, data, dataIndex){
						if( data[6] ==  `0`){
							$(row).addClass('redClass');
						}
					},
					"language": {
						"lengthMenu": "Ver _MENU_ registros por página",
						"zeroRecords": "NO existen Datos",
						"info": "mostrando la página _PAGE_ de _PAGES_",
						"infoEmpty": "No hay registros disponibles",
						"search":         "Buscar:",
						"paginate": {
							"first":      "Prim",
							"last":       "Ant",
							"next":       "Sig",
							"previous":   "Ant"
						},
						"infoFiltered": "(filtrado de _MAX_ total registros)"
					},
					responsive: "true",
					"columns": [
						{ "data": "nombre" },
						{ "data": "apellidos" },
						{ "data": "email" },
						{ "data": "nombrePais" },
						// {"defaultContent": "<button class='btn'> <i class='fa fa-home'></i></button>"}
						// {"defaultContent": "<button type='button' @click='hola() class='btn btn-success btn-sm' data-tooltip title='Actualizar' > <i class='icon-pencil'></i>  </button> "},
						{"defaultContent": "<button type='button' id='editar' class='editar btn btn-success btn-sm' data-tooltip title='Editar' > <i class='fas fa-edit'></i> </button> <button type='button' id='eliminar' class='eliminar btn btn-danger btn-sm' data-tooltip title='Eliminar' > <i class='icon-trash'></i>  </button>"},
						// {"defaultContent": "}
						// {"defaultContent": " <button type='button' class='md-raised' @click='this.hola()'> Cerrar </button>"}
					]
				});

				$('#dataTable tbody').on( 'click', '.editar', function () {
					me.datos = table.row( $(this).parents('tr') ).data();
					me.mostrarActualizar(me.datos);
					//  console.log(data['nombre']);
				});
				$('#dataTable tbody').on( 'click', '.eliminar', function () {
					me.datos= table.row( $(this).parents('tr') ).data();
					me.eliminarAutor(me.datos);
					//  console.log(data['modelo']);
				});
				
			});
		},
		selectPais() {
            let me = this;
            var url = "pais/selectPais";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPais = respuesta.paises;
                }).catch(function (error) {
                    console.log(error);
            });
        },
		mostraFormulario() {
			this.clearForm();
			let me = this;
			(this.tipoAccion = 1), (me.listado = 0);
		},
		cerrarFormulario() {
			this.listado = 1;
			this.listarAutor();
		},
		registrarAutor() {
		let me = this;
            axios.post("/autor/registrar", {
				'email': this.email,
                    'nombre': this.form.nombre,
                    'apellidos': this.form.apellidos,
					'id_pais': this.id_pais,
                }).then(function (response) {
					me.listarAutor();
                    me.mensaje("Guardado", "Guardo ");
                    me.cerrarFormulario();
                }).catch(function (error) {
                console.log(error);
                });
		},
		mostrarActualizar(data = []) {
			let me = this;
			(this.tipoAccion = 2), (me.listado = 0);
			(this.id_autor = data["id"]);
			this.id_pais = data["id_pais"];
			this.email = data["email"];
			this.form.nombre = data["nombre"];
			this.form.apellidos = data["apellidos"];
		},
		actualizarAutor() {
		let me = this;
            axios.put("/autor/actualizar", {
					'id':this.id_autor,
                    'email': this.email,
                    'nombre': this.form.nombre,
                    'apellidos': this.form.apellidos,
					'id_pais': this.id_pais,
            })
            .then(function (response) {
                me.listarAutor();
                me.mensaje("Actualizado", "Actualizo");
                me.cerrarFormulario();
            })
            .catch(function (error) {
                console.log(error);
            });
		},
		desactivarAutor(id){
            swal({  
                title: '¿Está seguro de desactivar este autor?',
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
						axios.put('/autor/desactivar',{
							'id': id
						}).then(function (response) {
							me.listarAutor();
							me.mensaje("¡Desactivado correctamente!", "desactivo ");
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
		activarAutor(id){
			swal({
				title: '¿Está seguro de activar este autor?',
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
					axios.put('/autor/activar',{
						'id': id
					}).then(function (response) {
						me.listarAutor();
						me.mensaje("¡Activado correctamente!", "activo ");
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
		eliminarAutor(data = []) {
			swal({
				title: "¿Esta seguro de Eliminar el autor " + data["nombre"],
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
				this.id_autor = data["id"];
				axios
					.post("/autor/eliminar", {
					id: this.id_autor
					})
					.then(function(response) {
						me.listarAutor();
					
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
		mensaje(tipo, crud) {
			swal(tipo, "El registro se " + crud + " con éxito.", "success");
		}
	},
	mounted() {
		this.selectPais();
		this.listarAutor();
	}
};
</script>
<style>
	.modal-content {
	width: 100% !important;
	position: absolute !important;
	}
	.mostrar {
	display: list-item !important;
	opacity: 1 !important;
	position: absolute !important;
	background-color: #3c29297a !important;
	}
	.div-error {
	display: flex;
	justify-content: center;
	}
	.text-error {
	color: red !important;
	font-weight: bold;
	}
	.material-icons.Color1 { color: rgb(31, 33, 34); }
	.material-icons.Color2 { color: rgba(223, 195, 37, 0.966); }
	.material-icons.Color3 { color: rgb(12, 170, 91); }
	.material-icons.Color4 { color: rgba(228, 54, 54, 0.863); }
</style>