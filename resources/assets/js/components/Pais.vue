<template>
    <main class="main">
    <!-- Breadcrumb -->
        <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
			<div class="card">
				<div class="card-header">
					<i class="fa fa-align-justify"></i> Paises
					<button type="button" @click="mostraFormulario()" class="btn btn-success btn-sm">
						<i class="icon-plus"></i>&nbsp;Nuevo
					</button>
				</div>
				<div class="card-body">
					<template v-if="listado==1">
						<div class="md-layout">
							<div class="md-layout-item">
								<md-field md-clearable>
									<label>Qué desea Buscar</label>
									<md-input v-model="buscar" @keypress="listarPais(1,buscar,criterio)"></md-input>
								</md-field>
							</div>&nbsp;&nbsp;&nbsp;
							<div class="md-layout-item">
								<br/> <md-button class="md-icon-button md-dense md-raised md-primary" @click="listarPais(1,buscar,criterio)">
								<md-icon>search</md-icon>
								</md-button>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-sm">
								<thead>
									<tr class="p-3 mb-2 bg-dark text-white">                            
										<th>nombre</th>
										<th>Opciones</th>
									</tr>
								</thead>
								<tbody>
									<tr  v-for="objeto in arrayPais" :key="objeto.id" >                            
										<td v-text="objeto.nombre"></td>
										<td>
											<md-button class="md-icon-button " @click="mostrarActualizar(objeto)" title="Actualizar">                         
												<i class="material-icons Color3">edit</i>
											</md-button>	
											<md-button class="md-icon-button md-primary " @click="eliminarPais(objeto)" title="Eliminar">
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
										<a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
									</li>
									<li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
										<a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
									</li>
									<li class="page-item" v-if="pagination.current_page < pagination.last_page">
										<a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
									</li>
								</ul>
							</nav>
					</template>
					<template v-else-if="listado==0">
						<div class="card-body">
							<form action method="post" enctype="multipart/form-data" class="form-horizontal">
								<md-card-content>
									<div class="md-layout">
										<div class="md-layout-item">
											<md-field :class="getValidationClass('nombre')">
												<label>Nombre</label>
												<md-input autocomplete="given-name" v-model="form.nombre" :disabled="sending"/>
												<span class="md-error" v-if="!$v.form.nombre.required">El nombre es requerido</span>
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
								<md-button type="submit" v-if="tipoAccion==2" class="md-dense md-raised md-primary" :disabled="sending" @click="actualizarPais()">Actualizar</md-button>
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
		form: {
			nombre:""
		},
		tipoAccion: 1,
		listado: 1,
		sending: false,
		arrayPais: [],
		id_pais:0,
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
	components: {
		vSelect,
		Multiselect
	},
	validations: {
		form: {
			nombre: {
				required
			},
		}
	},
	computed: {
		isActived: function() {
		return this.pagination.current_page;
		},
		//Calcula los elementos de la paginación
		pagesNumber: function() {
		if (!this.pagination.to) {
			return [];
		}
		var from = this.pagination.current_page - this.offset;
		if (from < 1) {
			from = 1;
		}
		var to = from + this.offset * 2;
		if (to >= this.pagination.last_page) {
			to = this.pagination.last_page;
		}
		var pagesArray = [];
		while (from <= to) {
			pagesArray.push(from);
			from++;
		}
		return pagesArray;
		},
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
				this.registrarPais();
				this.clearForm();
			}
		},
		clearForm() {
			this.id_pais = "";
			this.form.nombre="";
		},
		listarPais(page, buscar, criterio) {
            let me = this;
            var url = "/pais?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPais = respuesta.paises.data;
                    me.pagination = respuesta.pagination;
                }).catch(function (error) {
                    console.log(error);
            });
		},
		cambiarPagina(page, buscar, criterio) {
			let me = this;
			me.pagination.current_page = page;
			me.listarPais(page, buscar, criterio);
		},
		mostraFormulario() {
			this.clearForm();
			let me = this;
			(this.tipoAccion = 1), (me.listado = 0);
		},
		cerrarFormulario() {
			this.listado = 1;
			this.listarPais(1, this.buscar, this.criterio);
		},
		registrarPais() {
		let me = this;
            axios.post("/pais/registrar", {
                    'nombre': this.form.nombre,
					'id_pais': this.id_pais,
                }).then(function (response) {
					me.listarPais(1, "", "nombre");
                    me.mensaje("Guardado", "Guardo ");
                    me.cerrarFormulario();
                }).catch(function (error) {
                console.log(error);
                });
		},
		mostrarActualizar(data = []) {
			let me = this;
			(this.tipoAccion = 2), (me.listado = 0);
			(this.id_pais = data["id"]);
			// this.id_categoria = data["id_categoria"]
			this.form.nombre = data["nombre"];
		},
		actualizarPais() {
		let me = this;
            axios.put("/pais/actualizar", {
					'id':this.id_pais,
                    'nombre': this.form.nombre,
            })
            .then(function (response) {
                me.listarPais(1, "", "nombre");
                me.mensaje("Actualizado", "Actualizo");
                me.cerrarFormulario();
            })
            .catch(function (error) {
                console.log(error);
            });
		},
		eliminarPais(data = []) {
			swal({
				title: "¿Esta seguro de Eliminar el producto " + data["nombre"],
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
				this.id_pais = data["id"];
				axios
					.post("/pais/eliminar", {
					id: this.id_pais
					})
					.then(function(response) {
						me.listarPais(1, "", "nombre");
					
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
		this.listarPais(1, this.buscar, this.criterio);
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
	.material-icons.Color5 { color: rgba(59, 167, 230, 0.863); }
</style>