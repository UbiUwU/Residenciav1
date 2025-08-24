-- DROP SCHEMA public;

CREATE SCHEMA public AUTHORIZATION postgres;

-- DROP TYPE public."estado_enum";

CREATE TYPE public."estado_enum" AS ENUM (
	'activo',
	'inactivo',
	'pendiente',
	'eliminado',
	'borrador',
	'en_progreso',
	'completado',
	'cancelado',
	'rechazado',
	'aprobado',
	'archivado',
	'error',
	'bloqueado',
	'suspendido');

-- DROP TYPE public."estado_plantilla";

CREATE TYPE public."estado_plantilla" AS ENUM (
	'activo',
	'en_uso',
	'inactivo');

-- DROP TYPE public."origen_enum";

CREATE TYPE public."origen_enum" AS ENUM (
	'Interno',
	'Externo',
	'Nacional',
	'Internacional',
	'Otro');

-- DROP TYPE public."tipo_comp";

CREATE TYPE public."tipo_comp" AS ENUM (
	'Específica',
	'Previas',
	'Generica');

-- DROP TYPE public."tipo_plantilla";

CREATE TYPE public."tipo_plantilla" AS ENUM (
	'avance',
	'instrumentacion',
	'reporte_final');

-- DROP SEQUENCE public.actividad_aprendizaje_tema_id_actividad_seq;

CREATE SEQUENCE public.actividad_aprendizaje_tema_id_actividad_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.actividad_aprendizaje_tema_idactaprendizaje_seq;

CREATE SEQUENCE public.actividad_aprendizaje_tema_idactaprendizaje_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.actividad_competencia_tema_idactcompetencia_seq;

CREATE SEQUENCE public.actividad_competencia_tema_idactcompetencia_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.actividades_ensenanza_instrum_id_actividades_ensenanza_inst_seq;

CREATE SEQUENCE public.actividades_ensenanza_instrum_id_actividades_ensenanza_inst_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.alumnos_numero_control_seq;

CREATE SEQUENCE public.alumnos_numero_control_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 19390003
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.apoyos_didacticos_instrumentacion_id_apoyo_didactico_seq;

CREATE SEQUENCE public.apoyos_didacticos_instrumentacion_id_apoyo_didactico_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.asignatura_carrera_idasigcarrera_seq;

CREATE SEQUENCE public.asignatura_carrera_idasigcarrera_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.avance_detalles_fechas_id_avance_detalle_fecha_seq;

CREATE SEQUENCE public.avance_detalles_fechas_id_avance_detalle_fecha_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.avance_detalles_id_avance_detalle_seq;

CREATE SEQUENCE public.avance_detalles_id_avance_detalle_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.avance_fechas_id_avance_fecha_seq;

CREATE SEQUENCE public.avance_fechas_id_avance_fecha_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.avance_id_avance_seq;

CREATE SEQUENCE public.avance_id_avance_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.bitacora_alumnos_id_seq;

CREATE SEQUENCE public.bitacora_alumnos_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 9
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.bitacora_maestros_id_seq;

CREATE SEQUENCE public.bitacora_maestros_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 31
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.calendarizacion_evaluacion_instrumentaci_id_calendarizacion_seq;

CREATE SEQUENCE public.calendarizacion_evaluacion_instrumentaci_id_calendarizacion_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.calificaciones_id_seq;

CREATE SEQUENCE public.calificaciones_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.carga_academica_detalles_id_carga_detalle_seq;

CREATE SEQUENCE public.carga_academica_detalles_id_carga_detalle_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 2
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.carga_academica_general_id_carga_general_seq;

CREATE SEQUENCE public.carga_academica_general_id_carga_general_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 2
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.catalogo_tipos_fecha_id_tipo_fecha_seq;

CREATE SEQUENCE public.catalogo_tipos_fecha_id_tipo_fecha_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.comision_fecha_id_comision_fecha_seq;

CREATE SEQUENCE public.comision_fecha_id_comision_fecha_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.comision_id_comision_seq;

CREATE SEQUENCE public.comision_id_comision_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.comision_maestro_id_seq;

CREATE SEQUENCE public.comision_maestro_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.competencia_especifica_tema_id_competencia_especifica_seq;

CREATE SEQUENCE public.competencia_especifica_tema_id_competencia_especifica_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.competencia_generica_tema_id_competencia_generica_seq;

CREATE SEQUENCE public.competencia_generica_tema_id_competencia_generica_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.competencia_generico_instrume_id_competencia_generico_instr_seq;

CREATE SEQUENCE public.competencia_generico_instrume_id_competencia_generico_instr_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.competencia_idcompetencia_seq;

CREATE SEQUENCE public.competencia_idcompetencia_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.competencia_tema_idcomptema_seq;

CREATE SEQUENCE public.competencia_tema_idcomptema_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.competencias_instrumentacion_id_competencia_seq;

CREATE SEQUENCE public.competencias_instrumentacion_id_competencia_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.datos_estaticos_reportefinal_id_datos_estaticos_seq;

CREATE SEQUENCE public.datos_estaticos_reportefinal_id_datos_estaticos_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.disenio_curricular_id_disenio_curricular_seq;

CREATE SEQUENCE public.disenio_curricular_id_disenio_curricular_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public."disenio_curricular_participantes_IdParticipacion_seq";

CREATE SEQUENCE public."disenio_curricular_participantes_IdParticipacion_seq"
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.diseno_curricular_iddiscurricular_seq;

CREATE SEQUENCE public.diseno_curricular_iddiscurricular_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.diseno_participante_iddisparticipante_seq;

CREATE SEQUENCE public.diseno_participante_iddisparticipante_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.evaluacion_competencias_id_evaluacion_seq;

CREATE SEQUENCE public.evaluacion_competencias_id_evaluacion_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.evaluacion_competencias_instrumen_id_evaluacion_competencia_seq;

CREATE SEQUENCE public.evaluacion_competencias_instrumen_id_evaluacion_competencia_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.evaluacion_idevaluacion_seq;

CREATE SEQUENCE public.evaluacion_idevaluacion_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.fechas_clave_periodo_id_fecha_clave_seq;

CREATE SEQUENCE public.fechas_clave_periodo_id_fecha_clave_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.fuente_informacion_idfuenteinfo_seq;

CREATE SEQUENCE public.fuente_informacion_idfuenteinfo_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.fuentes_informacion_id_fuente_seq;

CREATE SEQUENCE public.fuentes_informacion_id_fuente_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.horarioasignatura_maestro_clave_horario_seq;

CREATE SEQUENCE public.horarioasignatura_maestro_clave_horario_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 4
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.horarios_maestros_idhorariomaestro_seq;

CREATE SEQUENCE public.horarios_maestros_idhorariomaestro_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.indicadores_alcance_evaluacion_instrum_id_indicador_alcance_seq;

CREATE SEQUENCE public.indicadores_alcance_evaluacion_instrum_id_indicador_alcance_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.indicadores_alcance_id_indicador_alcance_seq;

CREATE SEQUENCE public.indicadores_alcance_id_indicador_alcance_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.indicadores_alcance_instrumentacion_id_indicador_seq;

CREATE SEQUENCE public.indicadores_alcance_instrumentacion_id_indicador_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.instrumentacion_id_instrumentacion_seq;

CREATE SEQUENCE public.instrumentacion_id_instrumentacion_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.liberacion_academica_detalles_id_detalle_seq;

CREATE SEQUENCE public.liberacion_academica_detalles_id_detalle_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.liberacion_academica_id_liberacion_seq;

CREATE SEQUENCE public.liberacion_academica_id_liberacion_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.liberacion_academica_tarjeta_maestro_seq;

CREATE SEQUENCE public.liberacion_academica_tarjeta_maestro_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.liberacion_docente_detalles_id_detalle_seq;

CREATE SEQUENCE public.liberacion_docente_detalles_id_detalle_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.liberacion_docente_id_liberacion_seq;

CREATE SEQUENCE public.liberacion_docente_id_liberacion_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.liberacion_docente_tarjeta_maestro_seq;

CREATE SEQUENCE public.liberacion_docente_tarjeta_maestro_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.maestros_tarjeta_seq;

CREATE SEQUENCE public.maestros_tarjeta_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 100002
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.migrations_id_seq;

CREATE SEQUENCE public.migrations_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 20
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.niveles_desempeno_instrumentacion_id_nivel_desempeno_seq;

CREATE SEQUENCE public.niveles_desempeno_instrumentacion_id_nivel_desempeno_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.notificaciones_carreras_idnotificacioncarrera_seq;

CREATE SEQUENCE public.notificaciones_carreras_idnotificacioncarrera_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.notificaciones_idnotificaciones_seq;

CREATE SEQUENCE public.notificaciones_idnotificaciones_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.notificaciones_usuario_idnotificacionusuario_seq;

CREATE SEQUENCE public.notificaciones_usuario_idnotificacionusuario_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.participante_idparticipante_seq;

CREATE SEQUENCE public.participante_idparticipante_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.periodo_escolar_id_periodo_escolar_seq;

CREATE SEQUENCE public.periodo_escolar_id_periodo_escolar_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 2
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.personal_access_tokens_id_seq;

CREATE SEQUENCE public.personal_access_tokens_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.practias_asignatura_id_practicas_seq;

CREATE SEQUENCE public.practias_asignatura_id_practicas_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.practica_idpractica_seq;

CREATE SEQUENCE public.practica_idpractica_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public."presentacion_caracterizacion_id_Caracterizacion_seq";

CREATE SEQUENCE public."presentacion_caracterizacion_id_Caracterizacion_seq"
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public."presentacion_id_Presentacion_seq";

CREATE SEQUENCE public."presentacion_id_Presentacion_seq"
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.presentacion_idpresentacion_seq;

CREATE SEQUENCE public.presentacion_idpresentacion_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public."presentacion_intencion_id_Intencion_seq";

CREATE SEQUENCE public."presentacion_intencion_id_Intencion_seq"
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.proyecto_asignatura_id_proyecto_seq;

CREATE SEQUENCE public.proyecto_asignatura_id_proyecto_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.proyecto_asignatura_idproyectoasig_seq;

CREATE SEQUENCE public.proyecto_asignatura_idproyectoasig_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.reportefinal_asignatura_id_reportefinal_asignatura_seq;

CREATE SEQUENCE public.reportefinal_asignatura_id_reportefinal_asignatura_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.reportefinal_id_reportefinal_seq;

CREATE SEQUENCE public.reportefinal_id_reportefinal_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.reservacionalumnos_id_reservacion_alumno_seq;

CREATE SEQUENCE public.reservacionalumnos_id_reservacion_alumno_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 2
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.reservacionmaestros_id_reservacion_maestro_seq;

CREATE SEQUENCE public.reservacionmaestros_id_reservacion_maestro_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 13
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public."subtema_id_Subtema_seq";

CREATE SEQUENCE public."subtema_id_Subtema_seq"
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.subtema_idsubtema_seq;

CREATE SEQUENCE public.subtema_idsubtema_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.tema_idtema_seq;

CREATE SEQUENCE public.tema_idtema_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.tipo_evento_id_tipo_evento_seq;

CREATE SEQUENCE public.tipo_evento_id_tipo_evento_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.tipo_notificaciones_idtiponotif_seq;

CREATE SEQUENCE public.tipo_notificaciones_idtiponotif_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE public.usuarios_id_usuario_seq;

CREATE SEQUENCE public.usuarios_id_usuario_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 3
	CACHE 1
	NO CYCLE;-- public.asignatura definition

-- Drop table

-- DROP TABLE public.asignatura;

CREATE TABLE public.asignatura (
	"ClaveAsignatura" varchar(20) NOT NULL,
	"NombreAsignatura" varchar(100) NOT NULL,
	"Creditos" int4 NOT NULL,
	"Satca_Practicas" int4 NOT NULL,
	"Satca_Teoricas" int4 NOT NULL,
	"Satca_Total" int4 NOT NULL,
	CONSTRAINT asignatura_pkey PRIMARY KEY ("ClaveAsignatura")
);


-- public.carreras definition

-- Drop table

-- DROP TABLE public.carreras;

CREATE TABLE public.carreras (
	clavecarrera varchar(20) NOT NULL,
	nombre varchar(100) NOT NULL,
	descripcion varchar(255) NOT NULL,
	generacion int4 NOT NULL,
	CONSTRAINT carreras_pkey PRIMARY KEY (clavecarrera)
);


-- public.catalogo_tipos_fecha definition

-- Drop table

-- DROP TABLE public.catalogo_tipos_fecha;

CREATE TABLE public.catalogo_tipos_fecha (
	id_tipo_fecha serial4 NOT NULL,
	clave varchar(50) NOT NULL,
	nombre varchar(100) NOT NULL,
	descripcion text NULL,
	es_activo bool DEFAULT true NULL,
	created_at timestamp DEFAULT now() NOT NULL,
	CONSTRAINT catalogo_tipos_fecha_clave_key UNIQUE (clave),
	CONSTRAINT catalogo_tipos_fecha_pkey PRIMARY KEY (id_tipo_fecha)
);


-- public.departamentos definition

-- Drop table

-- DROP TABLE public.departamentos;

CREATE TABLE public.departamentos (
	id_departamento int2 NOT NULL,
	nombre varchar(100) NOT NULL,
	abreviacion varchar(10) NOT NULL,
	CONSTRAINT departamentos_pkey PRIMARY KEY (id_departamento)
);


-- public.edificios definition

-- Drop table

-- DROP TABLE public.edificios;

CREATE TABLE public.edificios (
	claveedificio varchar(20) NOT NULL,
	nombre varchar(100) NOT NULL,
	descripcion varchar(255) NOT NULL,
	CONSTRAINT edificios_pkey PRIMARY KEY (claveedificio)
);


-- public.grupos definition

-- Drop table

-- DROP TABLE public.grupos;

CREATE TABLE public.grupos (
	clavegrupo varchar(20) NOT NULL,
	nombre varchar(100) NOT NULL,
	descripcion varchar(255) NOT NULL,
	CONSTRAINT grupos_pkey PRIMARY KEY (clavegrupo)
);


-- public.migrations definition

-- Drop table

-- DROP TABLE public.migrations;

CREATE TABLE public.migrations (
	id_migration int4 DEFAULT nextval('migrations_id_seq'::regclass) NOT NULL,
	migration varchar(255) NOT NULL,
	batch int4 NOT NULL,
	CONSTRAINT migrations_id_migration_check CHECK ((id_migration > 0)),
	CONSTRAINT migrations_pkey PRIMARY KEY (id_migration)
);


-- public.participante definition

-- Drop table

-- DROP TABLE public.participante;

CREATE TABLE public.participante (
	"id_Participante" int8 DEFAULT nextval('participante_idparticipante_seq'::regclass) NOT NULL,
	"Nombre_Participante" varchar(255) NOT NULL,
	CONSTRAINT participante_pkey PRIMARY KEY ("id_Participante")
);


-- public.periodo_escolar definition

-- Drop table

-- DROP TABLE public.periodo_escolar;

CREATE TABLE public.periodo_escolar (
	id_periodo_escolar bigserial NOT NULL,
	codigoperiodo varchar(20) NOT NULL,
	nombre_periodo varchar(100) NOT NULL,
	fecha_inicio date NOT NULL,
	fecha_fin date NOT NULL,
	estado bool DEFAULT true NULL,
	created_at timestamp DEFAULT now() NOT NULL,
	updated_at timestamp DEFAULT now() NOT NULL,
	CONSTRAINT periodo_escolar_codigoperiodo_key UNIQUE (codigoperiodo),
	CONSTRAINT periodo_escolar_fecha_check CHECK ((fecha_inicio <= fecha_fin)),
	CONSTRAINT periodo_escolar_id_periodo_escolar_check CHECK ((id_periodo_escolar > 0)),
	CONSTRAINT periodo_escolar_pkey PRIMARY KEY (id_periodo_escolar)
);


-- public.personal_access_tokens definition

-- Drop table

-- DROP TABLE public.personal_access_tokens;

CREATE TABLE public.personal_access_tokens (
	id bigserial NOT NULL,
	tokenable_type varchar(255) NOT NULL,
	tokenable_id int8 NOT NULL,
	"name" varchar(255) NOT NULL,
	"token" varchar(64) NOT NULL,
	abilities text NULL,
	last_used_at timestamp NULL,
	expires_at timestamp NULL,
	created_at timestamp NULL,
	updated_at timestamp NULL,
	CONSTRAINT personal_access_tokens_id_check CHECK ((id > 0)),
	CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id),
	CONSTRAINT personal_access_tokens_tokenable_id_check CHECK ((tokenable_id > 0))
);
CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


-- public.roles definition

-- Drop table

-- DROP TABLE public.roles;

CREATE TABLE public.roles (
	idrol int8 NOT NULL,
	nombre varchar(50) NOT NULL,
	CONSTRAINT roles_idrol_check CHECK ((idrol > 0)),
	CONSTRAINT roles_pkey PRIMARY KEY (idrol)
);


-- public.sessions definition

-- Drop table

-- DROP TABLE public.sessions;

CREATE TABLE public.sessions (
	id varchar(255) NOT NULL,
	user_id int8 NULL,
	ip_address varchar(45) NULL,
	user_agent text NULL,
	payload text NOT NULL,
	last_activity int4 NOT NULL,
	CONSTRAINT sessions_pkey PRIMARY KEY (id)
);
CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);
CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


-- public.tipo_evento definition

-- Drop table

-- DROP TABLE public.tipo_evento;

CREATE TABLE public.tipo_evento (
	id_tipo_evento serial4 NOT NULL,
	nombre varchar(100) NOT NULL,
	descripcion text NULL,
	estado public."estado_enum" DEFAULT 'activo'::estado_enum NOT NULL,
	CONSTRAINT tipo_evento_nombre_key UNIQUE (nombre),
	CONSTRAINT tipo_evento_pkey PRIMARY KEY (id_tipo_evento)
);


-- public.tipo_notificaciones definition

-- Drop table

-- DROP TABLE public.tipo_notificaciones;

CREATE TABLE public.tipo_notificaciones (
	"id_Tipo_Notif" int8 DEFAULT nextval('tipo_notificaciones_idtiponotif_seq'::regclass) NOT NULL,
	"Tipo_Notif" varchar(50) NOT NULL,
	"Descripcion_Notif" varchar(255) NOT NULL,
	CONSTRAINT tipo_notificaciones_pkey PRIMARY KEY ("id_Tipo_Notif")
);


-- public.asignatura_carrera definition

-- Drop table

-- DROP TABLE public.asignatura_carrera;

CREATE TABLE public.asignatura_carrera (
	"idAsig_Carrera" int8 DEFAULT nextval('asignatura_carrera_idasigcarrera_seq'::regclass) NOT NULL,
	"Clave_Asignatura" varchar(50) NOT NULL,
	"Clave_Carrera" varchar(20) NOT NULL,
	"Semestre" int2 NOT NULL,
	"Posicion" int2 NOT NULL,
	CONSTRAINT asignatura_carrera_pkey PRIMARY KEY ("idAsig_Carrera"),
	CONSTRAINT asignatura_carrera_clave_carrera FOREIGN KEY ("Clave_Carrera") REFERENCES public.carreras(clavecarrera),
	CONSTRAINT asignatura_carrera_id_asignatura FOREIGN KEY ("Clave_Asignatura") REFERENCES public.asignatura("ClaveAsignatura")
);


-- public.aulas definition

-- Drop table

-- DROP TABLE public.aulas;

CREATE TABLE public.aulas (
	claveaula varchar(20) NOT NULL,
	claveedificio varchar(20) NOT NULL,
	nombre varchar(100) NOT NULL,
	cantidadcomputadoras int4 DEFAULT 0 NOT NULL,
	horadisponible time NOT NULL,
	estado public."estado_enum" DEFAULT 'activo'::estado_enum NOT NULL,
	CONSTRAINT aulas_pkey PRIMARY KEY (claveaula),
	CONSTRAINT aulas_claveedificio_foreign FOREIGN KEY (claveedificio) REFERENCES public.edificios(claveedificio) ON DELETE CASCADE
);
CREATE INDEX aulas_claveedificio_foreign ON public.aulas USING btree (claveedificio);


-- public.comision definition

-- Drop table

-- DROP TABLE public.comision;

CREATE TABLE public.comision (
	id_comision serial4 NOT NULL,
	folio varchar(50) NULL,
	nombre_evento varchar(100) NOT NULL,
	estado public."estado_enum" DEFAULT 'pendiente'::estado_enum NOT NULL,
	remitente_nombre varchar(100) NULL,
	remitente_puesto varchar(100) NULL,
	lugar varchar(255) NULL,
	motivo text NULL,
	tipo_comision public."origen_enum" DEFAULT 'Interno'::origen_enum NOT NULL,
	permiso_constancia bool DEFAULT false NULL,
	id_tipo_evento int4 NOT NULL,
	id_periodo_escolar int8 NOT NULL,
	CONSTRAINT comision_pkey PRIMARY KEY (id_comision),
	CONSTRAINT comision_id_periodo_escolar_fkey FOREIGN KEY (id_periodo_escolar) REFERENCES public.periodo_escolar(id_periodo_escolar),
	CONSTRAINT comision_id_tipo_evento_fkey FOREIGN KEY (id_tipo_evento) REFERENCES public.tipo_evento(id_tipo_evento)
);


-- public.comision_fecha definition

-- Drop table

-- DROP TABLE public.comision_fecha;

CREATE TABLE public.comision_fecha (
	id_comision_fecha serial4 NOT NULL,
	id_comision int4 NOT NULL,
	fecha date NOT NULL,
	hora_inicio time NULL,
	hora_fin time NULL,
	duracion interval NULL,
	observaciones text NULL,
	CONSTRAINT comision_fecha_pkey PRIMARY KEY (id_comision_fecha),
	CONSTRAINT comision_fecha_id_comision_fkey FOREIGN KEY (id_comision) REFERENCES public.comision(id_comision) ON DELETE CASCADE
);


-- public.competencia definition

-- Drop table

-- DROP TABLE public.competencia;

CREATE TABLE public.competencia (
	"id_Competencia" int8 DEFAULT nextval('competencia_idcompetencia_seq'::regclass) NOT NULL,
	"ClaveAsignatura" varchar(50) NOT NULL,
	"Descripcion" varchar(255) NOT NULL,
	"Tipo_Competencia" public."tipo_comp" NOT NULL,
	CONSTRAINT competencia_pkey PRIMARY KEY ("id_Competencia"),
	CONSTRAINT competencia_clave_asignatura FOREIGN KEY ("ClaveAsignatura") REFERENCES public.asignatura("ClaveAsignatura")
);


-- public.computadora definition

-- Drop table

-- DROP TABLE public.computadora;

CREATE TABLE public.computadora (
	numeroinventario int8 NOT NULL,
	claveaula varchar(20) NOT NULL,
	marca varchar(50) NOT NULL,
	estado varchar(50) NOT NULL,
	CONSTRAINT computadora_numeroinventario_check CHECK ((numeroinventario > 0)),
	CONSTRAINT computadora_pkey PRIMARY KEY (numeroinventario),
	CONSTRAINT computadora_claveaula_foreign FOREIGN KEY (claveaula) REFERENCES public.aulas(claveaula) ON DELETE CASCADE
);
CREATE INDEX computadora_claveaula_foreign ON public.computadora USING btree (claveaula);


-- public.departamentos_carreras definition

-- Drop table

-- DROP TABLE public.departamentos_carreras;

CREATE TABLE public.departamentos_carreras (
	id_departamento int2 NOT NULL,
	clavecarrera varchar(20) NOT NULL,
	CONSTRAINT departamentos_carreras_pkey PRIMARY KEY (id_departamento, clavecarrera),
	CONSTRAINT departamentos_carreras_clavecarrera_foreign FOREIGN KEY (clavecarrera) REFERENCES public.carreras(clavecarrera) ON DELETE CASCADE,
	CONSTRAINT departamentos_carreras_iddepartamento_foreign FOREIGN KEY (id_departamento) REFERENCES public.departamentos(id_departamento) ON DELETE CASCADE
);


-- public.disenio_curricular definition

-- Drop table

-- DROP TABLE public.disenio_curricular;

CREATE TABLE public.disenio_curricular (
	id_disenio_curricular serial4 NOT NULL,
	"ClaveAsignatura" varchar(20) NOT NULL,
	"Lugar" varchar(200) NOT NULL,
	"FechaInicio" date NOT NULL,
	"FechaFinal" date NOT NULL,
	"NombreEvento" varchar(200) NOT NULL,
	"Descripcion" text NULL,
	CONSTRAINT disenio_curricular_pkey PRIMARY KEY (id_disenio_curricular),
	CONSTRAINT fk_disenio_asignatura FOREIGN KEY ("ClaveAsignatura") REFERENCES public.asignatura("ClaveAsignatura")
);


-- public.disenio_curricular_participantes definition

-- Drop table

-- DROP TABLE public.disenio_curricular_participantes;

CREATE TABLE public.disenio_curricular_participantes (
	"IdParticipacion" serial4 NOT NULL,
	id_disenio_curricular int4 NOT NULL,
	"Instituto" varchar(200) NOT NULL,
	CONSTRAINT disenio_curricular_participantes_pkey PRIMARY KEY ("IdParticipacion"),
	CONSTRAINT fk_participante_evento FOREIGN KEY (id_disenio_curricular) REFERENCES public.disenio_curricular(id_disenio_curricular)
);


-- public.evaluacion definition

-- Drop table

-- DROP TABLE public.evaluacion;

CREATE TABLE public.evaluacion (
	"id_Evaluacion" int8 DEFAULT nextval('evaluacion_idevaluacion_seq'::regclass) NOT NULL,
	"Clave_Asignatura" varchar(50) NOT NULL,
	"Criterios_Evaluacion" varchar(255) NOT NULL,
	CONSTRAINT evaluacion_pkey PRIMARY KEY ("id_Evaluacion"),
	CONSTRAINT evaluacion_clave_asignatura FOREIGN KEY ("Clave_Asignatura") REFERENCES public.asignatura("ClaveAsignatura")
);


-- public.evaluacion_competencias definition

-- Drop table

-- DROP TABLE public.evaluacion_competencias;

CREATE TABLE public.evaluacion_competencias (
	id_evaluacion serial4 NOT NULL,
	"ClaveAsignatura" varchar(20) NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	CONSTRAINT evaluacion_competencias_pkey PRIMARY KEY (id_evaluacion),
	CONSTRAINT "evaluacion_competencias_ClaveAsignatura_fkey" FOREIGN KEY ("ClaveAsignatura") REFERENCES public.asignatura("ClaveAsignatura") ON DELETE CASCADE
);


-- public.fechas_clave_periodo definition

-- Drop table

-- DROP TABLE public.fechas_clave_periodo;

CREATE TABLE public.fechas_clave_periodo (
	id_fecha_clave bigserial NOT NULL,
	periodo_escolar_id int8 NOT NULL,
	tipo_fecha_clave varchar(50) NOT NULL,
	nombre_fecha varchar(100) NOT NULL,
	descripcion text NULL,
	fecha date NOT NULL,
	fecha_limite timestamp NULL,
	es_obligatoria bool DEFAULT true NULL,
	created_at timestamp DEFAULT now() NOT NULL,
	updated_at timestamp DEFAULT now() NOT NULL,
	CONSTRAINT fechas_clave_periodo_pkey PRIMARY KEY (id_fecha_clave),
	CONSTRAINT fechas_clave_periodo_periodo_fk FOREIGN KEY (periodo_escolar_id) REFERENCES public.periodo_escolar(id_periodo_escolar) ON DELETE CASCADE
);


-- public.fuente_informacion definition

-- Drop table

-- DROP TABLE public.fuente_informacion;

CREATE TABLE public.fuente_informacion (
	"id_Fuente_Info" int8 DEFAULT nextval('fuente_informacion_idfuenteinfo_seq'::regclass) NOT NULL,
	"Clave_Asignatura" varchar(50) NOT NULL,
	"Referencia_Fuente_Info" varchar(255) NOT NULL,
	CONSTRAINT fuente_informacion_pkey PRIMARY KEY ("id_Fuente_Info"),
	CONSTRAINT fuente_informacion_clave_asignatura FOREIGN KEY ("Clave_Asignatura") REFERENCES public.asignatura("ClaveAsignatura")
);


-- public.fuentes_informacion definition

-- Drop table

-- DROP TABLE public.fuentes_informacion;

CREATE TABLE public.fuentes_informacion (
	id_fuente serial4 NOT NULL,
	"ClaveAsignatura" varchar(20) NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	CONSTRAINT fuentes_informacion_pkey PRIMARY KEY (id_fuente),
	CONSTRAINT "fuentes_informacion_ClaveAsignatura_fkey" FOREIGN KEY ("ClaveAsignatura") REFERENCES public.asignatura("ClaveAsignatura") ON DELETE CASCADE
);


-- public.notificaciones definition

-- Drop table

-- DROP TABLE public.notificaciones;

CREATE TABLE public.notificaciones (
	"id_Notificaciones" int8 DEFAULT nextval('notificaciones_idnotificaciones_seq'::regclass) NOT NULL,
	"Titulo_Notif" varchar(100) NOT NULL,
	"Mensaje" varchar(255) NOT NULL,
	"Tipo_Notif_ID" int8 NOT NULL,
	"Fecha_Hora_Notif" timestamptz NOT NULL,
	"Fecha_Creacion" timestamptz DEFAULT now() NOT NULL,
	CONSTRAINT notificaciones_pkey PRIMARY KEY ("id_Notificaciones"),
	CONSTRAINT notificaciones_tipo_notif_id FOREIGN KEY ("Tipo_Notif_ID") REFERENCES public.tipo_notificaciones("id_Tipo_Notif")
);


-- public.notificaciones_carreras definition

-- Drop table

-- DROP TABLE public.notificaciones_carreras;

CREATE TABLE public.notificaciones_carreras (
	"id_Notificacion_Carrera" int8 DEFAULT nextval('notificaciones_carreras_idnotificacioncarrera_seq'::regclass) NOT NULL,
	"Notificacion_Id" int8 NOT NULL,
	"Carrera_Id" varchar(20) NOT NULL,
	CONSTRAINT notificaciones_carreras_pkey PRIMARY KEY ("id_Notificacion_Carrera"),
	CONSTRAINT notificaciones_carreras_carrera_id FOREIGN KEY ("Carrera_Id") REFERENCES public.carreras(clavecarrera),
	CONSTRAINT notificaciones_carreras_notificacion_id FOREIGN KEY ("Notificacion_Id") REFERENCES public.notificaciones("id_Notificaciones")
);


-- public.practias_asignatura definition

-- Drop table

-- DROP TABLE public.practias_asignatura;

CREATE TABLE public.practias_asignatura (
	id_practicas serial4 NOT NULL,
	"ClaveAsignatura" varchar(20) NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	CONSTRAINT practias_asignatura_pkey PRIMARY KEY (id_practicas),
	CONSTRAINT "practias_asignatura_ClaveAsignatura_fkey" FOREIGN KEY ("ClaveAsignatura") REFERENCES public.asignatura("ClaveAsignatura") ON DELETE CASCADE
);


-- public.practica definition

-- Drop table

-- DROP TABLE public.practica;

CREATE TABLE public.practica (
	"id_Practica" int8 DEFAULT nextval('practica_idpractica_seq'::regclass) NOT NULL,
	"Clave_Asignatura" varchar(50) NOT NULL,
	"Descripcion_Practica" varchar(255) NOT NULL,
	CONSTRAINT practica_pkey PRIMARY KEY ("id_Practica"),
	CONSTRAINT practica_clave_asignatura FOREIGN KEY ("Clave_Asignatura") REFERENCES public.asignatura("ClaveAsignatura")
);


-- public.presentacion definition

-- Drop table

-- DROP TABLE public.presentacion;

CREATE TABLE public.presentacion (
	"id_Presentacion" serial4 NOT NULL,
	"Clave_Asignatura" varchar(20) NOT NULL,
	CONSTRAINT presentacion_clave_unica UNIQUE ("Clave_Asignatura"),
	CONSTRAINT presentacion_pkey PRIMARY KEY ("id_Presentacion"),
	CONSTRAINT presentacion_clave_asignatura_fk FOREIGN KEY ("Clave_Asignatura") REFERENCES public.asignatura("ClaveAsignatura")
);


-- public.presentacion_caracterizacion definition

-- Drop table

-- DROP TABLE public.presentacion_caracterizacion;

CREATE TABLE public.presentacion_caracterizacion (
	"id_Caracterizacion" serial4 NOT NULL,
	"id_Presentacion" int8 NOT NULL,
	"Orden" int2 NOT NULL,
	"Punto" text NOT NULL,
	CONSTRAINT presentacion_caracterizacion_pkey PRIMARY KEY ("id_Caracterizacion"),
	CONSTRAINT presentacion_caracterizacion_fk FOREIGN KEY ("id_Presentacion") REFERENCES public.presentacion("id_Presentacion") ON DELETE CASCADE
);


-- public.presentacion_intencion definition

-- Drop table

-- DROP TABLE public.presentacion_intencion;

CREATE TABLE public.presentacion_intencion (
	"id_Intencion" serial4 NOT NULL,
	"id_Presentacion" int8 NOT NULL,
	"Orden" int2 NOT NULL,
	"Tema" varchar(100) NOT NULL,
	"Descripcion" text NOT NULL,
	CONSTRAINT presentacion_intencion_pkey PRIMARY KEY ("id_Intencion"),
	CONSTRAINT presentacion_intencion_fk FOREIGN KEY ("id_Presentacion") REFERENCES public.presentacion("id_Presentacion") ON DELETE CASCADE
);


-- public.proyecto_asignatura definition

-- Drop table

-- DROP TABLE public.proyecto_asignatura;

CREATE TABLE public.proyecto_asignatura (
	id_proyecto serial4 NOT NULL,
	"ClaveAsignatura" varchar(20) NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	CONSTRAINT proyecto_asignatura_pkey PRIMARY KEY (id_proyecto),
	CONSTRAINT "proyecto_asignatura_ClaveAsignatura_fkey" FOREIGN KEY ("ClaveAsignatura") REFERENCES public.asignatura("ClaveAsignatura") ON DELETE CASCADE
);


-- public.tema definition

-- Drop table

-- DROP TABLE public.tema;

CREATE TABLE public.tema (
	"id_Tema" int8 DEFAULT nextval('tema_idtema_seq'::regclass) NOT NULL,
	"Clave_Asignatura" varchar(50) NOT NULL,
	"Numero" int4 NOT NULL,
	"Nombre_Tema" varchar(255) NOT NULL,
	CONSTRAINT tema_pkey PRIMARY KEY ("id_Tema"),
	CONSTRAINT tema_clave_asignatura FOREIGN KEY ("Clave_Asignatura") REFERENCES public.asignatura("ClaveAsignatura")
);


-- public.usuarios definition

-- Drop table

-- DROP TABLE public.usuarios;

CREATE TABLE public.usuarios (
	idusuario int8 DEFAULT nextval('usuarios_id_usuario_seq'::regclass) NOT NULL,
	correo varchar(100) NOT NULL,
	"password" varchar(255) NOT NULL,
	"token" varchar(80) DEFAULT NULL::character varying NULL,
	idrol int8 NOT NULL,
	CONSTRAINT usuarios_idrol_check CHECK ((idrol > 0)),
	CONSTRAINT usuarios_idusuario_check CHECK ((idusuario > 0)),
	CONSTRAINT usuarios_pkey PRIMARY KEY (idusuario),
	CONSTRAINT usuarios_idrol_foreign FOREIGN KEY (idrol) REFERENCES public.roles(idrol) ON DELETE CASCADE
);
CREATE INDEX usuarios_correo_unique ON public.usuarios USING btree (correo);
CREATE INDEX usuarios_idrol_foreign ON public.usuarios USING btree (idrol);


-- public.actividad_aprendizaje definition

-- Drop table

-- DROP TABLE public.actividad_aprendizaje;

CREATE TABLE public.actividad_aprendizaje (
	"id_Act_Aprendizaje" int8 DEFAULT nextval('actividad_aprendizaje_tema_idactaprendizaje_seq'::regclass) NOT NULL,
	"Tema_id" int8 NOT NULL,
	"Descripcion_Act_Aprendizaje" varchar(255) NOT NULL,
	CONSTRAINT actividad_aprendizaje_pkey PRIMARY KEY ("id_Act_Aprendizaje"),
	CONSTRAINT actividad_aprendizaje_id_tema FOREIGN KEY ("Tema_id") REFERENCES public.tema("id_Tema")
);


-- public.actividad_aprendizaje_tema definition

-- Drop table

-- DROP TABLE public.actividad_aprendizaje_tema;

CREATE TABLE public.actividad_aprendizaje_tema (
	id_actividad serial4 NOT NULL,
	"id_Tema" int8 NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	CONSTRAINT actividad_aprendizaje_tema_pkey PRIMARY KEY (id_actividad),
	CONSTRAINT actividad_aprendizaje_tema_id_tema_fkey FOREIGN KEY ("id_Tema") REFERENCES public.tema("id_Tema") ON DELETE CASCADE
);


-- public.alumnos definition

-- Drop table

-- DROP TABLE public.alumnos;

CREATE TABLE public.alumnos (
	numerocontrol int8 DEFAULT nextval('alumnos_numero_control_seq'::regclass) NOT NULL,
	nombre varchar(255) NOT NULL,
	apellidopaterno varchar(255) NOT NULL,
	apellidomaterno varchar(255) NOT NULL,
	idusuario int8 NOT NULL,
	clavecarrera varchar(20) NOT NULL,
	CONSTRAINT alumnos_idusuario_check CHECK ((idusuario > 0)),
	CONSTRAINT alumnos_numerocontrol_check CHECK ((numerocontrol > 0)),
	CONSTRAINT alumnos_pkey PRIMARY KEY (numerocontrol),
	CONSTRAINT alumnos_idusuario_foreign FOREIGN KEY (idusuario) REFERENCES public.usuarios(idusuario) ON DELETE CASCADE
);
CREATE INDEX alumnos_idusuario_foreign ON public.alumnos USING btree (idusuario);


-- public.bitacora_alumnos definition

-- Drop table

-- DROP TABLE public.bitacora_alumnos;

CREATE TABLE public.bitacora_alumnos (
	id_bitacora_alumno int8 DEFAULT nextval('bitacora_alumnos_id_seq'::regclass) NOT NULL,
	hora_entrada timestamp DEFAULT now() NOT NULL,
	hora_salida timestamp NULL,
	tiempo_uso interval NULL,
	numerocontrol int8 NOT NULL,
	numeroinventario int8 NOT NULL,
	CONSTRAINT bitacora_alumnos_id_bitacora_alumno_check CHECK ((id_bitacora_alumno > 0)),
	CONSTRAINT bitacora_alumnos_numerocontrol_check CHECK ((numerocontrol > 0)),
	CONSTRAINT bitacora_alumnos_numeroinventario_check CHECK ((numeroinventario > 0)),
	CONSTRAINT bitacora_alumnos_pkey PRIMARY KEY (id_bitacora_alumno),
	CONSTRAINT bitacora_alumnos_numerocontrol_foreign FOREIGN KEY (numerocontrol) REFERENCES public.alumnos(numerocontrol) ON DELETE CASCADE,
	CONSTRAINT bitacora_alumnos_numeroinventario_foreign FOREIGN KEY (numeroinventario) REFERENCES public.computadora(numeroinventario) ON DELETE CASCADE
);
CREATE INDEX bitacora_alumnos_numerocontrol_foreign ON public.bitacora_alumnos USING btree (numerocontrol);
CREATE INDEX bitacora_alumnos_numeroinventario_foreign ON public.bitacora_alumnos USING btree (numeroinventario);


-- public.carga_academica_general definition

-- Drop table

-- DROP TABLE public.carga_academica_general;

CREATE TABLE public.carga_academica_general (
	idcargageneral int8 DEFAULT nextval('carga_academica_general_id_carga_general_seq'::regclass) NOT NULL,
	numerocontrol int8 NOT NULL,
	CONSTRAINT carga_academica_general_idcargageneral_check CHECK ((idcargageneral > 0)),
	CONSTRAINT carga_academica_general_numerocontrol_check CHECK ((numerocontrol > 0)),
	CONSTRAINT carga_academica_general_pkey PRIMARY KEY (idcargageneral),
	CONSTRAINT carga_academica_general_numerocontrol_foreign FOREIGN KEY (numerocontrol) REFERENCES public.alumnos(numerocontrol) ON DELETE CASCADE
);
CREATE INDEX carga_academica_general_numerocontrol_foreign ON public.carga_academica_general USING btree (numerocontrol);


-- public.competencia_especifica_tema definition

-- Drop table

-- DROP TABLE public.competencia_especifica_tema;

CREATE TABLE public.competencia_especifica_tema (
	id_competencia_especifica serial4 NOT NULL,
	"id_Tema" int8 NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	CONSTRAINT competencia_especifica_tema_pkey PRIMARY KEY (id_competencia_especifica),
	CONSTRAINT competencia_especifica_tema_id_tema_fkey FOREIGN KEY ("id_Tema") REFERENCES public.tema("id_Tema") ON DELETE CASCADE
);


-- public.competencia_generica_tema definition

-- Drop table

-- DROP TABLE public.competencia_generica_tema;

CREATE TABLE public.competencia_generica_tema (
	id_competencia_generica serial4 NOT NULL,
	"id_Tema" int8 NOT NULL,
	descripcion text NOT NULL,
	nivel varchar(50) NULL,
	orden int4 DEFAULT 0 NULL,
	CONSTRAINT competencia_generica_tema_pkey PRIMARY KEY (id_competencia_generica),
	CONSTRAINT competencia_generica_tema_id_tema_fkey FOREIGN KEY ("id_Tema") REFERENCES public.tema("id_Tema") ON DELETE CASCADE
);


-- public.competencia_tema definition

-- Drop table

-- DROP TABLE public.competencia_tema;

CREATE TABLE public.competencia_tema (
	"id_Comp_Tema" int8 DEFAULT nextval('competencia_tema_idcomptema_seq'::regclass) NOT NULL,
	"Tema_id" int8 NOT NULL,
	"Descripcion_Comp_Tema" varchar(255) NOT NULL,
	"Tipo_Competencia" public."_tipo_comp" NOT NULL,
	CONSTRAINT competencia_tema_pkey PRIMARY KEY ("id_Comp_Tema"),
	CONSTRAINT competencia_tema_id_tema FOREIGN KEY ("Tema_id") REFERENCES public.tema("id_Tema")
);


-- public.maestros definition

-- Drop table

-- DROP TABLE public.maestros;

CREATE TABLE public.maestros (
	tarjeta bigserial NOT NULL,
	nombre varchar(255) NOT NULL,
	apellidopaterno varchar(255) NOT NULL,
	apellidomaterno varchar(255) NOT NULL,
	idusuario int8 NOT NULL,
	rfc varchar(13) NOT NULL,
	escolaridad_licenciatura varchar(50) NOT NULL,
	estado_licenciatura varchar(1) NOT NULL,
	escolaridad_especializacion varchar(50) NOT NULL,
	estado_especializacion varchar(1) NOT NULL,
	escolaridad_maestria varchar(50) NOT NULL,
	estado_maestria varchar(1) NOT NULL,
	escolaridad_doctorado varchar(50) NOT NULL,
	estado_doctorado varchar(1) NOT NULL,
	id_departamento int2 NOT NULL,
	CONSTRAINT maestros_idusuario_check CHECK ((idusuario > 0)),
	CONSTRAINT maestros_pkey PRIMARY KEY (tarjeta),
	CONSTRAINT maestros_tarjeta_check CHECK ((tarjeta > 0)),
	CONSTRAINT fk_maestros_departamento FOREIGN KEY (id_departamento) REFERENCES public.departamentos(id_departamento),
	CONSTRAINT maestros_idusuario_foreign FOREIGN KEY (idusuario) REFERENCES public.usuarios(idusuario) ON DELETE CASCADE
);
CREATE INDEX maestros_idusuario_foreign ON public.maestros USING btree (idusuario);


-- public.notificaciones_usuario definition

-- Drop table

-- DROP TABLE public.notificaciones_usuario;

CREATE TABLE public.notificaciones_usuario (
	"id_Notificacion_Usuario" int8 DEFAULT nextval('notificaciones_usuario_idnotificacionusuario_seq'::regclass) NOT NULL,
	"Notificacion_Id" int8 NOT NULL,
	"Usuario_Id" int8 NOT NULL,
	CONSTRAINT notificaciones_usuario_pkey PRIMARY KEY ("id_Notificacion_Usuario"),
	CONSTRAINT notificaciones_usuario_notificacion_id FOREIGN KEY ("Notificacion_Id") REFERENCES public.notificaciones("id_Notificaciones"),
	CONSTRAINT notificaciones_usuario_usuario_id FOREIGN KEY ("Usuario_Id") REFERENCES public.usuarios(idusuario)
);


-- public.reportefinal definition

-- Drop table

-- DROP TABLE public.reportefinal;

CREATE TABLE public.reportefinal (
	id_reportefinal serial4 NOT NULL,
	tarjeta_profesor int8 NOT NULL,
	id_periodo_escolar int8 NOT NULL,
	id_departamento int2 NOT NULL,
	estado varchar(20) DEFAULT 'borrador'::character varying NOT NULL,
	CONSTRAINT reportefinal_pkey PRIMARY KEY (id_reportefinal),
	CONSTRAINT fk_reportefinal_departamento FOREIGN KEY (id_departamento) REFERENCES public.departamentos(id_departamento),
	CONSTRAINT fk_reportefinal_periodo FOREIGN KEY (id_periodo_escolar) REFERENCES public.periodo_escolar(id_periodo_escolar) ON DELETE RESTRICT,
	CONSTRAINT fk_reportefinal_profesor FOREIGN KEY (tarjeta_profesor) REFERENCES public.maestros(tarjeta)
);


-- public.reportefinal_asignatura definition

-- Drop table

-- DROP TABLE public.reportefinal_asignatura;

CREATE TABLE public.reportefinal_asignatura (
	id_reportefinal_asignatura serial4 NOT NULL,
	id_reportefinal int4 NOT NULL,
	clave_asignatura varchar(20) NOT NULL,
	clave_carrera varchar(20) NOT NULL,
	a int4 DEFAULT 0 NOT NULL,
	b int4 DEFAULT 0 NOT NULL,
	c numeric(5, 2) DEFAULT 0 NOT NULL,
	d int4 DEFAULT 0 NOT NULL,
	e numeric(5, 2) DEFAULT 0 NOT NULL,
	f int4 DEFAULT 0 NOT NULL,
	g numeric(5, 2) DEFAULT 0 NOT NULL,
	h numeric(5, 2) DEFAULT 0 NOT NULL,
	fecha_registro timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	fecha_actualizacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT reportefinal_asignatura_pkey PRIMARY KEY (id_reportefinal_asignatura),
	CONSTRAINT uk_reportefinal_asignatura UNIQUE (id_reportefinal, clave_asignatura, clave_carrera),
	CONSTRAINT fk_reportefinal_asignatura_asignatura FOREIGN KEY (clave_asignatura) REFERENCES public.asignatura("ClaveAsignatura"),
	CONSTRAINT fk_reportefinal_asignatura_carrera FOREIGN KEY (clave_carrera) REFERENCES public.carreras(clavecarrera),
	CONSTRAINT fk_reportefinal_asignatura_reporte FOREIGN KEY (id_reportefinal) REFERENCES public.reportefinal(id_reportefinal) ON DELETE CASCADE
);


-- public.reservacionalumnos definition

-- Drop table

-- DROP TABLE public.reservacionalumnos;

CREATE TABLE public.reservacionalumnos (
	idreservacionalumno int8 DEFAULT nextval('reservacionalumnos_id_reservacion_alumno_seq'::regclass) NOT NULL,
	numerocontrol int8 NOT NULL,
	numeroinventario int8 NOT NULL,
	fechareservacion date NOT NULL,
	horainicio time NOT NULL,
	horafin time NOT NULL,
	CONSTRAINT reservacionalumnos_idreservacionalumno_check CHECK ((idreservacionalumno > 0)),
	CONSTRAINT reservacionalumnos_numerocontrol_check CHECK ((numerocontrol > 0)),
	CONSTRAINT reservacionalumnos_numeroinventario_check CHECK ((numeroinventario > 0)),
	CONSTRAINT reservacionalumnos_pkey PRIMARY KEY (idreservacionalumno),
	CONSTRAINT reservacionalumnos_numerocontrol_foreign FOREIGN KEY (numerocontrol) REFERENCES public.alumnos(numerocontrol) ON DELETE CASCADE,
	CONSTRAINT reservacionalumnos_numeroinventario_foreign FOREIGN KEY (numeroinventario) REFERENCES public.computadora(numeroinventario) ON DELETE CASCADE
);
CREATE INDEX reservacionalumnos_numerocontrol_foreign ON public.reservacionalumnos USING btree (numerocontrol);
CREATE INDEX reservacionalumnos_numeroinventario_foreign ON public.reservacionalumnos USING btree (numeroinventario);


-- public.reservacionmaestros definition

-- Drop table

-- DROP TABLE public.reservacionmaestros;

CREATE TABLE public.reservacionmaestros (
	idreservacionmaestro int8 DEFAULT nextval('reservacionmaestros_id_reservacion_maestro_seq'::regclass) NOT NULL,
	tarjeta int8 NOT NULL,
	claveaula varchar(20) NOT NULL,
	fechareservacion date NOT NULL,
	horainicio time NOT NULL,
	horafin time NOT NULL,
	CONSTRAINT reservacionmaestros_idreservacionmaestro_check CHECK ((idreservacionmaestro > 0)),
	CONSTRAINT reservacionmaestros_pkey PRIMARY KEY (idreservacionmaestro),
	CONSTRAINT reservacionmaestros_tarjeta_check CHECK ((tarjeta > 0)),
	CONSTRAINT reservacionmaestros_claveaula_foreign FOREIGN KEY (claveaula) REFERENCES public.aulas(claveaula) ON DELETE CASCADE,
	CONSTRAINT reservacionmaestros_tarjeta_foreign FOREIGN KEY (tarjeta) REFERENCES public.maestros(tarjeta) ON DELETE CASCADE
);
CREATE INDEX reservacionmaestros_claveaula_foreign ON public.reservacionmaestros USING btree (claveaula);
CREATE INDEX reservacionmaestros_tarjeta_foreign ON public.reservacionmaestros USING btree (tarjeta);


-- public.subtema definition

-- Drop table

-- DROP TABLE public.subtema;

CREATE TABLE public.subtema (
	"id_Subtema" serial4 NOT NULL,
	"Tema_id" int8 NOT NULL,
	"Subtema_Padre_id" int8 NULL,
	"Nombre_Subtema" varchar(255) NOT NULL,
	"Orden" int2 NOT NULL,
	"Nivel" int2 DEFAULT 1 NOT NULL,
	CONSTRAINT subtema_orden_unico UNIQUE ("Tema_id", "Subtema_Padre_id", "Orden"),
	CONSTRAINT subtema_pkey PRIMARY KEY ("id_Subtema"),
	CONSTRAINT subtema_id_tema_fk FOREIGN KEY ("Tema_id") REFERENCES public.tema("id_Tema"),
	CONSTRAINT subtema_padre_fk FOREIGN KEY ("Subtema_Padre_id") REFERENCES public.subtema("id_Subtema") ON DELETE CASCADE
);


-- public.actividad_competencia definition

-- Drop table

-- DROP TABLE public.actividad_competencia;

CREATE TABLE public.actividad_competencia (
	"id_Act_Competencia" int8 DEFAULT nextval('actividad_competencia_tema_idactcompetencia_seq'::regclass) NOT NULL,
	"Act_Aprendizaje_id" int8 NOT NULL,
	"Comp_Tema_id" int8 NOT NULL,
	CONSTRAINT actividad_competencia_pkey PRIMARY KEY ("id_Act_Competencia"),
	CONSTRAINT actividad_aprendizaje_id_act_aprendizaje FOREIGN KEY ("Act_Aprendizaje_id") REFERENCES public.actividad_aprendizaje("id_Act_Aprendizaje"),
	CONSTRAINT actividad_competencia_competencia_tema_id_comp_tema FOREIGN KEY ("Comp_Tema_id") REFERENCES public.competencia_tema("id_Comp_Tema")
);


-- public.bitacora_maestros definition

-- Drop table

-- DROP TABLE public.bitacora_maestros;

CREATE TABLE public.bitacora_maestros (
	id_bitacora_maestro int8 DEFAULT nextval('bitacora_maestros_id_seq'::regclass) NOT NULL,
	fechahora timestamp NOT NULL,
	tarjeta int8 NOT NULL,
	claveaula varchar(255) NOT NULL,
	CONSTRAINT bitacora_maestros_id_bitacora_maestro_check CHECK ((id_bitacora_maestro > 0)),
	CONSTRAINT bitacora_maestros_pkey PRIMARY KEY (id_bitacora_maestro),
	CONSTRAINT bitacora_maestros_tarjeta_check CHECK ((tarjeta > 0)),
	CONSTRAINT bitacora_maestros_claveaula_foreign FOREIGN KEY (claveaula) REFERENCES public.aulas(claveaula) ON DELETE CASCADE,
	CONSTRAINT bitacora_maestros_tarjeta_foreign FOREIGN KEY (tarjeta) REFERENCES public.maestros(tarjeta) ON DELETE CASCADE
);
CREATE INDEX bitacora_maestros_claveaula_foreign ON public.bitacora_maestros USING btree (claveaula);
CREATE INDEX bitacora_maestros_tarjeta_foreign ON public.bitacora_maestros USING btree (tarjeta);


-- public.comision_maestro definition

-- Drop table

-- DROP TABLE public.comision_maestro;

CREATE TABLE public.comision_maestro (
	id serial4 NOT NULL,
	id_comision int4 NOT NULL,
	tarjeta_maestro int8 NOT NULL,
	CONSTRAINT comision_maestro_pkey PRIMARY KEY (id),
	CONSTRAINT fk_comision FOREIGN KEY (id_comision) REFERENCES public.comision(id_comision) ON DELETE CASCADE,
	CONSTRAINT fk_maestro FOREIGN KEY (tarjeta_maestro) REFERENCES public.maestros(tarjeta) ON DELETE CASCADE
);


-- public.datos_estaticos_reportefinal definition

-- Drop table

-- DROP TABLE public.datos_estaticos_reportefinal;

CREATE TABLE public.datos_estaticos_reportefinal (
	id_datos_estaticos serial4 NOT NULL,
	id_reportefinal int4 NOT NULL,
	numero_grupos_atendidos int4 DEFAULT 0 NOT NULL,
	numero_estudiantes int4 DEFAULT 0 NOT NULL,
	numero_asignaturas_diferentes int4 DEFAULT 0 NOT NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	fecha_actualizacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT datos_estaticos_reportefinal_pkey PRIMARY KEY (id_datos_estaticos),
	CONSTRAINT fk_datos_estaticos_reportefinal FOREIGN KEY (id_reportefinal) REFERENCES public.reportefinal(id_reportefinal) ON DELETE CASCADE
);


-- public.horarioasignatura_maestro definition

-- Drop table

-- DROP TABLE public.horarioasignatura_maestro;

CREATE TABLE public.horarioasignatura_maestro (
	clavehorario int8 DEFAULT nextval('horarioasignatura_maestro_clave_horario_seq'::regclass) NOT NULL,
	tarjeta int8 NOT NULL,
	claveaula varchar(20) NOT NULL,
	clavegrupo varchar(20) NOT NULL,
	claveasignatura varchar(20) NOT NULL,
	lunes_hi time NULL,
	lunes_hf time NULL,
	idperiodoescolar int8 NOT NULL,
	martes_hi time NULL,
	martes_hf time NULL,
	miercoles_hi time NULL,
	miercoles_hf time NULL,
	jueves_hi time NULL,
	jueves_hf time NULL,
	viernes_hi time NULL,
	viernes_hf time NULL,
	sabado_hi time NULL,
	sabado_hf time NULL,
	CONSTRAINT horarioasignatura_maestro_clavehorario_check CHECK ((clavehorario > 0)),
	CONSTRAINT horarioasignatura_maestro_idperiodoescolar_check CHECK ((idperiodoescolar > 0)),
	CONSTRAINT horarioasignatura_maestro_pkey PRIMARY KEY (clavehorario),
	CONSTRAINT horarioasignatura_maestro_tarjeta_check CHECK ((tarjeta > 0)),
	CONSTRAINT horarioasignatura_claveasignatura_foreign FOREIGN KEY (claveasignatura) REFERENCES public.asignatura("ClaveAsignatura") ON DELETE CASCADE,
	CONSTRAINT horarioasignatura_claveaula_foreign FOREIGN KEY (claveaula) REFERENCES public.aulas(claveaula) ON DELETE CASCADE,
	CONSTRAINT horarioasignatura_clavegrupo_foreign FOREIGN KEY (clavegrupo) REFERENCES public.grupos(clavegrupo) ON DELETE CASCADE,
	CONSTRAINT horarioasignatura_idperiodoescolar_foreign FOREIGN KEY (idperiodoescolar) REFERENCES public.periodo_escolar(id_periodo_escolar) ON DELETE CASCADE,
	CONSTRAINT horarioasignatura_tarjeta_foreign FOREIGN KEY (tarjeta) REFERENCES public.maestros(tarjeta) ON DELETE CASCADE
);
CREATE INDEX horarioasignatura_claveasignatura_foreign ON public.horarioasignatura_maestro USING btree (claveasignatura);
CREATE INDEX horarioasignatura_claveaula_foreign ON public.horarioasignatura_maestro USING btree (claveaula);
CREATE INDEX horarioasignatura_clavegrupo_foreign ON public.horarioasignatura_maestro USING btree (clavegrupo);
CREATE INDEX horarioasignatura_idperiodoescolar_foreign ON public.horarioasignatura_maestro USING btree (idperiodoescolar);
CREATE INDEX horarioasignatura_tarjeta_foreign ON public.horarioasignatura_maestro USING btree (tarjeta);


-- public.horarios_maestros definition

-- Drop table

-- DROP TABLE public.horarios_maestros;

CREATE TABLE public.horarios_maestros (
	id_horario_maestro int8 DEFAULT nextval('horarios_maestros_idhorariomaestro_seq'::regclass) NOT NULL,
	maestro_id int4 NOT NULL,
	dia_semana varchar(10) NOT NULL,
	hora_inicio time NOT NULL,
	hora_fin time NOT NULL,
	CONSTRAINT chk_horas CHECK ((hora_fin > hora_inicio)),
	CONSTRAINT horarios_maestros_dia_semana_check CHECK (((dia_semana)::text = ANY (ARRAY[('Lunes'::character varying)::text, ('Martes'::character varying)::text, ('Miércoles'::character varying)::text, ('Jueves'::character varying)::text, ('Viernes'::character varying)::text, ('Sábado'::character varying)::text, ('Domingo'::character varying)::text]))),
	CONSTRAINT horarios_maestros_pkey PRIMARY KEY (id_horario_maestro),
	CONSTRAINT horarios_maestros_maestro_id FOREIGN KEY (maestro_id) REFERENCES public.maestros(tarjeta)
);


-- public.instrumentacion definition

-- Drop table

-- DROP TABLE public.instrumentacion;

CREATE TABLE public.instrumentacion (
	id_instrumentacion serial4 NOT NULL,
	"ClaveAsignatura" varchar(50) NOT NULL,
	tarjeta_profesor int8 NOT NULL,
	clavecarrera varchar(20) NOT NULL,
	id_periodo_escolar int8 NOT NULL,
	nombre_jefe_academico varchar(60) NULL,
	fecha_creacion date DEFAULT CURRENT_DATE NOT NULL,
	fecha_ultima_actualizacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	id_departamento int2 NOT NULL,
	estado varchar(20) DEFAULT 'borrador'::character varying NOT NULL,
	CONSTRAINT instrumentacion_pkey PRIMARY KEY (id_instrumentacion),
	CONSTRAINT fk_instrumentacion_asignatura FOREIGN KEY ("ClaveAsignatura") REFERENCES public.asignatura("ClaveAsignatura"),
	CONSTRAINT fk_instrumentacion_carrera FOREIGN KEY (clavecarrera) REFERENCES public.carreras(clavecarrera),
	CONSTRAINT fk_instrumentacion_departamento FOREIGN KEY (id_departamento) REFERENCES public.departamentos(id_departamento),
	CONSTRAINT fk_instrumentacion_periodo FOREIGN KEY (id_periodo_escolar) REFERENCES public.periodo_escolar(id_periodo_escolar) ON DELETE RESTRICT,
	CONSTRAINT fk_instrumentacion_profesor FOREIGN KEY (tarjeta_profesor) REFERENCES public.maestros(tarjeta)
);


-- public.liberacion_academica definition

-- Drop table

-- DROP TABLE public.liberacion_academica;

CREATE TABLE public.liberacion_academica (
	id_liberacion serial4 NOT NULL,
	id_departamento int4 NOT NULL,
	tarjeta_maestro bigserial NOT NULL,
	id_periodo_escolar int4 NOT NULL,
	fecha_liberacion date NOT NULL,
	otorga_liberacion bool DEFAULT false NOT NULL,
	CONSTRAINT liberacion_academica_pkey PRIMARY KEY (id_liberacion),
	CONSTRAINT uk_liberacion_academica_maestro_periodo UNIQUE (tarjeta_maestro, id_periodo_escolar),
	CONSTRAINT fk_liberacion_academica_departamento FOREIGN KEY (id_departamento) REFERENCES public.departamentos(id_departamento),
	CONSTRAINT fk_liberacion_academica_maestro FOREIGN KEY (tarjeta_maestro) REFERENCES public.maestros(tarjeta),
	CONSTRAINT fk_liberacion_academica_periodo FOREIGN KEY (id_periodo_escolar) REFERENCES public.periodo_escolar(id_periodo_escolar)
);
CREATE INDEX idx_liberacion_academica_departamento ON public.liberacion_academica USING btree (id_departamento);
CREATE INDEX idx_liberacion_academica_fecha ON public.liberacion_academica USING btree (fecha_liberacion);
CREATE INDEX idx_liberacion_academica_maestro ON public.liberacion_academica USING btree (tarjeta_maestro);
CREATE INDEX idx_liberacion_academica_periodo ON public.liberacion_academica USING btree (id_periodo_escolar);


-- public.liberacion_academica_detalles definition

-- Drop table

-- DROP TABLE public.liberacion_academica_detalles;

CREATE TABLE public.liberacion_academica_detalles (
	id_detalle serial4 NOT NULL,
	id_liberacion int4 NOT NULL,
	numero_actividad int4 NOT NULL,
	descripcion_actividad text NOT NULL,
	si bool DEFAULT false NOT NULL,
	"no" bool DEFAULT false NOT NULL,
	na bool DEFAULT false NOT NULL,
	CONSTRAINT chk_estado_unico_academica CHECK (((((si)::integer + (no)::integer) + (na)::integer) <= 1)),
	CONSTRAINT liberacion_academica_detalles_pkey PRIMARY KEY (id_detalle),
	CONSTRAINT uk_detalle_actividad_academica UNIQUE (id_liberacion, numero_actividad),
	CONSTRAINT fk_detalle_liberacion_academica FOREIGN KEY (id_liberacion) REFERENCES public.liberacion_academica(id_liberacion) ON DELETE CASCADE
);
CREATE INDEX idx_detalle_liberacion_academica ON public.liberacion_academica_detalles USING btree (id_liberacion);
CREATE INDEX idx_detalle_na_academica ON public.liberacion_academica_detalles USING btree (na) WHERE (na = true);
CREATE INDEX idx_detalle_no_academica ON public.liberacion_academica_detalles USING btree (no) WHERE (no = true);
CREATE INDEX idx_detalle_numero_actividad_academica ON public.liberacion_academica_detalles USING btree (numero_actividad);
CREATE INDEX idx_detalle_si_academica ON public.liberacion_academica_detalles USING btree (si) WHERE (si = true);


-- public.liberacion_docente definition

-- Drop table

-- DROP TABLE public.liberacion_docente;

CREATE TABLE public.liberacion_docente (
	id_liberacion serial4 NOT NULL,
	id_departamento int4 NOT NULL,
	tarjeta_maestro bigserial NOT NULL,
	id_periodo_escolar int4 NOT NULL,
	fecha_liberacion date NOT NULL,
	otorga_liberacion bool DEFAULT false NOT NULL,
	CONSTRAINT liberacion_docente_pkey PRIMARY KEY (id_liberacion),
	CONSTRAINT uk_liberacion_maestro_periodo UNIQUE (tarjeta_maestro, id_periodo_escolar),
	CONSTRAINT fk_liberacion_departamento FOREIGN KEY (id_departamento) REFERENCES public.departamentos(id_departamento),
	CONSTRAINT fk_liberacion_maestro FOREIGN KEY (tarjeta_maestro) REFERENCES public.maestros(tarjeta),
	CONSTRAINT fk_liberacion_periodo FOREIGN KEY (id_periodo_escolar) REFERENCES public.periodo_escolar(id_periodo_escolar)
);
CREATE INDEX idx_liberacion_departamento ON public.liberacion_docente USING btree (id_departamento);
CREATE INDEX idx_liberacion_fecha ON public.liberacion_docente USING btree (fecha_liberacion);
CREATE INDEX idx_liberacion_maestro ON public.liberacion_docente USING btree (tarjeta_maestro);
CREATE INDEX idx_liberacion_periodo ON public.liberacion_docente USING btree (id_periodo_escolar);


-- public.liberacion_docente_detalles definition

-- Drop table

-- DROP TABLE public.liberacion_docente_detalles;

CREATE TABLE public.liberacion_docente_detalles (
	id_detalle serial4 NOT NULL,
	id_liberacion int4 NOT NULL,
	numero_actividad int4 NOT NULL,
	descripcion_actividad text NOT NULL,
	si bool DEFAULT false NOT NULL,
	"no" bool DEFAULT false NOT NULL,
	na bool DEFAULT false NOT NULL,
	CONSTRAINT chk_estado_unico CHECK (((((si)::integer + (no)::integer) + (na)::integer) <= 1)),
	CONSTRAINT liberacion_docente_detalles_pkey PRIMARY KEY (id_detalle),
	CONSTRAINT uk_detalle_actividad UNIQUE (id_liberacion, numero_actividad),
	CONSTRAINT fk_detalle_liberacion FOREIGN KEY (id_liberacion) REFERENCES public.liberacion_docente(id_liberacion) ON DELETE CASCADE
);
CREATE INDEX idx_detalle_liberacion ON public.liberacion_docente_detalles USING btree (id_liberacion);
CREATE INDEX idx_detalle_na ON public.liberacion_docente_detalles USING btree (na) WHERE (na = true);
CREATE INDEX idx_detalle_no ON public.liberacion_docente_detalles USING btree (no) WHERE (no = true);
CREATE INDEX idx_detalle_numero_actividad ON public.liberacion_docente_detalles USING btree (numero_actividad);
CREATE INDEX idx_detalle_si ON public.liberacion_docente_detalles USING btree (si) WHERE (si = true);


-- public.apoyos_didacticos_instrumentacion definition

-- Drop table

-- DROP TABLE public.apoyos_didacticos_instrumentacion;

CREATE TABLE public.apoyos_didacticos_instrumentacion (
	id_apoyo_didactico serial4 NOT NULL,
	id_instrumentacion int4 NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT apoyos_didacticos_instrumentacion_pkey PRIMARY KEY (id_apoyo_didactico),
	CONSTRAINT fk_apoyos_didacticos_instrumentacion FOREIGN KEY (id_instrumentacion) REFERENCES public.instrumentacion(id_instrumentacion) ON DELETE CASCADE
);
CREATE INDEX idx_apoyos_didacticos_instrumentacion ON public.apoyos_didacticos_instrumentacion USING btree (id_instrumentacion);


-- public.avance definition

-- Drop table

-- DROP TABLE public.avance;

CREATE TABLE public.avance (
	id_avance serial4 NOT NULL,
	clave_asignatura varchar(50) NOT NULL,
	tarjeta_profesor int8 NOT NULL,
	id_periodo_escolar int8 NOT NULL,
	fecha_creacion date DEFAULT CURRENT_DATE NOT NULL,
	fecha_ultima_actualizacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	firma_profesor varchar(100) NULL,
	firma_jefe_carrera varchar(100) NULL,
	requiere_firma_jefe bool DEFAULT false NULL,
	estado varchar(20) DEFAULT 'borrador'::character varying NOT NULL,
	clave_horario int8 NULL,
	CONSTRAINT avance_pkey PRIMARY KEY (id_avance),
	CONSTRAINT fk_avance_asignatura FOREIGN KEY (clave_asignatura) REFERENCES public.asignatura("ClaveAsignatura"),
	CONSTRAINT fk_avance_horario FOREIGN KEY (clave_horario) REFERENCES public.horarioasignatura_maestro(clavehorario) ON DELETE CASCADE,
	CONSTRAINT fk_avance_periodo FOREIGN KEY (id_periodo_escolar) REFERENCES public.periodo_escolar(id_periodo_escolar) ON DELETE RESTRICT,
	CONSTRAINT fk_avance_profesor FOREIGN KEY (tarjeta_profesor) REFERENCES public.maestros(tarjeta)
);


-- public.avance_detalles definition

-- Drop table

-- DROP TABLE public.avance_detalles;

CREATE TABLE public.avance_detalles (
	id_avance_detalle serial4 NOT NULL,
	id_avance int4 NOT NULL,
	id_tema int8 NULL,
	porcentaje_aprobacion numeric(5, 2) DEFAULT 0 NULL,
	requiere_firma_docente bool DEFAULT false NULL,
	observaciones text NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	updated_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT avance_detalles_pkey PRIMARY KEY (id_avance_detalle),
	CONSTRAINT fk_avance_detalles_avance FOREIGN KEY (id_avance) REFERENCES public.avance(id_avance) ON DELETE CASCADE,
	CONSTRAINT fk_avance_detalles_tema FOREIGN KEY (id_tema) REFERENCES public.tema("id_Tema") ON DELETE SET NULL
);


-- public.avance_detalles_fechas definition

-- Drop table

-- DROP TABLE public.avance_detalles_fechas;

CREATE TABLE public.avance_detalles_fechas (
	id_avance_detalle_fecha serial4 NOT NULL,
	id_avance_detalle int4 NOT NULL,
	fecha_inicio date NOT NULL,
	fecha_fin date NOT NULL,
	fecha_inicio_real date NULL,
	fecha_fin_real date NULL,
	fecha_evaluacion date NULL,
	fecha_evaluacion_real date NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	updated_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT avance_detalles_fechas_pkey PRIMARY KEY (id_avance_detalle_fecha),
	CONSTRAINT chk_fechas_reales_validas CHECK ((((fecha_inicio_real IS NULL) OR (fecha_fin_real IS NULL) OR (fecha_inicio_real <= fecha_fin_real)) AND ((fecha_evaluacion_real IS NULL) OR (fecha_evaluacion_real >= fecha_inicio_real)))),
	CONSTRAINT chk_fechas_validas CHECK ((fecha_inicio <= fecha_fin)),
	CONSTRAINT fk_avance_detalles_fechas_detalle FOREIGN KEY (id_avance_detalle) REFERENCES public.avance_detalles(id_avance_detalle) ON DELETE CASCADE
);


-- public.avance_fechas definition

-- Drop table

-- DROP TABLE public.avance_fechas;

CREATE TABLE public.avance_fechas (
	id_avance_fecha bigserial NOT NULL,
	id_avance int4 NOT NULL,
	id_fecha_clave int4 NOT NULL,
	observaciones text NULL,
	fecha_real date NULL,
	CONSTRAINT avance_fechas_pkey PRIMARY KEY (id_avance_fecha),
	CONSTRAINT fk_avance_fecha_avance FOREIGN KEY (id_avance) REFERENCES public.avance(id_avance) ON DELETE CASCADE,
	CONSTRAINT fk_avance_fecha_clave FOREIGN KEY (id_fecha_clave) REFERENCES public.fechas_clave_periodo(id_fecha_clave) ON DELETE RESTRICT
);


-- public.calendarizacion_evaluacion_instrumentacion definition

-- Drop table

-- DROP TABLE public.calendarizacion_evaluacion_instrumentacion;

CREATE TABLE public.calendarizacion_evaluacion_instrumentacion (
	id_calendarizacion int4 DEFAULT nextval('calendarizacion_evaluacion_instrumentaci_id_calendarizacion_seq'::regclass) NOT NULL,
	id_instrumentacion int4 NOT NULL,
	semana int4 NOT NULL,
	tiempo_planeado varchar(100) NULL,
	tiempo_real varchar(100) NULL,
	seguimiento_departamental bool DEFAULT false NOT NULL,
	descripcion text NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT calendarizacion_evaluacion_instrumentacion_pkey PRIMARY KEY (id_calendarizacion),
	CONSTRAINT unq_semana_instrumentacion UNIQUE (id_instrumentacion, semana),
	CONSTRAINT fk_calendarizacion_instrumentacion FOREIGN KEY (id_instrumentacion) REFERENCES public.instrumentacion(id_instrumentacion) ON DELETE CASCADE
);
CREATE INDEX idx_calendarizacion_instrumentacion ON public.calendarizacion_evaluacion_instrumentacion USING btree (id_instrumentacion);
CREATE INDEX idx_calendarizacion_semana ON public.calendarizacion_evaluacion_instrumentacion USING btree (semana);


-- public.carga_academica_detalles definition

-- Drop table

-- DROP TABLE public.carga_academica_detalles;

CREATE TABLE public.carga_academica_detalles (
	idcargadetalle int8 DEFAULT nextval('carga_academica_detalles_id_carga_detalle_seq'::regclass) NOT NULL,
	clavehorario int8 NOT NULL,
	idcargageneral int8 NOT NULL,
	CONSTRAINT carga_academica_detalles_clavehorario_check CHECK ((clavehorario > 0)),
	CONSTRAINT carga_academica_detalles_idcargadetalle_check CHECK ((idcargadetalle > 0)),
	CONSTRAINT carga_academica_detalles_idcargageneral_check CHECK ((idcargageneral > 0)),
	CONSTRAINT carga_academica_detalles_pkey PRIMARY KEY (idcargadetalle),
	CONSTRAINT carga_academica_detalles_clavehorario_foreign FOREIGN KEY (clavehorario) REFERENCES public.horarioasignatura_maestro(clavehorario) ON DELETE CASCADE,
	CONSTRAINT carga_academica_detalles_idcargageneral_foreign FOREIGN KEY (idcargageneral) REFERENCES public.carga_academica_general(idcargageneral) ON DELETE CASCADE
);
CREATE INDEX carga_academica_detalles_clavehorario_foreign ON public.carga_academica_detalles USING btree (clavehorario);
CREATE INDEX carga_academica_detalles_idcargageneral_foreign ON public.carga_academica_detalles USING btree (idcargageneral);


-- public.competencias_instrumentacion definition

-- Drop table

-- DROP TABLE public.competencias_instrumentacion;

CREATE TABLE public.competencias_instrumentacion (
	id_competencia serial4 NOT NULL,
	id_instrumentacion int4 NOT NULL,
	id_tema int8 NOT NULL,
	horas_dedicadas int2 NOT NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT competencias_instrumentacion_pkey PRIMARY KEY (id_competencia),
	CONSTRAINT fk_comp_instrumentacion FOREIGN KEY (id_instrumentacion) REFERENCES public.instrumentacion(id_instrumentacion) ON DELETE CASCADE,
	CONSTRAINT fk_comp_tema FOREIGN KEY (id_tema) REFERENCES public.tema("id_Tema") ON DELETE CASCADE
);
CREATE INDEX idx_comp_instrumentacion ON public.competencias_instrumentacion USING btree (id_instrumentacion);
CREATE INDEX idx_comp_tema ON public.competencias_instrumentacion USING btree (id_tema);


-- public.evaluacion_competencias_instrumentacion definition

-- Drop table

-- DROP TABLE public.evaluacion_competencias_instrumentacion;

CREATE TABLE public.evaluacion_competencias_instrumentacion (
	id_evaluacion_competencia int4 DEFAULT nextval('evaluacion_competencias_instrumen_id_evaluacion_competencia_seq'::regclass) NOT NULL,
	id_competencia int4 NOT NULL,
	evidencia_aprendizaje text NOT NULL,
	porcentaje_valor int4 NULL,
	evaluacion_formativa text NOT NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT chk_porcentaje_valor CHECK (((porcentaje_valor IS NULL) OR ((porcentaje_valor >= 0) AND (porcentaje_valor <= 100)))),
	CONSTRAINT evaluacion_competencias_instrumentacion_pkey PRIMARY KEY (id_evaluacion_competencia),
	CONSTRAINT fk_evaluacion_comp_instrumentacion FOREIGN KEY (id_competencia) REFERENCES public.competencias_instrumentacion(id_competencia) ON DELETE CASCADE
);
CREATE INDEX idx_evaluacion_competencia_instrumentacion ON public.evaluacion_competencias_instrumentacion USING btree (id_competencia);


-- public.indicadores_alcance_evaluacion_instrumentacion definition

-- Drop table

-- DROP TABLE public.indicadores_alcance_evaluacion_instrumentacion;

CREATE TABLE public.indicadores_alcance_evaluacion_instrumentacion (
	id_indicador_alcance int4 DEFAULT nextval('indicadores_alcance_evaluacion_instrum_id_indicador_alcance_seq'::regclass) NOT NULL,
	id_evaluacion_competencia int4 NOT NULL,
	letra_indicador varchar(5) NOT NULL,
	porcentaje int4 NULL,
	descripcion text NULL,
	orden int4 DEFAULT 0 NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT chk_porcentaje CHECK (((porcentaje IS NULL) OR ((porcentaje >= 0) AND (porcentaje <= 100)))),
	CONSTRAINT indicadores_alcance_evaluacion_instrumentacion_pkey PRIMARY KEY (id_indicador_alcance),
	CONSTRAINT unq_letra_evaluacion_instrumentacion UNIQUE (id_evaluacion_competencia, letra_indicador),
	CONSTRAINT fk_indicadores_evaluacion_instrumentacion FOREIGN KEY (id_evaluacion_competencia) REFERENCES public.evaluacion_competencias_instrumentacion(id_evaluacion_competencia) ON DELETE CASCADE
);
CREATE INDEX idx_indicadores_evaluacion_instrumentacion ON public.indicadores_alcance_evaluacion_instrumentacion USING btree (id_evaluacion_competencia);


-- public.indicadores_alcance_instrumentacion definition

-- Drop table

-- DROP TABLE public.indicadores_alcance_instrumentacion;

CREATE TABLE public.indicadores_alcance_instrumentacion (
	id_indicador serial4 NOT NULL,
	id_competencia int4 NOT NULL,
	indicador_alcance text NOT NULL,
	valor_indicador int4 NOT NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT indicadores_alcance_instrumentacion_pkey PRIMARY KEY (id_indicador),
	CONSTRAINT fk_indicadores_comp FOREIGN KEY (id_competencia) REFERENCES public.competencias_instrumentacion(id_competencia) ON DELETE CASCADE
);
CREATE INDEX idx_indicadores_competencia ON public.indicadores_alcance_instrumentacion USING btree (id_competencia);


-- public.niveles_desempeno_instrumentacion definition

-- Drop table

-- DROP TABLE public.niveles_desempeno_instrumentacion;

CREATE TABLE public.niveles_desempeno_instrumentacion (
	id_nivel_desempeno serial4 NOT NULL,
	id_competencia int4 NOT NULL,
	desempeno_alcanzado bool DEFAULT false NOT NULL,
	nivel_desempeno varchar(100) NOT NULL,
	valoracion_inicial int4 NOT NULL,
	valoracion_final int4 NOT NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT chk_valoracion_rango CHECK ((valoracion_inicial <= valoracion_final)),
	CONSTRAINT niveles_desempeno_instrumentacion_pkey PRIMARY KEY (id_nivel_desempeno),
	CONSTRAINT fk_niveles_comp FOREIGN KEY (id_competencia) REFERENCES public.competencias_instrumentacion(id_competencia) ON DELETE CASCADE
);
CREATE INDEX idx_niveles_competencia ON public.niveles_desempeno_instrumentacion USING btree (id_competencia);


-- public.actividades_ensenanza_instrumentacion definition

-- Drop table

-- DROP TABLE public.actividades_ensenanza_instrumentacion;

CREATE TABLE public.actividades_ensenanza_instrumentacion (
	id_actividades_ensenanza_instrumentacion int4 DEFAULT nextval('actividades_ensenanza_instrum_id_actividades_ensenanza_inst_seq'::regclass) NOT NULL,
	id_competencia int4 NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT actividades_ensenanza_instrumentacion_pkey PRIMARY KEY (id_actividades_ensenanza_instrumentacion),
	CONSTRAINT fk_actividades_comp FOREIGN KEY (id_competencia) REFERENCES public.competencias_instrumentacion(id_competencia) ON DELETE CASCADE
);
CREATE INDEX idx_actividades_competencia ON public.actividades_ensenanza_instrumentacion USING btree (id_competencia);


-- public.calificaciones_unidades definition

-- Drop table

-- DROP TABLE public.calificaciones_unidades;

CREATE TABLE public.calificaciones_unidades (
	idregistro int8 DEFAULT nextval('calificaciones_id_seq'::regclass) NOT NULL,
	idcargadetalle int8 NOT NULL,
	unidad int2 NULL,
	tipoevaluacion varchar(30) NULL,
	calificacion numeric(5, 2) NULL,
	ponderacion numeric(5, 2) NULL,
	fecharegistro timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	tipoacreditacion varchar(20) NULL,
	acreditado bool DEFAULT false NULL,
	CONSTRAINT calificaciones_unidades_calificacion_check CHECK (((calificacion >= (0)::numeric) AND (calificacion <= (100)::numeric))),
	CONSTRAINT calificaciones_unidades_idcargadetalle_check CHECK ((idcargadetalle > 0)),
	CONSTRAINT calificaciones_unidades_idregistro_check CHECK ((idregistro > 0)),
	CONSTRAINT calificaciones_unidades_pkey PRIMARY KEY (idregistro),
	CONSTRAINT calificaciones_unidades_ponderacion_check CHECK (((ponderacion > (0)::numeric) AND (ponderacion <= (100)::numeric))),
	CONSTRAINT calificaciones_unidades_tipoacreditacion_check CHECK (((tipoacreditacion)::text = ANY (ARRAY[('Ordinario'::character varying)::text, ('Complementario'::character varying)::text, ('Extraordinario'::character varying)::text, (NULL::character varying)::text]))),
	CONSTRAINT calificaciones_unidades_unidad_check CHECK ((unidad > 0)),
	CONSTRAINT uk_evaluacion_unica UNIQUE (idcargadetalle, unidad, tipoevaluacion, tipoacreditacion),
	CONSTRAINT fk_calificacion_carga FOREIGN KEY (idcargadetalle) REFERENCES public.carga_academica_detalles(idcargadetalle) ON DELETE CASCADE
);


-- public.competencia_generico_instrumentacion definition

-- Drop table

-- DROP TABLE public.competencia_generico_instrumentacion;

CREATE TABLE public.competencia_generico_instrumentacion (
	id_competencia_generico_instrumentacion int4 DEFAULT nextval('competencia_generico_instrume_id_competencia_generico_instr_seq'::regclass) NOT NULL,
	id_competencia int4 NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT competencia_generico_instrumentacion_pkey PRIMARY KEY (id_competencia_generico_instrumentacion),
	CONSTRAINT fk_comp_generico_comp FOREIGN KEY (id_competencia) REFERENCES public.competencias_instrumentacion(id_competencia) ON DELETE CASCADE
);
CREATE INDEX idx_generico_competencia ON public.competencia_generico_instrumentacion USING btree (id_competencia);


-- public.indicadores_alcance definition

-- Drop table

-- DROP TABLE public.indicadores_alcance;

CREATE TABLE public.indicadores_alcance (
	id_indicador_alcance serial4 NOT NULL,
	id_nivel_desempeno int4 NOT NULL,
	descripcion text NOT NULL,
	orden int4 DEFAULT 0 NULL,
	fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT indicadores_alcance_pkey PRIMARY KEY (id_indicador_alcance),
	CONSTRAINT fk_indicadores_nivel_desempeno FOREIGN KEY (id_nivel_desempeno) REFERENCES public.niveles_desempeno_instrumentacion(id_nivel_desempeno) ON DELETE CASCADE
);
CREATE INDEX idx_indicadores_nivel_desempeno ON public.indicadores_alcance USING btree (id_nivel_desempeno);



-- DROP FUNCTION public.actualizar_avance_y_detalles(int4, varchar, varchar, varchar, jsonb);

CREATE OR REPLACE FUNCTION public.actualizar_avance_y_detalles(p_id_avance integer, p_firma_profesor character varying DEFAULT NULL::character varying, p_firma_jefe character varying DEFAULT NULL::character varying, p_estado character varying DEFAULT NULL::character varying, p_detalles jsonb DEFAULT '[]'::jsonb)
 RETURNS boolean
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_detalle JSONB;
    v_update_count INTEGER;
BEGIN
    -- Verificar que el avance existe
    IF NOT EXISTS (SELECT 1 FROM avance WHERE id_avance = p_id_avance) THEN
        RAISE EXCEPTION 'No existe el avance con ID %', p_id_avance;
    END IF;

    -- Actualizar campos de la tabla principal (avance)
    UPDATE avance
    SET
        firma_profesor = COALESCE(p_firma_profesor, firma_profesor),
        firma_jefe_carrera = COALESCE(p_firma_jefe, firma_jefe_carrera),
        estado = COALESCE(p_estado, estado),
        fecha_ultima_actualizacion = CURRENT_TIMESTAMP
    WHERE id_avance = p_id_avance;

    -- Recorrer cada detalle para actualizar
    FOR v_detalle IN SELECT * FROM jsonb_array_elements(p_detalles)
    LOOP
        -- Actualizar y capturar si hizo update
        UPDATE avance_detalle
        SET
            fecha_programada_inicio = COALESCE(NULLIF(v_detalle->>'fecha_programada_inicio', '')::DATE, fecha_programada_inicio),
            fecha_programada_fin = COALESCE(NULLIF(v_detalle->>'fecha_programada_fin', '')::DATE, fecha_programada_fin),
            fecha_real_inicio = COALESCE(NULLIF(v_detalle->>'fecha_real_inicio', '')::DATE, fecha_real_inicio),
            fecha_real_fin = COALESCE(NULLIF(v_detalle->>'fecha_real_fin', '')::DATE, fecha_real_fin),
            evaluacion_programada_inicio = COALESCE(NULLIF(v_detalle->>'evaluacion_programada_inicio', '')::DATE, evaluacion_programada_inicio),
            evaluacion_programada_fin = COALESCE(NULLIF(v_detalle->>'evaluacion_programada_fin', '')::DATE, evaluacion_programada_fin),
            evaluacion_realizada_inicio = COALESCE(NULLIF(v_detalle->>'evaluacion_realizada_inicio', '')::DATE, evaluacion_realizada_inicio),
            evaluacion_realizada_fin = COALESCE(NULLIF(v_detalle->>'evaluacion_realizada_fin', '')::DATE, evaluacion_realizada_fin),
            porcentaje = COALESCE(NULLIF(v_detalle->>'porcentaje', '')::INTEGER, porcentaje),
            observaciones = COALESCE(v_detalle->>'observaciones', observaciones)
        WHERE id_avance_detalle = (v_detalle->>'id_avance_detalle')::INTEGER
          AND id_avance = p_id_avance;

        GET DIAGNOSTICS v_update_count = ROW_COUNT;

        IF v_update_count = 0 THEN
            RAISE NOTICE 'No se actualizó detalle con id_avance_detalle %', (v_detalle->>'id_avance_detalle')::INTEGER;
        END IF;
    END LOOP;

    RETURN TRUE;
END;
$function$
;

-- DROP FUNCTION public.actualizar_comision(int8, varchar, varchar, estado_enum, varchar, varchar, varchar, varchar, text, origen_enum, bool, int4, int8, _int8);

CREATE OR REPLACE FUNCTION public.actualizar_comision(p_id_comision bigint, p_nombre_evento character varying DEFAULT NULL::character varying, p_tipo_evento character varying DEFAULT NULL::character varying, p_estado estado_enum DEFAULT NULL::estado_enum, p_folio character varying DEFAULT NULL::character varying, p_remitente_nombre character varying DEFAULT NULL::character varying, p_remitente_puesto character varying DEFAULT NULL::character varying, p_lugar character varying DEFAULT NULL::character varying, p_motivo text DEFAULT NULL::text, p_tipo_comision origen_enum DEFAULT NULL::origen_enum, p_permiso_constancia boolean DEFAULT NULL::boolean, p_id_tipo_evento integer DEFAULT NULL::integer, p_id_periodo_escolar bigint DEFAULT NULL::bigint, p_tarjetas_maestros bigint[] DEFAULT NULL::bigint[])
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    resultado json;
BEGIN
    -- Verificar existencia
    IF NOT EXISTS (SELECT 1 FROM public.comision WHERE id_comision = p_id_comision) THEN
        RETURN json_build_object('status','error','message','Comisión no encontrada');
    END IF;

    -- Actualización parcial de campos base
    UPDATE public.comision c
       SET nombre_evento      = COALESCE(p_nombre_evento,      c.nombre_evento),
           tipo_evento        = COALESCE(p_tipo_evento,        c.tipo_evento),
           estado             = COALESCE(p_estado,             c.estado),
           folio              = COALESCE(p_folio,              c.folio),
           remitente_nombre   = COALESCE(p_remitente_nombre,   c.remitente_nombre),
           remitente_puesto   = COALESCE(p_remitente_puesto,   c.remitente_puesto),
           lugar              = COALESCE(p_lugar,              c.lugar),
           motivo             = COALESCE(p_motivo,             c.motivo),
           tipo_comision      = COALESCE(p_tipo_comision,      c.tipo_comision),
           permiso_constancia = COALESCE(p_permiso_constancia, c.permiso_constancia),
           id_tipo_evento     = COALESCE(p_id_tipo_evento,     c.id_tipo_evento),
           id_periodo_escolar = COALESCE(p_id_periodo_escolar, c.id_periodo_escolar)
     WHERE c.id_comision = p_id_comision;

    -- Manejo de relaciones con maestros:
    --  - NULL => no tocar
    --  - '{}' => borrar todas
    --  - valores => reemplazar
    IF p_tarjetas_maestros IS NOT NULL THEN
        DELETE FROM public.comision_maestro
         WHERE id_comision = p_id_comision;

        IF array_length(p_tarjetas_maestros, 1) IS NOT NULL THEN
            INSERT INTO public.comision_maestro (id_comision, tarjeta_maestro)
            SELECT p_id_comision, unnest(p_tarjetas_maestros);
        END IF;
    END IF;

    resultado := json_build_object(
        'status',  'success',
        'message', 'Comisión actualizada correctamente',
        'id_comision', p_id_comision
    );

    RETURN resultado;

EXCEPTION
    WHEN foreign_key_violation THEN
        -- Por referencias inválidas de id_tipo_evento o id_periodo_escolar
        RETURN json_build_object('status','error',
                                 'message','Violación de llave foránea (id_tipo_evento o id_periodo_escolar incorrectos)');
    WHEN check_violation THEN
        RETURN json_build_object('status','error',
                                 'message','Violación de restricción CHECK en la tabla comision');
    WHEN unique_violation THEN
        RETURN json_build_object('status','error',
                                 'message','Violación de restricción UNIQUE (por ejemplo, folio duplicado si aplica)');
END;
$function$
;

-- DROP FUNCTION public.actualizar_competencia(int8, varchar, varchar, _tipo_comp);

CREATE OR REPLACE FUNCTION public.actualizar_competencia(p_id_competencia bigint, p_clave_asignatura character varying DEFAULT NULL::character varying, p_descripcion character varying DEFAULT NULL::character varying, p_tipo_comp tipo_comp[] DEFAULT NULL::tipo_comp[])
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    resultado JSONB := '{}';
BEGIN
    IF NOT EXISTS (SELECT 1 FROM competencia WHERE "id_Competencia" = p_id_competencia) THEN
        RETURN jsonb_build_object(
            'success', false,
            'message', 'Competencia no encontrada'
        );
    END IF;

    UPDATE competencia SET
        "Clave_Asignatura" = COALESCE(p_clave_asignatura, "Clave_Asignatura"),
        "Descripcion" = COALESCE(p_descripcion, "Descripcion"),
        "Tipo_Competencia" = COALESCE(p_tipo_comp, "Tipo_Competencia")
    WHERE "id_Competencia" = p_id_competencia;

    resultado := jsonb_build_object(
        'success', true,
        'message', 'Competencia actualizada correctamente',
        'id_Competencia', p_id_competencia
    );

    RETURN resultado;
EXCEPTION WHEN OTHERS THEN
    RETURN jsonb_build_object(
        'success', false,
        'message', 'Error al actualizar competencia: ' || SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.actualizar_conjunto_competencia_actividad(int8, varchar, _tipo_comp, int8, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_conjunto_competencia_actividad(p_comp_id bigint DEFAULT NULL::bigint, p_desc_competencia character varying DEFAULT NULL::character varying, p_tipo_competencia tipo_comp[] DEFAULT NULL::tipo_comp[], p_act_id bigint DEFAULT NULL::bigint, p_desc_actividad character varying DEFAULT NULL::character varying)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    -- Actualizar competencia si se proporciona su ID
    IF p_comp_id IS NOT NULL THEN
        UPDATE competencia_tema
        SET 
            "Descripcion_Comp_Tema" = COALESCE(p_desc_competencia, "Descripcion_Comp_Tema"),
            "Tipo_Competencia" = COALESCE(p_tipo_competencia, "Tipo_Competencia")
        WHERE "id_Comp_Tema" = p_comp_id;
    END IF;
    
    -- Actualizar actividad si se proporciona su ID
    IF p_act_id IS NOT NULL THEN
        UPDATE actividad_aprendizaje
        SET 
            "Descripcion_Act_Aprendizaje" = COALESCE(p_desc_actividad, "Descripcion_Act_Aprendizaje")
        WHERE "id_Act_Aprendizaje" = p_act_id;
    END IF;
END;
$function$
;

-- DROP FUNCTION public.actualizar_departamento(int2, varchar, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_departamento(p_id_departamento smallint, p_nombre character varying DEFAULT NULL::character varying, p_abreviacion character varying DEFAULT NULL::character varying)
 RETURNS boolean
 LANGUAGE plpgsql
AS $function$
BEGIN
    UPDATE departamentos
    SET 
        nombre = COALESCE(p_nombre, nombre),
        abreviacion = COALESCE(p_abreviacion, abreviacion)
    WHERE id_departamento = p_id_departamento;
    
    RETURN FOUND;
END;
$function$
;

-- DROP FUNCTION public.actualizar_detalle_instrumentacion(int4, date, date, date, date, int4, text);

CREATE OR REPLACE FUNCTION public.actualizar_detalle_instrumentacion(p_id_detalle integer, p_fecha_programada_inicio date DEFAULT NULL::date, p_fecha_programada_fin date DEFAULT NULL::date, p_evaluacion_programada_inicio date DEFAULT NULL::date, p_evaluacion_programada_fin date DEFAULT NULL::date, p_porcentaje integer DEFAULT NULL::integer, p_observaciones text DEFAULT NULL::text)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    result JSON;
BEGIN
    -- Verificar si existe el detalle
    IF NOT EXISTS (
        SELECT 1 FROM instrumentacion_detalle 
        WHERE "id_Instrumentacion_Detalle" = p_id_detalle
    ) THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'El detalle especificado no existe'
        );
    END IF;

    -- Validar lógica de fechas programadas
    IF p_fecha_programada_inicio IS NOT NULL AND p_fecha_programada_fin IS NOT NULL AND
       p_fecha_programada_inicio > p_fecha_programada_fin THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'La fecha programada de inicio no puede ser posterior a la de fin'
        );
    END IF;

    -- Validar lógica de fechas de evaluación
    IF p_evaluacion_programada_inicio IS NOT NULL AND p_evaluacion_programada_fin IS NOT NULL AND
       p_evaluacion_programada_inicio > p_evaluacion_programada_fin THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'La evaluación programada de inicio no puede ser posterior a la de fin'
        );
    END IF;

    -- Validar porcentaje
    IF p_porcentaje IS NOT NULL AND (p_porcentaje < 0 OR p_porcentaje > 100) THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'El porcentaje debe estar entre 0 y 100'
        );
    END IF;

    -- Actualizar campos existentes
    UPDATE instrumentacion_detalle
    SET
        "Fecha_Programada_Inicio" = COALESCE(p_fecha_programada_inicio, "Fecha_Programada_Inicio"),
        "Fecha_Programada_Fin" = COALESCE(p_fecha_programada_fin, "Fecha_Programada_Fin"),
        "Evaluacion_Programada_Inicio" = COALESCE(p_evaluacion_programada_inicio, "Evaluacion_Programada_Inicio"),
        "Evaluacion_Programada_Fin" = COALESCE(p_evaluacion_programada_fin, "Evaluacion_Programada_Fin"),
        "Porcentaje" = COALESCE(p_porcentaje, "Porcentaje"),
        "Observaciones" = COALESCE(p_observaciones, "Observaciones")
    WHERE "id_Instrumentacion_Detalle" = p_id_detalle;

    -- Respuesta
    result := json_build_object(
        'status', 'success',
        'message', 'Detalle actualizado correctamente',
        'id_detalle', p_id_detalle
    );

    RETURN result;

EXCEPTION WHEN OTHERS THEN
    RETURN json_build_object(
        'status', 'error',
        'message', SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.actualizar_diseno_completo(int8, varchar, date, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_diseno_completo(p_diseno_id bigint, p_clave_asignatura character varying DEFAULT NULL::character varying, p_fecha date DEFAULT NULL::date, p_evento character varying DEFAULT NULL::character varying)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    resultado JSONB := jsonb_build_object();
BEGIN
    BEGIN
        -- 1. Verificar existencia del diseño
        IF NOT EXISTS (SELECT 1 FROM diseno_curricular WHERE "id_Dis_Curricular" = p_diseno_id) THEN
            RAISE EXCEPTION 'El diseño curricular con ID % no existe', p_diseno_id;
        END IF;
        
        -- 2. Actualizar campos del diseño si se proporcionan
        IF p_clave_asignatura IS NOT NULL OR p_fecha IS NOT NULL OR p_evento IS NOT NULL THEN
            -- Verificar nueva asignatura si se va a cambiar
            IF p_clave_asignatura IS NOT NULL AND 
               NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
                RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
            END IF;
            
            UPDATE diseno_curricular
            SET 
                "Clave_Asignatura" = COALESCE(p_clave_asignatura, "Clave_Asignatura"),
                "Fecha" = COALESCE(p_fecha, "Fecha"),
                "Evento" = COALESCE(p_evento, "Evento")
            WHERE "id_Dis_Curricular" = p_diseno_id;
            
            resultado := jsonb_set(resultado, '{diseno}', 
                         jsonb_build_object('id', p_diseno_id, 'status', 'updated'));
        ELSE
            resultado := jsonb_set(resultado, '{diseno}', 
                         jsonb_build_object('id', p_diseno_id, 'status', 'no_changes'));
        END IF;
        
        -- Confirmar transacción
        RETURN resultado;
        
    EXCEPTION WHEN OTHERS THEN
        RETURN jsonb_build_object(
            'success', false,
            'message', 'Error en la actualización: ' || SQLERRM,
            'error_details', SQLSTATE
        );
    END;
END;
$function$
;

-- DROP FUNCTION public.actualizar_evaluacion(int8, varchar, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_evaluacion(p_id_evaluacion bigint, p_clave_asignatura character varying, p_criterios_evaluacion character varying)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM evaluacion WHERE "id_Evaluacion" = p_id_evaluacion) THEN
        RAISE EXCEPTION 'Evaluación con ID % no existe', p_id_evaluacion;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    UPDATE evaluacion
    SET "Clave_Asignatura" = p_clave_asignatura,
        "Criterios_Evaluacion" = p_criterios_evaluacion
    WHERE "id_Evaluacion" = p_id_evaluacion;
END;
$function$
;

-- DROP FUNCTION public.actualizar_fuente_informacion(int8, varchar, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_fuente_informacion(p_id_fuente_info bigint, p_clave_asignatura character varying, p_referencia_fuente_info character varying)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM fuente_informacion WHERE "id_Fuente_Info" = p_id_fuente_info) THEN
        RAISE EXCEPTION 'Fuente de información con ID % no existe', p_id_fuente_info;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    UPDATE fuente_informacion
    SET "Clave_Asignatura" = p_clave_asignatura,
        "Referencia_Fuente_Info" = p_referencia_fuente_info
    WHERE "id_Fuente_Info" = p_id_fuente_info;
END;
$function$
;

-- DROP FUNCTION public.actualizar_horario_maestro(int4, varchar, time, time);

CREATE OR REPLACE FUNCTION public.actualizar_horario_maestro(p_id integer, p_dia_semana character varying DEFAULT NULL::character varying, p_hora_inicio time without time zone DEFAULT NULL::time without time zone, p_hora_fin time without time zone DEFAULT NULL::time without time zone)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    maestro_id_local INT;
    traslape_count INT;
    horario_actual RECORD;
    cambios_realizados BOOLEAN := FALSE;
    mensaje TEXT := '';
BEGIN
    -- Obtener el horario actual y verificar existencia
    SELECT maestro_id, dia_semana, hora_inicio, hora_fin 
    INTO horario_actual
    FROM horarios_maestros 
    WHERE id_Horario_Maestro = p_id;

    IF NOT FOUND THEN
        RETURN 'ERROR: No existe un horario con el ID ' || p_id || '.';
    END IF;

    -- Asignar valores actuales si no se proporcionan nuevos
    IF p_dia_semana IS NULL THEN
        p_dia_semana := horario_actual.dia_semana;
    ELSE
        -- Validar día de la semana
        IF p_dia_semana NOT IN ('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo') THEN
            RETURN 'ERROR: Día de la semana no válido. Los valores permitidos son: Lunes, Martes, Miércoles, Jueves, Viernes, Sábado, Domingo.';
        END IF;
    END IF;

    IF p_hora_inicio IS NULL THEN
        p_hora_inicio := horario_actual.hora_inicio;
    END IF;

    IF p_hora_fin IS NULL THEN
        p_hora_fin := horario_actual.hora_fin;
    ELSE
        -- Validar que hora_fin sea mayor que hora_inicio
        IF p_hora_fin <= p_hora_inicio THEN
            RETURN 'ERROR: La hora de fin (' || p_hora_fin || ') debe ser mayor que la hora de inicio (' || p_hora_inicio || ').';
        END IF;
    END IF;

    -- Verificar si hay cambios reales
    IF p_dia_semana = horario_actual.dia_semana AND
       p_hora_inicio = horario_actual.hora_inicio AND
       p_hora_fin = horario_actual.hora_fin THEN
        RETURN 'AVISO: No se realizaron cambios. Los datos proporcionados son idénticos a los existentes para el horario con ID ' || p_id || '.';
    END IF;

    -- Verificar traslapes solo si cambió el día o las horas
    IF p_dia_semana <> horario_actual.dia_semana OR 
       p_hora_inicio <> horario_actual.hora_inicio OR 
       p_hora_fin <> horario_actual.hora_fin THEN
        
        -- Lógica mejorada de detección de traslapes
        SELECT COUNT(*) INTO traslape_count
        FROM horarios_maestros
        WHERE maestro_id = horario_actual.maestro_id
          AND dia_semana = p_dia_semana
          AND id_Horario_Maestro != p_id
          AND (
              -- Caso 1: Nuevo horario comienza dentro de un horario existente
              (p_hora_inicio >= hora_inicio AND p_hora_inicio < hora_fin) OR
              -- Caso 2: Nuevo horario termina dentro de un horario existente
              (p_hora_fin > hora_inicio AND p_hora_fin <= hora_fin) OR
              -- Caso 3: Nuevo horario engloba completamente un horario existente
              (p_hora_inicio <= hora_inicio AND p_hora_fin >= hora_fin)
          );

        IF traslape_count > 0 THEN
            RETURN 'ERROR: El nuevo horario (' || p_dia_semana || ' de ' || 
                   p_hora_inicio || ' a ' || p_hora_fin || 
                   ') se traslapa con uno o más horarios existentes para este maestro.';
        END IF;
        
        cambios_realizados  := TRUE;
    END IF;

    -- Realizar la actualización si hay cambios válidos
    IF cambios_realizados  THEN
        UPDATE horarios_maestros
        SET dia_semana = p_dia_semana,
            hora_inicio = p_hora_inicio,
            hora_fin = p_hora_fin
        WHERE id_Horario_Maestro = p_id;

        RETURN 'ÉXITO: Horario con ID ' || p_id || ' actualizado correctamente. ' ||
               'Cambios realizados: ' || 
               CASE WHEN p_dia_semana <> horario_actual.dia_semana THEN 'Día: ' || horario_actual.dia_semana || ' → ' || p_dia_semana || '; ' ELSE '' END ||
               CASE WHEN p_hora_inicio <> horario_actual.hora_inicio THEN 'Inicio: ' || horario_actual.hora_inicio || ' → ' || p_hora_inicio || '; ' ELSE '' END ||
               CASE WHEN p_hora_fin <> horario_actual.hora_fin THEN 'Fin: ' || horario_actual.hora_fin || ' → ' || p_hora_fin || '; ' ELSE '' END;
    ELSE
        RETURN 'AVISO: No se realizaron cambios en el horario con ID ' || p_id || '.';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.actualizar_instrumentacion(int4, varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_instrumentacion(p_id_instrumentacion integer, p_firma_profesor character varying DEFAULT NULL::character varying, p_firma_jefe character varying DEFAULT NULL::character varying, p_estado character varying DEFAULT NULL::character varying)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    result JSON;
BEGIN
    -- Verificar que la instrumentación existe
    IF NOT EXISTS (
        SELECT 1 FROM instrumentacion WHERE "id_Instrumentacion" = p_id_instrumentacion
    ) THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'La instrumentación especificada no existe'
        );
    END IF;

    -- Actualizar solo los campos que no son NULL (COALESCE para no sobrescribir con NULL)
    UPDATE instrumentacion
    SET
        "Firma_Profesor" = COALESCE(p_firma_profesor, "Firma_Profesor"),
        "Firma_Jefe" = COALESCE(p_firma_jefe, "Firma_Jefe"),
        "Estado" = COALESCE(p_estado, "Estado")
    WHERE "id_Instrumentacion" = p_id_instrumentacion;

    -- Devolver resultado exitoso
    result := json_build_object(
        'status', 'success',
        'message', 'Instrumentación actualizada correctamente',
        'id_instrumentacion', p_id_instrumentacion
    );

    RETURN result;

EXCEPTION WHEN OTHERS THEN
    RETURN json_build_object(
        'status', 'error',
        'message', SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.actualizar_participantes_diseno(int8, jsonb);

CREATE OR REPLACE FUNCTION public.actualizar_participantes_diseno(p_diseno_id bigint, p_participantes jsonb)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    resultado JSONB := jsonb_build_object();
    errores TEXT[] := '{}';
    success_count INT := 0;
    error_count INT := 0;
    participante_record JSONB;
    participante_id BIGINT;
    participante_nombre TEXT;
BEGIN
    BEGIN
        -- Verificar existencia del diseño
        IF NOT EXISTS (SELECT 1 FROM diseno_curricular WHERE "id_Dis_Curricular" = p_diseno_id) THEN
            RAISE EXCEPTION 'El diseño curricular con ID % no existe', p_diseno_id;
        END IF;

        -- Procesar cada participante
        FOR participante_record IN SELECT * FROM jsonb_array_elements(p_participantes)
        LOOP
            BEGIN
                participante_nombre := participante_record->>'nombre';

                -- Caso 1: Participante existente
                IF participante_record ? 'id' THEN
                    participante_id := (participante_record->>'id')::BIGINT;

                    -- Verificar existencia
                    IF NOT EXISTS (SELECT 1 FROM participante WHERE "id_Participante" = participante_id) THEN
                        RAISE EXCEPTION 'Participante con ID % no existe', participante_id;
                    END IF;

                    -- Actualizar nombre si se proporciona
                    IF participante_nombre IS NOT NULL THEN
                        UPDATE participante
                        SET "Nombre_Participante" = participante_nombre
                        WHERE "id_Participante" = participante_id;
                    END IF;

                -- Caso 2: Nuevo participante (solo nombre)
                ELSIF participante_nombre IS NOT NULL THEN
                    INSERT INTO participante ("Nombre_Participante")
                    VALUES (participante_nombre)
                    RETURNING "id_Participante" INTO participante_id;

                    resultado := jsonb_set(resultado, 
                                    '{participantes_creados}', 
                                    coalesce(resultado->'participantes_creados', '[]'::jsonb) || 
                                    jsonb_build_object('id', participante_id, 'nombre', participante_nombre));
                ELSE
                    RAISE EXCEPTION 'Cada participante debe tener al menos "id" o "nombre"';
                END IF;

                -- Crear la relación si no existe
                IF NOT EXISTS (
                    SELECT 1 FROM diseno_participante 
                    WHERE "Diseno_id" = p_diseno_id AND "Participante_id" = participante_id
                ) THEN
                    INSERT INTO diseno_participante ("Diseno_id", "Participante_id")
                    VALUES (p_diseno_id, participante_id);
                END IF;

                success_count := success_count + 1;

            EXCEPTION WHEN OTHERS THEN
                error_count := error_count + 1;
                errores := array_append(errores, 
                                'Participante ' || coalesce(participante_nombre, participante_record->>'id') || 
                                ': ' || SQLERRM);
            END;
        END LOOP;

        resultado := jsonb_set(resultado, '{summary}', 
                    jsonb_build_object(
                        'total_participantes', jsonb_array_length(p_participantes),
                        'asociados_exitosamente', success_count,
                        'errores', error_count,
                        'diseno_id', p_diseno_id
                    ));

        IF array_length(errores, 1) > 0 THEN
            resultado := jsonb_set(resultado, '{errores_detallados}', to_jsonb(errores));
        END IF;

        RETURN resultado;

    EXCEPTION WHEN OTHERS THEN
        RETURN jsonb_build_object(
            'success', false,
            'message', 'Error en la actualización de participantes: ' || SQLERRM,
            'error_details', SQLSTATE
        );
    END;
END;
$function$
;

-- DROP FUNCTION public.actualizar_practica(int8, varchar, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_practica(p_id_practica bigint, p_clave_asignatura character varying, p_descripcion_practica character varying)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    -- Verificar que la práctica exista
    IF NOT EXISTS (
        SELECT 1 FROM practica WHERE "id_Practica" = p_id_practica
    ) THEN
        RAISE EXCEPTION 'La práctica con ID % no existe', p_id_practica;
    END IF;

    -- Verificar que la asignatura exista
    IF NOT EXISTS (
        SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura
    ) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    -- Actualizar la práctica
    UPDATE practica
    SET 
        "Clave_Asignatura" = p_clave_asignatura,
        "Descripcion_Practica" = p_descripcion_practica
    WHERE "id_Practica" = p_id_practica;
END;
$function$
;

-- DROP FUNCTION public.actualizar_presentacion(int8, varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_presentacion(p_id_presentacion bigint, p_clave_asignatura character varying, p_caracterizacion character varying, p_intencion_didactica character varying)
 RETURNS boolean
 LANGUAGE plpgsql
AS $function$
BEGIN
    -- Verificar que la asignatura exista
    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;
    
    -- Verificar que la presentación exista
    IF NOT EXISTS (SELECT 1 FROM presentacion WHERE "id_Presentacion" = p_id_presentacion) THEN
        RETURN FALSE;
    END IF;
    
    -- Actualizar la presentación
    UPDATE presentacion
    SET 
        "Clave_Asignatura" = p_clave_asignatura,
        "Caracterizacion" = p_caracterizacion,
        "Intencion_didactica" = p_intencion_didactica
    WHERE 
        "id_Presentacion" = p_id_presentacion;
    
    RETURN TRUE;
END;
$function$
;

-- DROP FUNCTION public.actualizar_proyecto_asignatura(int8, varchar, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_proyecto_asignatura(p_id_proyecto_asig bigint, p_clave_asignatura character varying, p_descripcion_proyecto_asig character varying)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM proyecto_asignatura WHERE "id_Proyecto_Asig" = p_id_proyecto_asig) THEN
        RAISE EXCEPTION 'Proyecto asignatura con ID % no existe', p_id_proyecto_asig;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    UPDATE proyecto_asignatura
    SET "Clave_Asignatura" = p_clave_asignatura,
        "Descripcion_Proyecto_Asig" = p_descripcion_proyecto_asig
    WHERE "id_Proyecto_Asig" = p_id_proyecto_asig;
END;
$function$
;

-- DROP FUNCTION public.actualizar_subtema(int8, int8, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_subtema(p_id_subtema bigint, p_tema_id bigint, p_nombre_subtema character varying)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM subtema WHERE "id_Subtema" = p_id_subtema) THEN
        RAISE EXCEPTION 'Subtema con ID % no existe', p_id_subtema;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM tema WHERE "id_Tema" = p_tema_id) THEN
        RAISE EXCEPTION 'Tema con ID % no existe', p_tema_id;
    END IF;

    UPDATE subtema
    SET "Tema_id" = p_tema_id,
        "Nombre_Subtema" = p_nombre_subtema
    WHERE "id_Subtema" = p_id_subtema;
END;
$function$
;

-- DROP FUNCTION public.actualizar_tema(int8, varchar, int4, varchar);

CREATE OR REPLACE FUNCTION public.actualizar_tema(p_id_tema bigint, p_clave_asignatura character varying, p_numero integer, p_nombre_tema character varying)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM tema WHERE "id_Tema" = p_id_tema) THEN
        RAISE EXCEPTION 'Tema con ID % no existe', p_id_tema;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'Asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    UPDATE tema
    SET "Clave_Asignatura" = p_clave_asignatura,
        "Numero" = p_numero,
        "Nombre_Tema" = p_nombre_tema
    WHERE "id_Tema" = p_id_tema;
END;
$function$
;

-- DROP FUNCTION public.agregar_detalle_instrumentacion(int4, int8, date, date, date, date, int4, text);

CREATE OR REPLACE FUNCTION public.agregar_detalle_instrumentacion(p_id_instrumentacion integer, p_id_tema bigint, p_fecha_programada_inicio date DEFAULT NULL::date, p_fecha_programada_fin date DEFAULT NULL::date, p_evaluacion_programada_inicio date DEFAULT NULL::date, p_evaluacion_programada_fin date DEFAULT NULL::date, p_porcentaje integer DEFAULT NULL::integer, p_observaciones text DEFAULT NULL::text)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_resultado_id INTEGER;
    v_tema_exists BOOLEAN;
BEGIN
    -- Validar que la instrumentación existe
    IF NOT EXISTS (SELECT 1 FROM instrumentacion WHERE "id_Instrumentacion" = p_id_instrumentacion) THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'La instrumentación especificada no existe'
        );
    END IF;

    -- Validar que el tema existe
    SELECT EXISTS(SELECT 1 FROM tema WHERE "id_Tema" = p_id_tema) INTO v_tema_exists;
    IF NOT v_tema_exists THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'El tema especificado no existe'
        );
    END IF;

    -- Validar porcentaje si se proporciona
    IF p_porcentaje IS NOT NULL AND (p_porcentaje < 0 OR p_porcentaje > 100) THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'El porcentaje debe estar entre 0 y 100'
        );
    END IF;

    -- Validar fechas si se proporcionan
    IF p_fecha_programada_inicio IS NOT NULL AND p_fecha_programada_fin IS NOT NULL AND 
       p_fecha_programada_inicio > p_fecha_programada_fin THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'La fecha de inicio no puede ser posterior a la fecha de fin'
        );
    END IF;

    -- Insertar el detalle y obtener el ID generado
    INSERT INTO instrumentacion_detalle (
        "id_Instrumentacion",
        "id_Tema",
        "Fecha_Programada_Inicio",
        "Fecha_Programada_Fin",
        "Evaluacion_Programada_Inicio",
        "Evaluacion_Programada_Fin",
        "Porcentaje",
        "Observaciones"
    ) VALUES (
        p_id_instrumentacion,
        p_id_tema,
        p_fecha_programada_inicio,
        p_fecha_programada_fin,
        p_evaluacion_programada_inicio,
        p_evaluacion_programada_fin,
        p_porcentaje,
        p_observaciones
    ) RETURNING "id_Instrumentacion_Detalle" INTO v_resultado_id;

    -- Devolver el resultado
    RETURN json_build_object(
        'status', 'success',
        'message', 'Detalle de instrumentación agregado correctamente',
        'id_detalle', v_resultado_id
    );

EXCEPTION WHEN OTHERS THEN
    RETURN json_build_object(
        'status', 'error',
        'message', SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.asignar_maestro_departamento(int2, int4);

CREATE OR REPLACE FUNCTION public.asignar_maestro_departamento(p_id_departamento smallint, p_maestro_id integer)
 RETURNS boolean
 LANGUAGE plpgsql
AS $function$
BEGIN
    UPDATE maestros
    SET id_departamento = p_id_departamento
    WHERE tarjeta = p_maestro_id;
    
    RETURN FOUND;
END;
$function$
;

-- DROP FUNCTION public.cerrar_bitacora_alumno(int8);

CREATE OR REPLACE FUNCTION public.cerrar_bitacora_alumno(p_id_bitacora_alumno bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_hora_entrada TIMESTAMP;
BEGIN
    -- Obtener hora_entrada
    SELECT hora_entrada
      INTO v_hora_entrada
      FROM bitacora_alumnos
     WHERE id_bitacora_alumno = p_id_bitacora_alumno;

    IF v_hora_entrada IS NULL THEN
        RETURN 'Error: Registro no encontrado';
    END IF;

    -- Actualizar hora_salida y tiempo_uso
    UPDATE bitacora_alumnos
       SET hora_salida = NOW(),
           tiempo_uso  = NOW() - v_hora_entrada
     WHERE id_bitacora_alumno = p_id_bitacora_alumno;

    RETURN 'Sesión cerrada y tiempo calculado correctamente';
END;
$function$
;

-- DROP FUNCTION public.close_bitacora_alumno(int8);

CREATE OR REPLACE FUNCTION public.close_bitacora_alumno(p_id_bitacora bigint)
 RETURNS TABLE(mensaje text, tiempo_uso interval)
 LANGUAGE plpgsql
AS $function$
BEGIN
    -- Verificar que el registro existe y sigue abierto
    IF NOT EXISTS (
        SELECT 1 
        FROM bitacora_alumnos 
        WHERE id_bitacora_alumno = p_id_bitacora 
          AND hora_salida IS NULL
    ) THEN
        RETURN QUERY 
        SELECT 'Error: El registro no existe o ya fue cerrado.'::text, NULL::interval;
        RETURN;
    END IF;

    -- Actualizar hora_salida y calcular tiempo_uso
    UPDATE bitacora_alumnos
    SET hora_salida = now(),
        tiempo_uso = now() - hora_entrada
    WHERE id_bitacora_alumno = p_id_bitacora;

    -- Devolver mensaje y tiempo_uso (calificando columna)
    RETURN QUERY
    SELECT 'Sesión cerrada exitosamente'::text, ba.tiempo_uso
    FROM bitacora_alumnos AS ba
    WHERE ba.id_bitacora_alumno = p_id_bitacora;
END;
$function$
;

-- DROP FUNCTION public.consultar_evaluacion_por_id(int8);

CREATE OR REPLACE FUNCTION public.consultar_evaluacion_por_id(p_id_evaluacion bigint)
 RETURNS TABLE("id_Evaluacion" bigint, "Clave_Asignatura" character varying, "Criterios_Evaluacion" character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT "id_Evaluacion", "Clave_Asignatura", "Criterios_Evaluacion"
    FROM evaluacion
    WHERE "id_Evaluacion" = p_id_evaluacion;
END;
$function$
;

-- DROP FUNCTION public.consultar_fuente_informacion_por_id(int8);

CREATE OR REPLACE FUNCTION public.consultar_fuente_informacion_por_id(p_id_fuente_info bigint)
 RETURNS TABLE("id_Fuente_Info" bigint, "Clave_Asignatura" character varying, "Referencia_Fuente_Info" character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT "id_Fuente_Info", "Clave_Asignatura", "Referencia_Fuente_Info"
    FROM fuente_informacion
    WHERE "id_Fuente_Info" = p_id_fuente_info;
END;
$function$
;

-- DROP FUNCTION public.consultar_proyecto_asignatura_por_id(int8);

CREATE OR REPLACE FUNCTION public.consultar_proyecto_asignatura_por_id(p_id_proyecto_asig bigint)
 RETURNS TABLE("id_Proyecto_Asig" bigint, "Clave_Asignatura" character varying, "Descripcion_Proyecto_Asig" character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT "id_Proyecto_Asig", "Clave_Asignatura", "Descripcion_Proyecto_Asig"
    FROM proyecto_asignatura
    WHERE "id_Proyecto_Asig" = p_id_proyecto_asig;
END;
$function$
;

-- DROP FUNCTION public.consultar_tema_por_id(int8);

CREATE OR REPLACE FUNCTION public.consultar_tema_por_id(p_id_tema bigint)
 RETURNS TABLE("id_Tema" bigint, "Clave_Asignatura" character varying, "Numero" integer, "Nombre_Tema" character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT "id_Tema", "Clave_Asignatura", "Numero", "Nombre_Tema"
    FROM tema
    WHERE "id_Tema" = p_id_tema;
END;
$function$
;

-- DROP FUNCTION public.consultar_todos_temas();

CREATE OR REPLACE FUNCTION public.consultar_todos_temas()
 RETURNS TABLE("id_Tema" bigint, "Clave_Asignatura" character varying, "Numero" integer, "Nombre_Tema" character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        t."id_Tema", 
        t."Clave_Asignatura", 
        t."Numero", 
        t."Nombre_Tema"
    FROM tema t  -- Alias 't' para la tabla
    ORDER BY t."Numero";
END;
$function$
;

-- DROP FUNCTION public.crear_avance_programatico_completo(varchar, int8, varchar, jsonb, varchar, int8);

CREATE OR REPLACE FUNCTION public.crear_avance_programatico_completo(p_clave_asignatura character varying, p_tarjeta_profesor bigint, p_periodo character varying, p_temas jsonb, p_estado character varying DEFAULT 'Borrador'::character varying, p_clave_horario bigint DEFAULT NULL::bigint)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_id_avance INTEGER;
    v_tema JSONB;
    v_porcentaje_total INTEGER := 0;
BEGIN
    -- Verificar que el estado sea válido
    IF p_estado NOT IN ('Borrador', 'En Revisión', 'Aprobado', 'Rechazado') THEN
        RAISE EXCEPTION 'Estado no válido';
    END IF;
    
    -- Verificar que la suma de porcentajes sea 100%
    FOR v_tema IN SELECT * FROM jsonb_array_elements(p_temas)
    LOOP
        IF (v_tema->>'porcentaje') IS NULL THEN
            RAISE EXCEPTION 'Todos los temas deben tener porcentaje definido';
        END IF;
        v_porcentaje_total := v_porcentaje_total + (v_tema->>'porcentaje')::INTEGER;
    END LOOP;
    
    IF v_porcentaje_total != 100 THEN
        RAISE EXCEPTION 'La suma de porcentajes debe ser 100%%';
    END IF;
    
    -- Insertar el avance principal
    INSERT INTO public.avance (
        clave_asignatura,
        tarjeta_profesor,
        periodo,
        estado,
        clave_horario
    ) VALUES (
        p_clave_asignatura,
        p_tarjeta_profesor,
        p_periodo,
        p_estado,
        p_clave_horario
    ) RETURNING id_avance INTO v_id_avance;
    
    -- Insertar los detalles del avance con manejo de nulos
    FOR v_tema IN SELECT * FROM jsonb_array_elements(p_temas)
    LOOP
        INSERT INTO public.avance_detalle (
            id_avance,
            tema,
            subtema,
            fecha_programada_inicio,
            fecha_programada_fin,
            evaluacion_programada_inicio,
            evaluacion_programada_fin,
            porcentaje,
            observaciones,
            completado
        ) VALUES (
            v_id_avance,
            v_tema->>'tema',
            COALESCE(v_tema->>'subtema', ''),
            (v_tema->>'fecha_programada_inicio')::DATE,
            (v_tema->>'fecha_programada_fin')::DATE,
            NULLIF(v_tema->>'evaluacion_programada_inicio', '')::DATE,
            NULLIF(v_tema->>'evaluacion_programada_fin', '')::DATE,
            (v_tema->>'porcentaje')::INTEGER,
            COALESCE(v_tema->>'observaciones', ''),
            COALESCE((v_tema->>'completado')::BOOLEAN, FALSE)
        );
    END LOOP;
    
    RETURN v_id_avance;
END;
$function$
;

-- DROP FUNCTION public.crear_calificacion(int8, int2, varchar, numeric, numeric, varchar, bool);

CREATE OR REPLACE FUNCTION public.crear_calificacion(p_id_carga_detalle bigint, p_unidad smallint, p_tipo_evaluacion character varying, p_calificacion numeric, p_ponderacion numeric, p_tipo_acreditacion character varying DEFAULT NULL::character varying, p_acreditado boolean DEFAULT false)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    new_id bigint;
    result json;
BEGIN
    -- Validaciones iniciales
    IF p_unidad IS NULL OR p_unidad <= 0 THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'La unidad debe ser un número positivo'
        );
    END IF;
    
    IF p_calificacion IS NULL OR p_calificacion < 0 OR p_calificacion > 100 THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'La calificación debe estar entre 0 y 100'
        );
    END IF;

    IF p_tipo_acreditacion IS NOT NULL AND p_tipo_acreditacion NOT IN ('Ordinario', 'Complementario', 'Extraordinario') THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'Tipo de acreditación no válido. Use: Ordinario, Complementario o Extraordinario'
        );
    END IF;

    -- Verificar si ya existe
    IF EXISTS (
        SELECT 1 FROM calificaciones_unidades 
        WHERE IdCargaDetalle = p_id_carga_detalle 
        AND Unidad = p_unidad 
        AND TipoEvaluacion = p_tipo_evaluacion
        AND (TipoAcreditacion = p_tipo_acreditacion OR (TipoAcreditacion IS NULL AND p_tipo_acreditacion IS NULL))
    ) THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'Ya existe una calificación de este tipo para la unidad especificada'
        );
    END IF;

    -- Insertar nueva calificación
    INSERT INTO calificaciones_unidades (
        IdCargaDetalle, Unidad, TipoEvaluacion, 
        Calificacion, Ponderacion, TipoAcreditacion, Acreditado
    ) VALUES (
        p_id_carga_detalle, p_unidad, p_tipo_evaluacion, 
        p_calificacion, p_ponderacion, p_tipo_acreditacion, p_acreditado
    ) RETURNING IdRegistro INTO new_id;

    -- Devolver el resultado
    SELECT json_build_object(
        'status', 'success',
        'id', new_id,
        'data', row_to_json(cu)
    ) INTO result
    FROM calificaciones_unidades cu
    WHERE cu.IdRegistro = new_id;

    RETURN result;
EXCEPTION WHEN OTHERS THEN
    RETURN json_build_object(
        'status', 'error',
        'message', SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.crear_comision_con_maestros(varchar, varchar, int4, int8, estado_enum, varchar, varchar, varchar, varchar, text, origen_enum, bool, _int8);

CREATE OR REPLACE FUNCTION public.crear_comision_con_maestros(p_nombre_evento character varying, p_tipo_evento character varying, p_id_tipo_evento integer, p_id_periodo_escolar bigint, p_estado estado_enum DEFAULT 'pendiente'::estado_enum, p_folio character varying DEFAULT NULL::character varying, p_remitente_nombre character varying DEFAULT NULL::character varying, p_remitente_puesto character varying DEFAULT NULL::character varying, p_lugar character varying DEFAULT NULL::character varying, p_motivo text DEFAULT NULL::text, p_tipo_comision origen_enum DEFAULT 'Interno'::origen_enum, p_permiso_constancia boolean DEFAULT false, p_maestros bigint[] DEFAULT NULL::bigint[])
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_id_comision bigint;
BEGIN
    -- Validaciones FK
    IF NOT EXISTS (SELECT 1 FROM public.tipo_evento te WHERE te.id_tipo_evento = p_id_tipo_evento) THEN
        RETURN json_build_object('status','error','message','id_tipo_evento no existe');
    END IF;

    IF NOT EXISTS (SELECT 1 FROM public.periodo_escolar pe WHERE pe.id_periodo_escolar = p_id_periodo_escolar) THEN
        RETURN json_build_object('status','error','message','id_periodo_escolar no existe');
    END IF;

    -- Insert comisión
    INSERT INTO public.comision (
        folio, nombre_evento, tipo_evento, estado,
        remitente_nombre, remitente_puesto, lugar, motivo,
        tipo_comision, permiso_constancia, id_tipo_evento, id_periodo_escolar
    ) VALUES (
        p_folio, p_nombre_evento, p_tipo_evento, COALESCE(p_estado,'pendiente'),
        p_remitente_nombre, p_remitente_puesto, p_lugar, p_motivo,
        COALESCE(p_tipo_comision,'Interno'), COALESCE(p_permiso_constancia,false),
        p_id_tipo_evento, p_id_periodo_escolar
    )
    RETURNING id_comision INTO v_id_comision;

    -- Insert maestros (si vienen)
    IF p_maestros IS NOT NULL AND array_length(p_maestros, 1) IS NOT NULL THEN
        INSERT INTO public.comision_maestro (id_comision, tarjeta_maestro)
        SELECT v_id_comision, unnest(p_maestros);
    END IF;

    RETURN json_build_object('status','success','comision_id', v_id_comision);

EXCEPTION
    WHEN foreign_key_violation THEN
        RETURN json_build_object('status','error','message','LLave foránea inválida (tipo_evento / periodo_escolar o tarjetas)');
    WHEN check_violation THEN
        RETURN json_build_object('status','error','message','Violación de CHECK en comision');
    WHEN unique_violation THEN
        RETURN json_build_object('status','error','message','Violación UNIQUE (por ejemplo, folio duplicado si aplica)');
    WHEN others THEN
        RETURN json_build_object('status','error','message', SQLERRM);
END;
$function$
;

-- DROP FUNCTION public.crear_competencia(varchar, varchar, _tipo_comp);

CREATE OR REPLACE FUNCTION public.crear_competencia(p_clave_asignatura character varying, p_descripcion character varying, p_tipo_comp tipo_comp[])
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    competencia_id BIGINT;
    resultado JSONB := '{}';
BEGIN
    INSERT INTO competencia ("Clave_Asignatura", "Descripcion", "Tipo_Competencia")
    VALUES (p_clave_asignatura, p_descripcion, p_tipo_comp)
    RETURNING "id_Competencia" INTO competencia_id;

    resultado := jsonb_build_object(
        'success', true,
        'id_Competencia', competencia_id,
        'message', 'Competencia creada correctamente'
    );

    RETURN resultado;
EXCEPTION WHEN OTHERS THEN
    RETURN jsonb_build_object(
        'success', false,
        'message', 'Error al crear competencia: ' || SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.crear_conjunto_competencia_actividad(int8, varchar, _tipo_comp, varchar);

CREATE OR REPLACE FUNCTION public.crear_conjunto_competencia_actividad(p_tema_id bigint, p_desc_competencia character varying, p_tipo_competencia tipo_comp[], p_desc_actividad character varying)
 RETURNS bigint
 LANGUAGE plpgsql
AS $function$
DECLARE
    nueva_competencia_id BIGINT;
    nueva_actividad_id BIGINT;
    nueva_relacion_id BIGINT;
BEGIN
    -- Validar existencia del tema
    IF NOT EXISTS (SELECT 1 FROM tema WHERE "id_Tema" = p_tema_id) THEN
        RAISE EXCEPTION 'Tema con ID % no existe', p_tema_id;
    END IF;

    -- Crear competencia del tema
    INSERT INTO competencia_tema ("Tema_id", "Descripcion_Comp_Tema", "Tipo_Competencia")
    VALUES (p_tema_id, p_desc_competencia, p_tipo_competencia)
    RETURNING "id_Comp_Tema" INTO nueva_competencia_id;

    -- Crear actividad de aprendizaje
    INSERT INTO actividad_aprendizaje ("Tema_id", "Descripcion_Act_Aprendizaje")
    VALUES (p_tema_id, p_desc_actividad)
    RETURNING "id_Act_Aprendizaje" INTO nueva_actividad_id;

    -- Relacionar ambas en actividad_competencia
    INSERT INTO actividad_competencia ("Act_Aprendizaje_id", "Comp_Tema_id")
    VALUES (nueva_actividad_id, nueva_competencia_id)
    RETURNING "id_Act_Competencia" INTO nueva_relacion_id;

    RETURN nueva_relacion_id;
END;
$function$
;

-- DROP FUNCTION public.crear_diseno_completo(varchar, date, varchar, jsonb);

CREATE OR REPLACE FUNCTION public.crear_diseno_completo(p_clave_asignatura character varying, p_fecha date, p_evento character varying, p_participantes jsonb)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    diseno_id BIGINT;
    participante_id BIGINT;
    participante_record JSONB;
    resultado JSONB := jsonb_build_object();
    errores TEXT[] := '{}';
    success_count INT := 0;
    error_count INT := 0;
BEGIN
    -- Iniciar transacción explícita
    BEGIN
        -- 1. Verificar existencia de la asignatura
        IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
            RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
        END IF;
        
        -- 2. Crear el diseño curricular
        INSERT INTO diseno_curricular (
            "Clave_Asignatura",
            "Fecha",
            "Evento"
        ) VALUES (
            p_clave_asignatura,
            p_fecha,
            p_evento
        ) RETURNING "id_Dis_Curricular" INTO diseno_id;
        
        resultado := jsonb_set(resultado, '{diseno}', 
                     jsonb_build_object('id', diseno_id, 'status', 'created'));
        
        -- 3. Procesar cada participante del array JSON
        FOR participante_record IN SELECT * FROM jsonb_array_elements(p_participantes)
        LOOP
            BEGIN
                -- Opción 1: Participante existente (con ID)
                IF participante_record->>'id' IS NOT NULL THEN
                    participante_id := (participante_record->>'id')::BIGINT;
                    
                    -- Verificar que el participante exista
                    IF NOT EXISTS (SELECT 1 FROM participante WHERE "id_Participante" = participante_id) THEN
                        RAISE EXCEPTION 'Participante con ID % no existe', participante_id;
                    END IF;
                    
                -- Opción 2: Nuevo participante (con nombre)
                ELSIF participante_record->>'nombre' IS NOT NULL THEN
                    INSERT INTO participante ("Nombre_Participante")
                    VALUES (participante_record->>'nombre')
                    RETURNING "id_Participante" INTO participante_id;
                    
                    resultado := jsonb_set(resultado, 
                                        '{participantes_created}', 
                                        coalesce(resultado->'participantes_created', '[]'::jsonb) || 
                                        jsonb_build_object('id', participante_id, 'nombre', participante_record->>'nombre'));
                ELSE
                    RAISE EXCEPTION 'Cada participante debe tener "id" o "nombre"';
                END IF;
                
                -- 4. Crear la relación en diseno_participante
                INSERT INTO diseno_participante ("Diseno_id", "Participante_id")
                VALUES (diseno_id, participante_id);
                
                success_count := success_count + 1;
                
            EXCEPTION WHEN OTHERS THEN
                error_count := error_count + 1;
                errores := array_append(errores, 
                                      'Participante ' || coalesce(participante_record->>'nombre', participante_record->>'id') || 
                                      ': ' || SQLERRM);
            END;
        END LOOP;
        
        -- Construir respuesta final
        resultado := jsonb_set(resultado, '{summary}', 
                    jsonb_build_object(
                        'total_participantes', jsonb_array_length(p_participantes),
                        'successful', success_count,
                        'errors', error_count
                    ));
        
        IF array_length(errores, 1) > 0 THEN
            resultado := jsonb_set(resultado, '{errors}', to_jsonb(errores));
        END IF;
        
        -- Confirmar transacción si todo fue bien
        RETURN resultado;
        
    END;
END;
$function$
;

-- DROP FUNCTION public.crear_evaluacion(varchar, varchar);

CREATE OR REPLACE FUNCTION public.crear_evaluacion(p_clave_asignatura character varying, p_criterios_evaluacion character varying)
 RETURNS bigint
 LANGUAGE plpgsql
AS $function$
DECLARE
    nuevo_id BIGINT;
BEGIN
    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    INSERT INTO evaluacion ("Clave_Asignatura", "Criterios_Evaluacion")
    VALUES (p_clave_asignatura, p_criterios_evaluacion)
    RETURNING "id_Evaluacion" INTO nuevo_id;

    RETURN nuevo_id;
END;
$function$
;

-- DROP FUNCTION public.crear_fuente_informacion(varchar, varchar);

CREATE OR REPLACE FUNCTION public.crear_fuente_informacion(p_clave_asignatura character varying, p_referencia_fuente_info character varying)
 RETURNS bigint
 LANGUAGE plpgsql
AS $function$
DECLARE
    nuevo_id BIGINT;
BEGIN
    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    INSERT INTO fuente_informacion ("Clave_Asignatura", "Referencia_Fuente_Info")
    VALUES (p_clave_asignatura, p_referencia_fuente_info)
    RETURNING "id_Fuente_Info" INTO nuevo_id;

    RETURN nuevo_id;
END;
$function$
;

-- DROP FUNCTION public.crear_instrumentacion(varchar, int8, varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.crear_instrumentacion(p_clave_asignatura character varying, p_tarjeta bigint, p_periodo character varying, p_firma_profesor character varying DEFAULT NULL::character varying, p_firma_jefe character varying DEFAULT NULL::character varying)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    new_id INTEGER;
    result JSON;
    v_maestro_exists BOOLEAN;
    v_asignatura_exists BOOLEAN;
BEGIN
    -- Validar que el maestro existe
    SELECT EXISTS(SELECT 1 FROM maestros WHERE tarjeta = p_tarjeta)
    INTO v_maestro_exists;

    IF NOT v_maestro_exists THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'El maestro con la tarjeta proporcionada no existe'
        );
    END IF;

 
    -- Validar que no exista una instrumentación para el mismo periodo
    IF EXISTS (
        SELECT 1 FROM instrumentacion 
        WHERE "Clave_Asignatura" = p_clave_asignatura 
        AND "Periodo" = p_periodo
    ) THEN
        RETURN json_build_object(
            'status', 'error',
            'message', 'Ya existe una instrumentación para esta asignatura en el periodo especificado'
        );
    END IF;

    -- Insertar la nueva instrumentación
    INSERT INTO instrumentacion (
        "Clave_Asignatura", 
        "Tarjeta", 
        "Periodo", 
        "Firma_Profesor", 
        "Firma_Jefe",
        "Estado"
    ) VALUES (
        p_clave_asignatura, 
        p_tarjeta, 
        p_periodo, 
        p_firma_profesor, 
        p_firma_jefe,
        'Borrador'
    ) RETURNING "id_Instrumentacion" INTO new_id;

    -- Devolver el resultado
    SELECT json_build_object(
        'status', 'success',
        'id_instrumentacion', new_id,
        'data', json_build_object(
            'clave_asignatura', p_clave_asignatura,
            'periodo', p_periodo,
            'estado', 'Borrador',
            'fecha_creacion', CURRENT_DATE,
            'total_temas', 0
        )
    ) INTO result;

    RETURN result;

EXCEPTION WHEN OTHERS THEN
    RETURN json_build_object(
        'status', 'error',
        'message', SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.crear_practica(varchar, varchar);

CREATE OR REPLACE FUNCTION public.crear_practica(p_clave_asignatura character varying, p_descripcion_practica character varying)
 RETURNS bigint
 LANGUAGE plpgsql
AS $function$
DECLARE
    nuevo_id BIGINT;
BEGIN
    -- Verificar que la asignatura exista
    IF NOT EXISTS (
        SELECT 1 FROM asignatura 
        WHERE "ClaveAsignatura" = p_clave_asignatura
    ) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    -- Insertar nueva práctica
    INSERT INTO practica (
        "Clave_Asignatura",
        "Descripcion_Practica"
    ) VALUES (
        p_clave_asignatura,
        p_descripcion_practica
    ) RETURNING "id_Practica" INTO nuevo_id;

    RETURN nuevo_id;
END;
$function$
;

-- DROP FUNCTION public.crear_presentacion(varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.crear_presentacion(p_clave_asignatura character varying, p_caracterizacion character varying, p_intencion_didactica character varying)
 RETURNS bigint
 LANGUAGE plpgsql
AS $function$
DECLARE
    nuevo_id BIGINT;
BEGIN
    -- Verificar que la asignatura exista
    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;
    
    -- Insertar la nueva presentación
    INSERT INTO presentacion (
        "Clave_Asignatura",
        "Caracterizacion",
        "Intencion_didactica"
    ) VALUES (
        p_clave_asignatura,
        p_caracterizacion,
        p_intencion_didactica
    ) RETURNING "id_Presentacion" INTO nuevo_id;
    
    RETURN nuevo_id;
END;
$function$
;

-- DROP FUNCTION public.crear_proyecto_asignatura(varchar, varchar);

CREATE OR REPLACE FUNCTION public.crear_proyecto_asignatura(p_clave_asignatura character varying, p_descripcion_proyecto_asig character varying)
 RETURNS bigint
 LANGUAGE plpgsql
AS $function$
DECLARE
    nuevo_id BIGINT;
BEGIN
    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'La asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    INSERT INTO proyecto_asignatura ("Clave_Asignatura", "Descripcion_Proyecto_Asig")
    VALUES (p_clave_asignatura, p_descripcion_proyecto_asig)
    RETURNING "id_Proyecto_Asig" INTO nuevo_id;

    RETURN nuevo_id;
END;
$function$
;

-- DROP FUNCTION public.crear_subtema(int8, varchar);

CREATE OR REPLACE FUNCTION public.crear_subtema(p_tema_id bigint, p_nombre_subtema character varying)
 RETURNS bigint
 LANGUAGE plpgsql
AS $function$
DECLARE
    nuevo_id BIGINT;
BEGIN
    IF NOT EXISTS (SELECT 1 FROM tema WHERE "id_Tema" = p_tema_id) THEN
        RAISE EXCEPTION 'Tema con ID % no existe', p_tema_id;
    END IF;

    INSERT INTO subtema ("Tema_id", "Nombre_Subtema")
    VALUES (p_tema_id, p_nombre_subtema)
    RETURNING "id_Subtema" INTO nuevo_id;

    RETURN nuevo_id;
END;
$function$
;

-- DROP FUNCTION public.crear_tema(varchar, int4, varchar);

CREATE OR REPLACE FUNCTION public.crear_tema(p_clave_asignatura character varying, p_numero integer, p_nombre_tema character varying)
 RETURNS bigint
 LANGUAGE plpgsql
AS $function$
DECLARE
    nuevo_id BIGINT;
BEGIN
    IF NOT EXISTS (SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) THEN
        RAISE EXCEPTION 'Asignatura con clave % no existe', p_clave_asignatura;
    END IF;

    INSERT INTO tema (
        "Clave_Asignatura", "Numero", "Nombre_Tema"
    ) VALUES (
        p_clave_asignatura, p_numero, p_nombre_tema
    )
    RETURNING "id_Tema" INTO nuevo_id;

    RETURN nuevo_id;
END;
$function$
;

-- DROP FUNCTION public.delete_alumno(int8);

CREATE OR REPLACE FUNCTION public.delete_alumno(p_numero_control bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM alumnos WHERE NumeroControl = p_numero_control;
    
    IF FOUND THEN
        RETURN 'Alumno eliminado exitosamente';
    ELSE
        RETURN 'Error: Alumno no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_asignatura(varchar);

CREATE OR REPLACE FUNCTION public.delete_asignatura(p_clave_asignatura character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    -- Eliminar relaciones
    DELETE FROM asignatura_carrera WHERE "Clave_Asignatura" = p_clave_asignatura;

    -- Eliminar asignatura
    DELETE FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura;

    IF FOUND THEN
        RETURN 'Asignatura eliminada exitosamente';
    ELSE
        RETURN 'Error: Asignatura no encontrada';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_aula(varchar);

CREATE OR REPLACE FUNCTION public.delete_aula(p_clave_aula character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM aulas WHERE claveaula = p_clave_aula;

    IF FOUND THEN
        RETURN 'Aula eliminada exitosamente';
    ELSE
        RETURN 'Error: Aula no encontrada';
    END IF;

EXCEPTION
    WHEN foreign_key_violation THEN
        RETURN 'Error: No se puede eliminar; el aula está referenciada por otros registros';
END;
$function$
;

-- DROP FUNCTION public.delete_bitacora_entry(int8);

CREATE OR REPLACE FUNCTION public.delete_bitacora_entry(p_id_bitacora_alumno bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM bitacora_alumnos
    WHERE id_bitacora_alumno = p_id_bitacora_alumno;

    IF FOUND THEN
        RETURN 'Registro eliminado exitosamente';
    ELSE
        RETURN 'Error: Registro no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_bitacora_maestro_entry(int8);

CREATE OR REPLACE FUNCTION public.delete_bitacora_maestro_entry(p_id_bitacora_maestro bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM bitacora_maestros WHERE Id_Bitacora_Maestro = p_id_bitacora_maestro;
    
    IF FOUND THEN
        RETURN 'Registro eliminado exitosamente';
    ELSE
        RETURN 'Error: Registro no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_carga_academica_detalle(int8);

CREATE OR REPLACE FUNCTION public.delete_carga_academica_detalle(p_id_carga_detalle bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM carga_academica_detalles WHERE IdCargaDetalle = p_id_carga_detalle;

    IF FOUND THEN
        RETURN 'Detalle de carga académica eliminado exitosamente';
    ELSE
        RETURN 'Error: Detalle de carga académica no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_carga_academica_general(int8);

CREATE OR REPLACE FUNCTION public.delete_carga_academica_general(p_id_carga_general bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM carga_academica_general WHERE IdCargaGeneral = p_id_carga_general;

    IF FOUND THEN
        RETURN 'Carga académica general eliminada exitosamente';
    ELSE
        RETURN 'Error: Carga académica general no encontrada';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_carrera(varchar);

CREATE OR REPLACE FUNCTION public.delete_carrera(p_clave_carrera character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM carreras WHERE ClaveCarrera = p_clave_carrera;

    IF FOUND THEN
        RETURN 'Carrera eliminada exitosamente';
    ELSE
        RETURN 'Error: Carrera no encontrada';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_computadora(int8);

CREATE OR REPLACE FUNCTION public.delete_computadora(p_numero_inventario bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM computadora WHERE NumeroInventario = p_numero_inventario;

    IF FOUND THEN
        RETURN 'Computadora eliminada exitosamente';
    ELSE
        RETURN 'Error: Computadora no encontrada';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_edificio(varchar);

CREATE OR REPLACE FUNCTION public.delete_edificio(p_clave_edificio character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM edificios WHERE ClaveEdificio = p_clave_edificio;

    IF FOUND THEN
        RETURN 'Edificio eliminado exitosamente';
    ELSE
        RETURN 'Error: Edificio no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_grupo(varchar);

CREATE OR REPLACE FUNCTION public.delete_grupo(p_clave_grupo character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM grupos WHERE ClaveGrupo = p_clave_grupo;

    IF FOUND THEN
        RETURN 'Grupo eliminado exitosamente';
    ELSE
        RETURN 'Error: Grupo no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_horario(int8);

CREATE OR REPLACE FUNCTION public.delete_horario(p_clave_horario bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM horarioasignatura_maestro WHERE ClaveHorario = p_clave_horario;

    IF FOUND THEN
        RETURN 'Horario eliminado exitosamente';
    ELSE
        RETURN 'Error: Horario no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_maestro(int8);

CREATE OR REPLACE FUNCTION public.delete_maestro(p_tarjeta bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM maestros WHERE Tarjeta = p_tarjeta;

    IF FOUND THEN
        RETURN 'Maestro eliminado exitosamente';
    ELSE
        RETURN 'Error: Maestro no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_periodo_escolar(int8);

CREATE OR REPLACE FUNCTION public.delete_periodo_escolar(p_id bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM periodo_escolar WHERE id_periodo_escolar = p_id;

    IF FOUND THEN
        RETURN 'Período escolar eliminado exitosamente';
    ELSE
        RETURN 'Error: Período escolar no encontrado';
    END IF;
EXCEPTION
    WHEN foreign_key_violation THEN
        -- Por si existe referencia desde otras tablas
        RETURN 'Error: No se puede eliminar porque el período está referenciado por otros registros.';
END;
$function$
;

-- DROP FUNCTION public.delete_reservacion(int8);

CREATE OR REPLACE FUNCTION public.delete_reservacion(p_id bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM reservacionalumnos WHERE IdReservacionAlumno = p_id;

    IF FOUND THEN
        RETURN 'Reservación eliminada exitosamente';
    ELSE
        RETURN 'Error: Reservación no encontrada';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_reservacion_maestro(int8);

CREATE OR REPLACE FUNCTION public.delete_reservacion_maestro(p_id bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM reservacionmaestros WHERE IdReservacionMaestro = p_id;

    IF FOUND THEN
        RETURN 'Reservación eliminada exitosamente';
    ELSE
        RETURN 'Error: Reservación no encontrada';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_role(int8);

CREATE OR REPLACE FUNCTION public.delete_role(p_id bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM roles WHERE IdRol = p_id;

    IF FOUND THEN
        RETURN 'Rol eliminado exitosamente';
    ELSE
        RETURN 'Error: Rol no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.delete_usuario(int8);

CREATE OR REPLACE FUNCTION public.delete_usuario(p_id bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM usuarios WHERE IdUsuario = p_id;

    IF FOUND THEN
        RETURN 'Usuario eliminado exitosamente';
    ELSE
        RETURN 'Error: Usuario no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.eliminar_comision(int8);

CREATE OR REPLACE FUNCTION public.eliminar_comision(p_id_comision bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    resultado JSON;
BEGIN
    -- Verifica existencia
    IF NOT EXISTS (SELECT 1 FROM comision WHERE id = p_id_comision) THEN
        RETURN json_build_object('status', 'error', 'message', 'Comisión no encontrada');
    END IF;

    -- Eliminar la comisión
    DELETE FROM comision WHERE id = p_id_comision;

    RETURN json_build_object('status', 'success', 'message', 'Comisión eliminada correctamente');
END;
$function$
;

-- DROP FUNCTION public.eliminar_competencia(int8);

CREATE OR REPLACE FUNCTION public.eliminar_competencia(p_id_competencia bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    resultado JSONB := '{}';
BEGIN
    IF NOT EXISTS (SELECT 1 FROM competencia WHERE "id_Competencia" = p_id_competencia) THEN
        RETURN jsonb_build_object(
            'success', false,
            'message', 'Competencia no encontrada'
        );
    END IF;

    DELETE FROM competencia WHERE "id_Competencia" = p_id_competencia;

    resultado := jsonb_build_object(
        'success', true,
        'message', 'Competencia eliminada correctamente',
        'id_Competencia', p_id_competencia
    );

    RETURN resultado;
EXCEPTION WHEN OTHERS THEN
    RETURN jsonb_build_object(
        'success', false,
        'message', 'Error al eliminar competencia: ' || SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.eliminar_conjunto_competencia_actividad(int8);

CREATE OR REPLACE FUNCTION public.eliminar_conjunto_competencia_actividad(p_relacion_id bigint)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
DECLARE
    act_id BIGINT;
    comp_id BIGINT;
BEGIN
    -- Verificar existencia
    IF NOT EXISTS (
        SELECT 1 FROM actividad_competencia WHERE "id_Act_Competencia" = p_relacion_id
    ) THEN
        RAISE EXCEPTION 'La relación con ID % no existe', p_relacion_id;
    END IF;

    -- Obtener IDs relacionados
    SELECT "Act_Aprendizaje_id", "Comp_Tema_id"
    INTO act_id, comp_id
    FROM actividad_competencia
    WHERE "id_Act_Competencia" = p_relacion_id;

    -- Eliminar la relación
    DELETE FROM actividad_competencia WHERE "id_Act_Competencia" = p_relacion_id;

    -- Eliminar registros dependientes
    DELETE FROM actividad_aprendizaje WHERE "id_Act_Aprendizaje" = act_id;
    DELETE FROM competencia_tema WHERE "id_Comp_Tema" = comp_id;
END;
$function$
;

-- DROP FUNCTION public.eliminar_departamento(int2);

CREATE OR REPLACE FUNCTION public.eliminar_departamento(p_id_departamento smallint)
 RETURNS boolean
 LANGUAGE plpgsql
AS $function$
BEGIN
    DELETE FROM departamentos
    WHERE id_departamento = p_id_departamento;
    
    RETURN FOUND;
END;
$function$
;

-- DROP FUNCTION public.eliminar_diseno_completo(int8);

CREATE OR REPLACE FUNCTION public.eliminar_diseno_completo(p_diseno_id bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    relaciones_eliminadas INT := 0;
BEGIN
    BEGIN
        -- 1. Verificar existencia del diseño
        IF NOT EXISTS (SELECT 1 FROM diseno_curricular WHERE "id_Dis_Curricular" = p_diseno_id) THEN
            RETURN jsonb_build_object(
                'success', false,
                'message', 'El diseño curricular no existe'
            );
        END IF;
        
        -- 2. Eliminar relaciones en diseno_participante
        DELETE FROM diseno_participante WHERE "Diseno_id" = p_diseno_id;
        GET DIAGNOSTICS relaciones_eliminadas = ROW_COUNT;
        
        -- 3. Eliminar el diseño curricular
        DELETE FROM diseno_curricular WHERE "id_Dis_Curricular" = p_diseno_id;
        
        -- Construir respuesta
        RETURN jsonb_build_object(
            'success', true,
            'message', 'Diseño eliminado completamente',
            'details', jsonb_build_object(
                'relaciones_eliminadas', relaciones_eliminadas,
                'participantes_eliminados', 0,
                'diseno_id', p_diseno_id
            )
        );
        
    EXCEPTION WHEN OTHERS THEN
        RETURN jsonb_build_object(
            'success', false,
            'message', 'Error al eliminar: ' || SQLERRM,
            'error_details', SQLSTATE
        );
    END;
END;
$function$
;

-- DROP FUNCTION public.eliminar_evaluacion(int8);

CREATE OR REPLACE FUNCTION public.eliminar_evaluacion(p_id_evaluacion bigint)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM evaluacion WHERE "id_Evaluacion" = p_id_evaluacion) THEN
        RAISE EXCEPTION 'Evaluación con ID % no existe', p_id_evaluacion;
    END IF;

    DELETE FROM evaluacion WHERE "id_Evaluacion" = p_id_evaluacion;
END;
$function$
;

-- DROP FUNCTION public.eliminar_fuente_informacion(int8);

CREATE OR REPLACE FUNCTION public.eliminar_fuente_informacion(p_id_fuente_info bigint)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM fuente_informacion WHERE "id_Fuente_Info" = p_id_fuente_info) THEN
        RAISE EXCEPTION 'Fuente de información con ID % no existe', p_id_fuente_info;
    END IF;

    DELETE FROM fuente_informacion WHERE "id_Fuente_Info" = p_id_fuente_info;
END;
$function$
;

-- DROP FUNCTION public.eliminar_horario_maestro(int4);

CREATE OR REPLACE FUNCTION public.eliminar_horario_maestro(p_id integer)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM horarios_maestros WHERE id_Horario_Maestro = p_id) THEN
        RETURN 'Error: El horario con ese ID no existe.';
    END IF;

    DELETE FROM horarios_maestros WHERE id_Horario_Maestro = p_id;

    RETURN 'Horario eliminado correctamente.';
END;
$function$
;

-- DROP FUNCTION public.eliminar_participante_diseno(int8, int8);

CREATE OR REPLACE FUNCTION public.eliminar_participante_diseno(p_diseno_id bigint, p_participante_id bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    eliminado BOOLEAN := FALSE;
BEGIN
    BEGIN
        -- Verificar existencia del diseño
        IF NOT EXISTS (SELECT 1 FROM diseno_curricular WHERE "id_Dis_Curricular" = p_diseno_id) THEN
            RETURN jsonb_build_object(
                'success', false,
                'message', 'El diseño curricular no existe'
            );
        END IF;
        
        -- Verificar existencia del participante
        IF NOT EXISTS (SELECT 1 FROM participante WHERE "id_Participante" = p_participante_id) THEN
            RETURN jsonb_build_object(
                'success', false,
                'message', 'El participante no existe'
            );
        END IF;
        
        -- Eliminar la relación entre diseño y participante
        DELETE FROM diseno_participante 
        WHERE "Diseno_id" = p_diseno_id AND "Participante_id" = p_participante_id
        RETURNING TRUE INTO eliminado;
        
        IF NOT eliminado THEN
            RETURN jsonb_build_object(
                'success', false,
                'message', 'El participante no estaba asociado a este diseño'
            );
        END IF;
        
        -- Respuesta exitosa
        RETURN jsonb_build_object(
            'success', true,
            'message', 'Participante eliminado del diseño',
            'details', jsonb_build_object(
                'diseno_id', p_diseno_id,
                'participante_id', p_participante_id,
                'accion', 'relacion_eliminada'
            )
        );
        
    EXCEPTION WHEN OTHERS THEN
        RETURN jsonb_build_object(
            'success', false,
            'message', 'Error al eliminar participante: ' || SQLERRM,
            'error_details', SQLSTATE
        );
    END;
END;
$function$
;

-- DROP FUNCTION public.eliminar_practica(int8);

CREATE OR REPLACE FUNCTION public.eliminar_practica(p_id_practica bigint)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    -- Verificar que la práctica exista
    IF NOT EXISTS (
        SELECT 1 FROM practica WHERE "id_Practica" = p_id_practica
    ) THEN
        RAISE EXCEPTION 'La práctica con ID % no existe', p_id_practica;
    END IF;

    -- Eliminar la práctica
    DELETE FROM practica
    WHERE "id_Practica" = p_id_practica;
END;
$function$
;

-- DROP FUNCTION public.eliminar_presentacion(int8);

CREATE OR REPLACE FUNCTION public.eliminar_presentacion(p_id_presentacion bigint)
 RETURNS boolean
 LANGUAGE plpgsql
AS $function$
BEGIN
    -- Verificar que la presentación exista
    IF NOT EXISTS (SELECT 1 FROM presentacion WHERE "id_Presentacion" = p_id_presentacion) THEN
        RETURN FALSE;
    END IF;
    
    -- Eliminar la presentación
    DELETE FROM presentacion
    WHERE "id_Presentacion" = p_id_presentacion;
    
    RETURN TRUE;
END;
$function$
;

-- DROP FUNCTION public.eliminar_proyecto_asignatura(int8);

CREATE OR REPLACE FUNCTION public.eliminar_proyecto_asignatura(p_id_proyecto_asig bigint)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM proyecto_asignatura WHERE "id_Proyecto_Asig" = p_id_proyecto_asig) THEN
        RAISE EXCEPTION 'Proyecto asignatura con ID % no existe', p_id_proyecto_asig;
    END IF;

    DELETE FROM proyecto_asignatura WHERE "id_Proyecto_Asig" = p_id_proyecto_asig;
END;
$function$
;

-- DROP FUNCTION public.eliminar_subtema(int8);

CREATE OR REPLACE FUNCTION public.eliminar_subtema(p_id_subtema bigint)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM subtema WHERE "id_Subtema" = p_id_subtema) THEN
        RAISE EXCEPTION 'Subtema con ID % no existe', p_id_subtema;
    END IF;

    DELETE FROM subtema WHERE "id_Subtema" = p_id_subtema;
END;
$function$
;

-- DROP FUNCTION public.eliminar_tema(int8);

CREATE OR REPLACE FUNCTION public.eliminar_tema(p_id_tema bigint)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
DECLARE
    comp_tema_id BIGINT;
BEGIN
    IF NOT EXISTS (SELECT 1 FROM tema WHERE "id_Tema" = p_id_tema) THEN
        RAISE EXCEPTION 'Tema con ID % no existe', p_id_tema;
    END IF;

    -- 1. Eliminar subtemas del tema
    DELETE FROM subtema WHERE "Tema_id" = p_id_tema;

    -- 2. Eliminar actividad_competencia relacionadas a competencia_tema
    FOR comp_tema_id IN
        SELECT "id_Comp_Tema" FROM competencia_tema WHERE "Tema_id" = p_id_tema
    LOOP
        DELETE FROM actividad_competencia WHERE "Comp_Tema_id" = comp_tema_id;
    END LOOP;

    -- 3. Eliminar competencias del tema
    DELETE FROM competencia_tema WHERE "Tema_id" = p_id_tema;

    -- 4. Eliminar actividades de aprendizaje del tema
    DELETE FROM actividad_aprendizaje WHERE "Tema_id" = p_id_tema;

    -- 5. Finalmente eliminar el tema
    DELETE FROM tema WHERE "id_Tema" = p_id_tema;
END;
$function$
;

-- DROP FUNCTION public.get_all_alumnos();

CREATE OR REPLACE FUNCTION public.get_all_alumnos()
 RETURNS TABLE(numerocontrol bigint, nombre character varying, apellidopaterno character varying, apellidomaterno character varying, idusuario bigint, clavecarrera character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM alumnos;
END;
$function$
;

-- DROP FUNCTION public.get_all_asignaturas();

CREATE OR REPLACE FUNCTION public.get_all_asignaturas()
 RETURNS TABLE(claveasignatura character varying, nombreasignatura character varying, creditos integer, satca_teoricas integer, satca_practicas integer, satca_total integer, clavecarrera character varying, semestre smallint, posicion smallint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        a."ClaveAsignatura", a."NombreAsignatura", a."Creditos",
        a."Satca_Teoricas", a."Satca_Practicas", a."Satca_Total",
        ac."Clave_Carrera", ac."Semestre", ac."Posicion"
    FROM asignatura a
    JOIN asignatura_carrera ac ON a."ClaveAsignatura" = ac."Clave_Asignatura";
END;
$function$
;

-- DROP FUNCTION public.get_all_aulas();

CREATE OR REPLACE FUNCTION public.get_all_aulas()
 RETURNS TABLE(claveaula character varying, claveedificio character varying, nombre character varying, cantidadcomputadoras integer, horadisponible time without time zone, estado estado_enum)
 LANGUAGE plpgsql
 STABLE
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        a.claveaula,
        a.claveedificio,
        a.nombre,
        a.cantidadcomputadoras,
        a.horadisponible,
        a.estado
    FROM aulas a
    ORDER BY a.claveaula;
END;
$function$
;

-- DROP FUNCTION public.get_all_bitacora_alumnos();

CREATE OR REPLACE FUNCTION public.get_all_bitacora_alumnos()
 RETURNS TABLE(id_bitacora_alumno bigint, hora_entrada timestamp without time zone, hora_salida timestamp without time zone, tiempo_uso interval, numerocontrol bigint, numeroinventario bigint)
 LANGUAGE plpgsql
 STABLE
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        b.id_bitacora_alumno,
        b.hora_entrada,
        b.hora_salida,
        b.tiempo_uso,
        b.numerocontrol,
        b.numeroinventario
    FROM bitacora_alumnos b
    ORDER BY b.hora_entrada DESC, b.id_bitacora_alumno DESC;
END;
$function$
;

-- DROP FUNCTION public.get_all_bitacora_maestros();

CREATE OR REPLACE FUNCTION public.get_all_bitacora_maestros()
 RETURNS TABLE(id_bitacora_maestro bigint, fechahora timestamp without time zone, tarjeta bigint, claveaula character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM bitacora_maestros;
END;
$function$
;

-- DROP FUNCTION public.get_all_carga_academica_detalles();

CREATE OR REPLACE FUNCTION public.get_all_carga_academica_detalles()
 RETURNS TABLE(idcargadetalle bigint, clavehorario bigint, idcargageneral bigint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM carga_academica_detalles;
END;
$function$
;

-- DROP FUNCTION public.get_all_carga_academica_general();

CREATE OR REPLACE FUNCTION public.get_all_carga_academica_general()
 RETURNS TABLE(idcargageneral bigint, numerocontrol bigint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM carga_academica_general;
END;
$function$
;

-- DROP FUNCTION public.get_all_carreras();

CREATE OR REPLACE FUNCTION public.get_all_carreras()
 RETURNS TABLE(clavecarrera character varying, nombre character varying, descripcion character varying, generacion integer)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM carreras;
END;
$function$
;

-- DROP FUNCTION public.get_all_computadoras();

CREATE OR REPLACE FUNCTION public.get_all_computadoras()
 RETURNS TABLE(numeroinventario bigint, claveaula character varying, marca character varying, estado character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM computadora;
END;
$function$
;

-- DROP FUNCTION public.get_all_departamentos();

CREATE OR REPLACE FUNCTION public.get_all_departamentos()
 RETURNS TABLE(id_departamento smallint, nombre character varying, abreviacion character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        d.id_departamento,
        d.nombre,
        d.abreviacion
    FROM departamentos
    ORDER BY id_departamento;
END;
$function$
;

-- DROP FUNCTION public.get_all_edificios();

CREATE OR REPLACE FUNCTION public.get_all_edificios()
 RETURNS TABLE(claveedificio character varying, nombre character varying, descripcion character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM edificios;
END;
$function$
;

-- DROP FUNCTION public.get_all_grupos();

CREATE OR REPLACE FUNCTION public.get_all_grupos()
 RETURNS TABLE(clavegrupo character varying, nombre character varying, descripcion character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM grupos;
END;
$function$
;

-- DROP FUNCTION public.get_all_horarios();

CREATE OR REPLACE FUNCTION public.get_all_horarios()
 RETURNS TABLE(clavehorario bigint, tarjeta bigint, claveaula character varying, clavegrupo character varying, claveasignatura character varying, lunes_hi time without time zone, lunes_hf time without time zone, idperiodoescolar bigint, martes_hi time without time zone, martes_hf time without time zone, miercoles_hi time without time zone, miercoles_hf time without time zone, jueves_hi time without time zone, jueves_hf time without time zone, viernes_hi time without time zone, viernes_hf time without time zone, sabado_hi time without time zone, sabado_hf time without time zone)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM horarioasignatura_maestro;
END;
$function$
;

-- DROP FUNCTION public.get_all_maestros();

CREATE OR REPLACE FUNCTION public.get_all_maestros()
 RETURNS TABLE(tarjeta bigint, nombre character varying, apellidopaterno character varying, apellidomaterno character varying, idusuario bigint, rfc character varying, escolaridad_licenciatura character varying, estado_licenciatura character varying, escolaridad_especializacion character varying, estado_especializacion character varying, escolaridad_maestria character varying, estado_maestria character varying, escolaridad_doctorado character varying, estado_doctorado character varying, id_departamento smallint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM maestros;
END;
$function$
;

-- DROP FUNCTION public.get_all_periodos_escolares();

CREATE OR REPLACE FUNCTION public.get_all_periodos_escolares()
 RETURNS TABLE(id_periodo_escolar bigint, codigoperiodo character varying, nombre_periodo character varying, fecha_inicio date, fecha_fin date, estado boolean, created_at timestamp without time zone, updated_at timestamp without time zone)
 LANGUAGE plpgsql
 STABLE
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        p.id_periodo_escolar,
        p.codigoperiodo,
        p.nombre_periodo,
        p.fecha_inicio,
        p.fecha_fin,
        p.estado,
        p.created_at,
        p.updated_at
    FROM periodo_escolar p
    ORDER BY p.fecha_inicio DESC, p.id_periodo_escolar DESC;
END;
$function$
;

-- DROP FUNCTION public.get_all_reservaciones();

CREATE OR REPLACE FUNCTION public.get_all_reservaciones()
 RETURNS TABLE(idreservacionalumno bigint, numerocontrol bigint, numeroinventario bigint, fechareservacion date, horainicio time without time zone, horafin time without time zone)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM reservacionalumnos;
END;
$function$
;

-- DROP FUNCTION public.get_all_reservaciones_maestro();

CREATE OR REPLACE FUNCTION public.get_all_reservaciones_maestro()
 RETURNS TABLE(idreservacionmaestro bigint, tarjeta bigint, claveaula character varying, fechareservacion date, horainicio time without time zone, horafin time without time zone)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM reservacionmaestros;
END;
$function$
;

-- DROP FUNCTION public.get_all_roles();

CREATE OR REPLACE FUNCTION public.get_all_roles()
 RETURNS TABLE(idrol bigint, nombre character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM roles;
END;
$function$
;

-- DROP FUNCTION public.get_all_usuarios();

CREATE OR REPLACE FUNCTION public.get_all_usuarios()
 RETURNS TABLE(idusuario bigint, correo character varying, token character varying, idrol bigint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT IdUsuario, Correo, Token, IdRol FROM usuarios;
END;
$function$
;

-- DROP FUNCTION public.get_alumno_by_id(int8);

CREATE OR REPLACE FUNCTION public.get_alumno_by_id(p_numero_control bigint)
 RETURNS TABLE(numerocontrol bigint, nombre character varying, apellidopaterno character varying, apellidomaterno character varying, idusuario bigint, clavecarrera character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM alumnos WHERE NumeroControl = p_numero_control;
END;
$function$
;

-- DROP FUNCTION public.get_asignatura_by_clave(varchar);

CREATE OR REPLACE FUNCTION public.get_asignatura_by_clave(p_clave_asignatura character varying)
 RETURNS TABLE(claveasignatura character varying, nombreasignatura character varying, creditos integer, satca_teoricas integer, satca_practicas integer, satca_total integer, clavecarrera character varying, semestre smallint, posicion smallint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        a."ClaveAsignatura", a."NombreAsignatura", a."Creditos",
        a."Satca_Teoricas", a."Satca_Practicas", a."Satca_Total",
        ac."Clave_Carrera", ac."Semestre", ac."Posicion"
    FROM asignatura a
    JOIN asignatura_carrera ac ON a."ClaveAsignatura" = ac."Clave_Asignatura"
    WHERE a."ClaveAsignatura" = p_clave_asignatura;
END;
$function$
;

-- DROP FUNCTION public.get_asignatura_by_clave_complete(text);

CREATE OR REPLACE FUNCTION public.get_asignatura_by_clave_complete(p_clave_asignatura text)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    result JSON;
BEGIN
    SELECT json_build_object(
        'informacionbasica', json_build_object(
            'clave', a."ClaveAsignatura",
            'nombre', a."NombreAsignatura",
            'creditos', a."Creditos",
            'satca', json_build_object(
                'teoricas', a."Satca_Teoricas",
                'practicas', a."Satca_Practicas",
                'total', a."Satca_Total"
            )
        ),
        'carreras', (
            SELECT json_agg(json_build_object(
                'clave', ac."Clave_Carrera",
                'nombre', c."nombre",
                'semestre', ac."Semestre",
                'posicion', ac."Posicion"
            ))
            FROM asignatura_carrera ac
            JOIN carreras c ON ac."Clave_Carrera" = c.clavecarrera
            WHERE ac."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'presentacion', (
            SELECT json_build_object(
                'id', p."id_Presentacion",
                'caracterizacion', p."Caracterizacion",
                'intencion_didactica', p."Intencion_didactica"
            )
            FROM presentacion p
            WHERE p."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'competencias', (
            SELECT json_agg(json_build_object(
                'id', c."id_Competencia",
                'tipo', c."Tipo_Competencia",
                'descripcion', c."Descripcion"
            ))
            FROM competencia c
            WHERE c."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'disenos_curriculares', (
            SELECT json_agg(json_build_object(
                'id', dc."id_Dis_Curricular",
                'fecha', dc."Fecha",
                'evento', dc."Evento",
                'participantes', (
                    SELECT json_agg(json_build_object(
                        'id', p."id_Participante",
                        'nombre', p."Nombre_Participante"
                    ))
                    FROM participante p
                    JOIN diseno_participante dp ON p."id_Participante" = dp."Participante_id"
                    WHERE dp."Diseno_id" = dc."id_Dis_Curricular"
                )
            ))
            FROM diseno_curricular dc
            WHERE dc."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'contenidos', (
            SELECT json_agg(json_build_object(
                'id', t."id_Tema",
                'numero', t."Numero",
                'nombre', t."Nombre_Tema",
                'subtemas', (
                    SELECT json_agg(json_build_object(
                        'id', s."id_Subtema",
                        'nombre', s."Nombre_Subtema"
                    ))
                    FROM subtema s
                    WHERE s."Tema_id" = t."id_Tema"
                )
            ) ORDER BY t."Numero")
            FROM tema t
            WHERE t."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'actividades', (
            SELECT json_agg(json_build_object(
                'tema_id', t."id_Tema",
                'actividades', (
                    SELECT json_agg(json_build_object(
                        'id', aa."id_Act_Aprendizaje",
                        'descripcion', aa."Descripcion_Act_Aprendizaje",
                        'competencias', (
                            SELECT json_agg(json_build_object(
                                'id', ct."id_Comp_Tema",
                                'descripcion', ct."Descripcion_Comp_Tema",
                                'tipo', ct."Tipo_Competencia",
                                'relacion_id', ac."id_Act_Competencia"
                            ))
                            FROM actividad_competencia ac
                            JOIN competencia_tema ct ON ac."Comp_Tema_id" = ct."id_Comp_Tema"
                            WHERE ac."Act_Aprendizaje_id" = aa."id_Act_Aprendizaje"
                        )
                    ))
                    FROM actividad_aprendizaje aa
                    WHERE aa."Tema_id" = t."id_Tema"
                )
            ))
            FROM tema t
            WHERE t."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'practicas', (
            SELECT json_agg(json_build_object(
                'id', p."id_Practica",
                'descripcion', p."Descripcion_Practica"
            ))
            FROM practica p
            WHERE p."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'proyectos', (
            SELECT json_agg(json_build_object(
                'id', pa."id_Proyecto_Asig",
                'descripcion', pa."Descripcion_Proyecto_Asig"
            ))
            FROM proyecto_asignatura pa
            WHERE pa."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'evaluacion', (
            SELECT json_agg(json_build_object(
                'id', e."id_Evaluacion",
                'criterios', e."Criterios_Evaluacion"
            ))
            FROM evaluacion e
            WHERE e."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'fuentes', (
            SELECT json_agg(json_build_object(
                'id', f."id_Fuente_Info",
                'referencia', f."Referencia_Fuente_Info"
            ))
            FROM fuente_informacion f
            WHERE f."Clave_Asignatura" = a."ClaveAsignatura"
        ),
        'aulas_grupos_periodos', (
    SELECT json_agg(json_build_object(
        'aula', ham.claveaula,
        'grupo', ham.clavegrupo,
        'periodo', pe.codigoperiodo
    ))
    FROM horarioasignatura_maestro ham
    JOIN periodo_escolar pe ON pe.id_periodo_escolar = ham.idperiodoescolar
    WHERE ham.claveasignatura = a."ClaveAsignatura"
)



    ) INTO result
    FROM asignatura a
    WHERE a."ClaveAsignatura" = p_clave_asignatura;
    
    RETURN result;
END;
$function$
;

-- DROP FUNCTION public.get_asignaturas_by_carrera(varchar);

CREATE OR REPLACE FUNCTION public.get_asignaturas_by_carrera(p_clave_carrera character varying)
 RETURNS TABLE(claveasignatura character varying, nombreasignatura character varying, creditos integer, satca_teoricas integer, satca_practicas integer, satca_total integer, semestre smallint, posicion smallint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        a."ClaveAsignatura", a."NombreAsignatura", a."Creditos",
        a."Satca_Teoricas", a."Satca_Practicas", a."Satca_Total",
        ac."Semestre", ac."Posicion"
    FROM asignatura a
    INNER JOIN asignatura_carrera ac ON a."ClaveAsignatura" = ac."Clave_Asignatura"
    WHERE ac."Clave_Carrera" = p_clave_carrera
    ORDER BY ac."Semestre", ac."Posicion";
END;
$function$
;

-- DROP FUNCTION public.get_asignaturas_by_carrera_and_semestre(varchar, int2);

CREATE OR REPLACE FUNCTION public.get_asignaturas_by_carrera_and_semestre(p_clave_carrera character varying, p_semestre smallint)
 RETURNS TABLE(claveasignatura character varying, nombreasignatura character varying, creditos integer, satca_teoricas integer, satca_practicas integer, satca_total integer, posicion smallint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        a."ClaveAsignatura", a."NombreAsignatura", a."Creditos",
        a."Satca_Teoricas", a."Satca_Practicas", a."Satca_Total",
        ac."Posicion"
    FROM asignatura a
    INNER JOIN asignatura_carrera ac ON a."ClaveAsignatura" = ac."Clave_Asignatura"
    WHERE ac."Clave_Carrera" = p_clave_carrera
      AND ac."Semestre" = p_semestre
    ORDER BY ac."Posicion";
END;
$function$
;

-- DROP FUNCTION public.get_asignaturas_by_tarjeta_complete(int8);

CREATE OR REPLACE FUNCTION public.get_asignaturas_by_tarjeta_complete(p_tarjeta bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    result JSON;
BEGIN
    SELECT json_agg(asignatura_info) INTO result
    FROM (
        SELECT json_build_object(
            'informacionbasica', json_build_object(
                'clave', a."ClaveAsignatura",
                'nombre', a."NombreAsignatura",
                'creditos', a."Creditos",
                'satca', json_build_object(
                    'teoricas', a."Satca_Teoricas",
                    'practicas', a."Satca_Practicas",
                    'total', a."Satca_Total"
                )
            ),
            'carreras', (
                SELECT json_agg(json_build_object(
                    'clave', ac."Clave_Carrera",
                    'nombre', c."nombre",
                    'semestre', ac."Semestre",
                    'posicion', ac."Posicion"
                ))
                FROM asignatura_carrera ac
                JOIN carreras c ON ac."Clave_Carrera" = c.clavecarrera
                WHERE ac."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'presentacion', (
                SELECT json_build_object(
                    'id', p."id_Presentacion",
                    'caracterizacion', p."Caracterizacion",
                    'intencion_didactica', p."Intencion_didactica"
                )
                FROM presentacion p
                WHERE p."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'competencias', (
                SELECT json_agg(json_build_object(
                    'id', c."id_Competencia",
                    'tipo', c."Tipo_Competencia",
                    'descripcion', c."Descripcion"
                ))
                FROM competencia c
                WHERE c."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'disenos_curriculares', (
                SELECT json_agg(json_build_object(
                    'id', dc."id_Dis_Curricular",
                    'fecha', dc."Fecha",
                    'evento', dc."Evento",
                    'participantes', (
                        SELECT json_agg(json_build_object(
                            'id', p."id_Participante",
                            'nombre', p."Nombre_Participante"
                        ))
                        FROM participante p
                        JOIN diseno_participante dp ON p."id_Participante" = dp."Participante_id"
                        WHERE dp."Diseno_id" = dc."id_Dis_Curricular"
                    )
                ))
                FROM diseno_curricular dc
                WHERE dc."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'contenidos', (
                SELECT json_agg(json_build_object(
                    'id', t."id_Tema",
                    'numero', t."Numero",
                    'nombre', t."Nombre_Tema",
                    'subtemas', (
                        SELECT json_agg(json_build_object(
                            'id', s."id_Subtema",
                            'nombre', s."Nombre_Subtema"
                        ))
                        FROM subtema s
                        WHERE s."Tema_id" = t."id_Tema"
                    )
                ) ORDER BY t."Numero")
                FROM tema t
                WHERE t."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'actividades', (
                SELECT json_agg(json_build_object(
                    'tema_id', t."id_Tema",
                    'actividades', (
                        SELECT json_agg(json_build_object(
                            'id', aa."id_Act_Aprendizaje",
                            'descripcion', aa."Descripcion_Act_Aprendizaje",
                            'competencias', (
                                SELECT json_agg(json_build_object(
                                    'id', ct."id_Comp_Tema",
                                    'descripcion', ct."Descripcion_Comp_Tema",
                                    'tipo', ct."Tipo_Competencia",
                                    'relacion_id', ac."id_Act_Competencia"
                                ))
                                FROM actividad_competencia ac
                                JOIN competencia_tema ct ON ac."Comp_Tema_id" = ct."id_Comp_Tema"
                                WHERE ac."Act_Aprendizaje_id" = aa."id_Act_Aprendizaje"
                            )
                        ))
                        FROM actividad_aprendizaje aa
                        WHERE aa."Tema_id" = t."id_Tema"
                    )
                ))
                FROM tema t
                WHERE t."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'practicas', (
                SELECT json_agg(json_build_object(
                    'id', p."id_Practica",
                    'descripcion', p."Descripcion_Practica"
                ))
                FROM practica p
                WHERE p."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'proyectos', (
                SELECT json_agg(json_build_object(
                    'id', pa."id_Proyecto_Asig",
                    'descripcion', pa."Descripcion_Proyecto_Asig"
                ))
                FROM proyecto_asignatura pa
                WHERE pa."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'evaluacion', (
                SELECT json_agg(json_build_object(
                    'id', e."id_Evaluacion",
                    'criterios', e."Criterios_Evaluacion"
                ))
                FROM evaluacion e
                WHERE e."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'fuentes', (
                SELECT json_agg(json_build_object(
                    'id', f."id_Fuente_Info",
                    'referencia', f."Referencia_Fuente_Info"
                ))
                FROM fuente_informacion f
                WHERE f."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'aulas_grupos_periodos', (
                SELECT json_agg(json_build_object(
                    'aula', ham.claveaula,
                    'grupo', ham.clavegrupo,
                    'periodo', pe.codigoperiodo
                ))
                FROM horarioasignatura_maestro ham
                JOIN periodo_escolar pe ON pe.id_periodo_escolar = ham.idperiodoescolar
                WHERE ham.claveasignatura = a."ClaveAsignatura"
                  AND ham.tarjeta = p_tarjeta
            )
        ) AS asignatura_info
        FROM asignatura a
        WHERE a."ClaveAsignatura" IN (
            SELECT DISTINCT claveasignatura
            FROM horarioasignatura_maestro
            WHERE tarjeta = p_tarjeta
        )
    ) sub;

    RETURN result;
END;
$function$
;

-- DROP FUNCTION public.get_aula_by_id(varchar);

CREATE OR REPLACE FUNCTION public.get_aula_by_id(p_clave_aula character varying)
 RETURNS TABLE(claveaula character varying, claveedificio character varying, nombre character varying, cantidadcomputadoras integer, horadisponible time without time zone, estado estado_enum)
 LANGUAGE plpgsql
 STABLE
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        a.claveaula,
        a.claveedificio,
        a.nombre,
        a.cantidadcomputadoras,
        a.horadisponible,
        a.estado
    FROM aulas a
    WHERE a.claveaula = p_clave_aula;
END;
$function$
;

-- DROP FUNCTION public.get_bitacora_by_numero_control(int8);

CREATE OR REPLACE FUNCTION public.get_bitacora_by_numero_control(p_numero_control bigint)
 RETURNS TABLE(id_bitacora_alumno bigint, hora_entrada timestamp without time zone, hora_salida timestamp without time zone, tiempo_uso interval, numerocontrol bigint, numeroinventario bigint)
 LANGUAGE plpgsql
 STABLE
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        b.id_bitacora_alumno,
        b.hora_entrada,
        b.hora_salida,
        b.tiempo_uso,
        b.numerocontrol,
        b.numeroinventario
    FROM bitacora_alumnos b
    WHERE b.numerocontrol = p_numero_control
    ORDER BY b.hora_entrada DESC, b.id_bitacora_alumno DESC;
END;
$function$
;

-- DROP FUNCTION public.get_bitacora_by_tarjeta(int8);

CREATE OR REPLACE FUNCTION public.get_bitacora_by_tarjeta(p_tarjeta bigint)
 RETURNS TABLE(id_bitacora_maestro bigint, fechahora timestamp without time zone, tarjeta bigint, claveaula character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM bitacora_maestros WHERE Tarjeta = p_tarjeta;
END;
$function$
;

-- DROP FUNCTION public.get_carga_academica_detalle_by_id(int8);

CREATE OR REPLACE FUNCTION public.get_carga_academica_detalle_by_id(p_id_carga_detalle bigint)
 RETURNS TABLE(idcargadetalle bigint, clavehorario bigint, idcargageneral bigint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM carga_academica_detalles WHERE IdCargaDetalle = p_id_carga_detalle;
END;
$function$
;

-- DROP FUNCTION public.get_carga_academica_general_by_id(int8);

CREATE OR REPLACE FUNCTION public.get_carga_academica_general_by_id(p_id_carga_general bigint)
 RETURNS TABLE(idcargageneral bigint, numerocontrol bigint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM carga_academica_general WHERE IdCargaGeneral = p_id_carga_general;
END;
$function$
;

-- DROP FUNCTION public.get_carrera_by_clave(varchar);

CREATE OR REPLACE FUNCTION public.get_carrera_by_clave(p_clave_carrera character varying)
 RETURNS TABLE(clavecarrera character varying, nombre character varying, descripcion character varying, generacion integer)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM carreras WHERE ClaveCarrera = p_clave_carrera;
END;
$function$
;

-- DROP FUNCTION public.get_comisiones_by_maestro(int8);

CREATE OR REPLACE FUNCTION public.get_comisiones_by_maestro(p_tarjeta bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    result json;
BEGIN
    SELECT COALESCE(json_agg(com_info), '[]'::json) INTO result
    FROM (
        SELECT 
            c.id_comision,
            c.folio,
            c.nombre_evento,
            c.tipo_evento,
            c.estado,
            c.lugar,
            c.motivo,
            c.tipo_comision,
            c.permiso_constancia,
            c.id_tipo_evento,
            c.id_periodo_escolar,
            c.created_at,
            c.updated_at
        FROM public.comision c
        JOIN public.comision_maestro cm
          ON cm.id_comision = c.id_comision
        WHERE cm.tarjeta_maestro = p_tarjeta
        ORDER BY c.id_comision DESC
    ) AS com_info;

    RETURN result;
END;
$function$
;

-- DROP FUNCTION public.get_computadora_by_inventario(int8);

CREATE OR REPLACE FUNCTION public.get_computadora_by_inventario(p_numero_inventario bigint)
 RETURNS TABLE(numeroinventario bigint, claveaula character varying, marca character varying, estado character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM computadora WHERE NumeroInventario = p_numero_inventario;
END;
$function$
;

-- DROP FUNCTION public.get_datos_asignatura_maestro(int8);

CREATE OR REPLACE FUNCTION public.get_datos_asignatura_maestro(tarjeta_maestro bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    resultado JSON;
BEGIN
    SELECT json_build_object(
        'maestro', json_build_object(
            'tarjeta', m.tarjeta,
            'nombre_completo', m.nombre || ' ' || m.apellidopaterno || ' ' || m.apellidomaterno,
            'departamento', m.id_departamento
        ),
        'horarios', (
            SELECT json_agg(json_build_object(
                'clave_horario', hm.clavehorario,
                'aula', json_build_object(
                    'clave_aula', a.claveaula,
                    'nombre_aula', a.nombre
                ),
                'grupo', json_build_object(
                    'clave_grupo', g.clavegrupo
                ),
                'asignatura', json_build_object(
                    'clave_asignatura', asig."ClaveAsignatura",
                    'nombre_asignatura', asig."NombreAsignatura",
                    'creditos', asig."Creditos",
                    'satca_practicas', asig."Satca_Practicas",
                    'satca_teoricas', asig."Satca_Teoricas",
                    'satca_total', asig."Satca_Total",
                    'presentacion', (
                        SELECT json_build_object(
                            'caracterizacion', p."Caracterizacion"
                        )
                        FROM presentacion p
                        WHERE p."Clave_Asignatura" = asig."ClaveAsignatura"
                        LIMIT 1
                    ),
                    'temas', (
                        SELECT json_agg(json_build_object(
                            'numero', t."Numero",
                            'nombre_tema', t."Nombre_Tema",
                            'subtemas', (
                                SELECT json_agg(json_build_object(
                                    'nombre_subtema', st."Nombre_Subtema"
                                ))
                                FROM subtema st
                                WHERE st."Tema_id" = t."id_Tema"
                            )
                        ))
                        FROM tema t
                        WHERE t."Clave_Asignatura" = asig."ClaveAsignatura"
                    )
                )
            ))
            FROM horarioasignatura_maestro hm
            JOIN aulas a ON hm.claveaula = a.claveaula
            JOIN grupos g ON hm.clavegrupo = g.clavegrupo
            JOIN asignatura asig ON hm.claveasignatura = asig."ClaveAsignatura"
            WHERE hm.tarjeta = tarjeta_maestro
        )
    ) INTO resultado
    FROM maestros m
    WHERE m.tarjeta = tarjeta_maestro;

    RETURN resultado;
END;
$function$
;

-- DROP FUNCTION public.get_detalle_grupos__para_calificaciones_by_tarjeta(int8);

CREATE OR REPLACE FUNCTION public.get_detalle_grupos__para_calificaciones_by_tarjeta(p_tarjeta bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    result JSON;
BEGIN
    SELECT json_agg(asignatura_info) INTO result
    FROM (
        SELECT json_build_object(
            'informacionbasica', json_build_object(
                'clave', a."ClaveAsignatura",
                'nombre', a."NombreAsignatura",
                'creditos', a."Creditos",
                'satca', json_build_object(
                    'teoricas', a."Satca_Teoricas",
                    'practicas', a."Satca_Practicas",
                    'total', a."Satca_Total"
                )
            ),
            'carreras', (
                SELECT json_agg(json_build_object(
                    'clave', ac."Clave_Carrera",
                    'nombre', c."nombre",
                    'semestre', ac."Semestre",
                    'posicion', ac."Posicion"
                ))
                FROM asignatura_carrera ac
                JOIN carreras c ON ac."Clave_Carrera" = c.clavecarrera
                WHERE ac."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'contenidos', (
                SELECT json_agg(json_build_object(
                    'id', t."id_Tema",
                    'numero', t."Numero",
                    'nombre', t."Nombre_Tema",
                    'subtemas', (
                        SELECT json_agg(json_build_object(
                            'id', s."id_Subtema",
                            'nombre', s."Nombre_Subtema"
                        ))
                        FROM subtema s
                        WHERE s."Tema_id" = t."id_Tema"
                    )
                ) ORDER BY t."Numero")
                FROM tema t
                WHERE t."Clave_Asignatura" = a."ClaveAsignatura"
            ),
            'aulas_grupos_periodos', (
                SELECT json_agg(json_build_object(
                    'aula', ham.claveaula,
                    'grupo', ham.clavegrupo,
                    'periodo', pe.codigoperiodo,
                    -- Modifica esta parte dentro del json_build_object de 'aulas_grupos_periodos':
'alumnos', (
    SELECT json_agg(json_build_object(
        'numero_control', al.NumeroControl,
        'nombre_completo', al.Nombre || ' ' || al.ApellidoPaterno || ' ' || al.ApellidoMaterno,
        'carrera', car.nombre,
        'id_carga_detalle', cad.IdCargaDetalle  -- ¡Nuevo campo crítico!
    ))
    FROM alumnos al
    JOIN carga_academica_general cag ON al.NumeroControl = cag.NumeroControl
    JOIN carga_academica_detalles cad ON cag.IdCargaGeneral = cad.IdCargaGeneral
    JOIN carreras car ON al.ClaveCarrera = car.clavecarrera
    WHERE cad.ClaveHorario = ham.ClaveHorario
)
                ))
                FROM horarioasignatura_maestro ham
                JOIN periodo_escolar pe ON pe.id_periodo_escolar = ham.idperiodoescolar
                WHERE ham.claveasignatura = a."ClaveAsignatura"
                  AND ham.tarjeta = p_tarjeta
            )
        ) AS asignatura_info
        FROM asignatura a
        WHERE a."ClaveAsignatura" IN (
            SELECT DISTINCT claveasignatura
            FROM horarioasignatura_maestro
            WHERE tarjeta = p_tarjeta
        )
    ) sub;

    RETURN result;
END;
$function$
;

-- DROP FUNCTION public.get_detalle_grupos_calificacion_por_carrera(int8);

CREATE OR REPLACE FUNCTION public.get_detalle_grupos_calificacion_por_carrera(p_tarjeta bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    result JSON;
BEGIN
    SELECT json_agg(asignatura_info) INTO result
    FROM (
        SELECT json_build_object(
            'informacionbasica', json_build_object(
                'clave', a."ClaveAsignatura",
                'nombre', a."NombreAsignatura",
                'creditos', a."Creditos"
            ),
            'aulas_grupos_periodos', (
                SELECT json_agg(json_build_object(
                    'aula', ham.claveaula,
                    'grupo', ham.clavegrupo,
                    'periodo', pe.codigoperiodo,
                    'carreras', (
                        SELECT json_agg(json_build_object(
                            'clave_carrera', car.clavecarrera,
                            'nombre_carrera', car.nombre,
                            'alumnos', (
                                SELECT json_agg(json_build_object(
                                    'numero_control', al.NumeroControl,
                                    'nombre_completo', al.Nombre || ' ' || al.ApellidoPaterno || ' ' || al.ApellidoMaterno,
                                    'id_carga_detalle', cad.IdCargaDetalle,
                                    'calificaciones', (
                                        SELECT json_agg(json_build_object(
                                            'unidad', cu.Unidad,
                                            'tipo_evaluacion', cu.TipoEvaluacion,
                                            'calificacion', cu.Calificacion,
                                            'ponderacion', cu.Ponderacion,
                                            'tipo_acreditacion', cu.TipoAcreditacion,
                                            'acreditado', cu.Acreditado,
                                            'fecha_registro', cu.FechaRegistro
                                        ))
                                        FROM calificaciones_unidades cu
                                        WHERE cu.IdCargaDetalle = cad.IdCargaDetalle
                                    )
                                ))
                                FROM alumnos al
                                JOIN carga_academica_general cag ON al.NumeroControl = cag.NumeroControl
                                JOIN carga_academica_detalles cad ON cag.IdCargaGeneral = cad.IdCargaGeneral
                                WHERE cad.ClaveHorario = ham.ClaveHorario
                                AND al.ClaveCarrera = car.clavecarrera
                            )
                        ))
                        FROM carreras car
                        WHERE car.clavecarrera IN (
                            SELECT DISTINCT al.ClaveCarrera
                            FROM alumnos al
                            JOIN carga_academica_general cag ON al.NumeroControl = cag.NumeroControl
                            JOIN carga_academica_detalles cad ON cag.IdCargaGeneral = cad.IdCargaGeneral
                            WHERE cad.ClaveHorario = ham.ClaveHorario
                        )
                    )
                ))
                FROM horarioasignatura_maestro ham
                JOIN periodo_escolar pe ON pe.id_periodo_escolar = ham.idperiodoescolar
                WHERE ham.claveasignatura = a."ClaveAsignatura"
                  AND ham.tarjeta = p_tarjeta
            )
        ) AS asignatura_info
        FROM asignatura a
        WHERE a."ClaveAsignatura" IN (
            SELECT DISTINCT claveasignatura
            FROM horarioasignatura_maestro
            WHERE tarjeta = p_tarjeta
        )
    ) sub;

    RETURN result;
END;
$function$
;

-- DROP FUNCTION public.get_edificio_by_clave(varchar);

CREATE OR REPLACE FUNCTION public.get_edificio_by_clave(p_clave_edificio character varying)
 RETURNS TABLE(claveedificio character varying, nombre character varying, descripcion character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM edificios WHERE ClaveEdificio = p_clave_edificio;
END;
$function$
;

-- DROP FUNCTION public.get_grupo_by_clave(varchar);

CREATE OR REPLACE FUNCTION public.get_grupo_by_clave(p_clave_grupo character varying)
 RETURNS TABLE(clavegrupo character varying, nombre character varying, descripcion character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM grupos WHERE ClaveGrupo = p_clave_grupo;
END;
$function$
;

-- DROP FUNCTION public.get_horario_alumno(int8);

CREATE OR REPLACE FUNCTION public.get_horario_alumno(p_numero_control bigint)
 RETURNS jsonb
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_horario jsonb;
BEGIN
    -- Retrieve and return the student's schedule
    SELECT jsonb_agg(
        jsonb_strip_nulls( -- Remove NULL values
            jsonb_build_object(
                'Asignatura', m."NombreAsignatura",
                'Aula', au.Nombre,
                'Profesor',  COALESCE(mtros.Nombre, '') || ' ' || 
                            COALESCE(mtros.apellidopaterno, '') || ' ' || 
                            COALESCE(mtros.apellidomaterno, ''),
                'Inicio Lunes', hm.lunes_hi,
                'Fin Lunes', hm.lunes_hf,
                'Inicio Martes', hm.martes_hi,
                'Fin Martes', hm.martes_hf,
                'Inicio Miercoles', hm.miercoles_hi,
                'Fin Miercoles', hm.miercoles_hf,
                'Inicio Jueves', hm.jueves_hi,
                'Fin Jueves', hm.jueves_hf,
                'Inicio Viernes', hm.viernes_hi,
                'Fin Viernes', hm.viernes_hf,
                'Inicio Sabado', hm.sabado_hi,
                'Fin Sabado', hm.sabado_hf
            )
        )
    ) INTO v_horario
    FROM Carga_Academica_General cag
    JOIN Carga_Academica_Detalles cad ON cag.idcargageneral = cad.idcargageneral
    JOIN HorarioAsignatura_Maestro hm ON cad.ClaveHorario = hm.ClaveHorario
    JOIN Asignatura m ON hm.ClaveAsignatura = m."ClaveAsignatura"
    JOIN Aulas au ON hm.ClaveAula = au.ClaveAula
    JOIN Maestros mtros ON hm.tarjeta = mtros.tarjeta
    WHERE cag.numerocontrol = p_numero_control;

    RETURN v_horario;
END;
$function$
;

-- DROP FUNCTION public.get_horario_by_clave(int8);

CREATE OR REPLACE FUNCTION public.get_horario_by_clave(p_clave_horario bigint)
 RETURNS TABLE(clavehorario bigint, tarjeta bigint, claveaula character varying, clavegrupo character varying, claveasignatura character varying, lunes_hi time without time zone, lunes_hf time without time zone, idperiodoescolar bigint, martes_hi time without time zone, martes_hf time without time zone, miercoles_hi time without time zone, miercoles_hf time without time zone, jueves_hi time without time zone, jueves_hf time without time zone, viernes_hi time without time zone, viernes_hf time without time zone, sabado_hi time without time zone, sabado_hf time without time zone)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM horarioasignatura_maestro WHERE ClaveHorario = p_clave_horario;
END;
$function$
;

-- DROP FUNCTION public.get_horario_maestro(int4);

CREATE OR REPLACE FUNCTION public.get_horario_maestro(p_tarjeta integer)
 RETURNS jsonb
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_horario jsonb;
BEGIN
    -- Retrieve and return the teacher's schedule
    SELECT jsonb_agg(
		jsonb_strip_nulls( -- Remove NULL values
            jsonb_build_object(
		        'Asignatura', a."NombreAsignatura",
		        'Aula', au.Nombre,
		        'Inicio Lunes', hm.lunes_hi,
				'Fin Lunes', hm.lunes_hf,
				'Inicio Martes', hm.martes_hi,
				'Fin Martes', hm.martes_hf,
				'Inicio Miercoles', hm.miercoles_hi,
				'Fin Miercoles', hm.miercoles_hf,
				'Inicio Jueves', hm.jueves_hi,
				'Fin Jueves', hm.jueves_hf,
				'Inicio Viernes', hm.viernes_hi,
				'Fin Viernes', hm.viernes_hf,
				'Inicio Sabado', hm.sabado_hi,
				'Fin Sabado', hm.sabado_hf
    		)
		)
	) INTO v_horario
    FROM HorarioAsignatura_Maestro hm
    JOIN Asignatura a ON hm.ClaveAsignatura = a."ClaveAsignatura"
	JOIN Aulas au ON hm.ClaveAula = au.ClaveAula
    WHERE hm.Tarjeta = p_tarjeta;

    RETURN v_horario;
END;
$function$
;

-- DROP FUNCTION public.get_maestro_by_idusuario(int8);

CREATE OR REPLACE FUNCTION public.get_maestro_by_idusuario(p_idusuario bigint)
 RETURNS TABLE(tarjeta bigint, nombre character varying, apellidopaterno character varying, apellidomaterno character varying, idusuario bigint, rfc character varying, escolaridad_licenciatura character varying, estado_licenciatura character varying, escolaridad_especializacion character varying, estado_especializacion character varying, escolaridad_maestria character varying, estado_maestria character varying, escolaridad_doctorado character varying, estado_doctorado character varying, id_departamento smallint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM maestros WHERE maestros.IdUsuario = p_idusuario;
END;
$function$
;

-- DROP FUNCTION public.get_maestro_by_tarjeta(int8);

CREATE OR REPLACE FUNCTION public.get_maestro_by_tarjeta(p_tarjeta bigint)
 RETURNS TABLE(tarjeta bigint, nombre character varying, apellidopaterno character varying, apellidomaterno character varying, idusuario bigint, rfc character varying, escolaridad_licenciatura character varying, estado_licenciatura character varying, escolaridad_especializacion character varying, estado_especializacion character varying, escolaridad_maestria character varying, estado_maestria character varying, escolaridad_doctorado character varying, estado_doctorado character varying, id_departamento smallint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM maestros WHERE Tarjeta = p_tarjeta;
END;
$function$
;

-- DROP FUNCTION public.get_periodo_escolar_by_id(int8);

CREATE OR REPLACE FUNCTION public.get_periodo_escolar_by_id(p_id bigint)
 RETURNS TABLE(id_periodo_escolar bigint, codigoperiodo character varying, nombre_periodo character varying, fecha_inicio date, fecha_fin date, estado boolean, created_at timestamp without time zone, updated_at timestamp without time zone)
 LANGUAGE plpgsql
 STABLE
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        p.id_periodo_escolar,
        p.codigoperiodo,
        p.nombre_periodo,
        p.fecha_inicio,
        p.fecha_fin,
        p.estado,
        p.created_at,
        p.updated_at
    FROM periodo_escolar p
    WHERE p.id_periodo_escolar = p_id;
END;
$function$
;

-- DROP FUNCTION public.get_reservacion_by_id(int8);

CREATE OR REPLACE FUNCTION public.get_reservacion_by_id(p_id bigint)
 RETURNS TABLE(idreservacionalumno bigint, numerocontrol bigint, numeroinventario bigint, fechareservacion date, horainicio time without time zone, horafin time without time zone)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM reservacionalumnos WHERE IdReservacionAlumno = p_id;
END;
$function$
;

-- DROP FUNCTION public.get_reservacion_maestro_by_id(int8);

CREATE OR REPLACE FUNCTION public.get_reservacion_maestro_by_id(p_id bigint)
 RETURNS TABLE(idreservacionmaestro bigint, tarjeta bigint, claveaula character varying, fechareservacion date, horainicio time without time zone, horafin time without time zone)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM reservacionmaestros WHERE IdReservacionMaestro = p_id;
END;
$function$
;

-- DROP FUNCTION public.get_role_by_id(int8);

CREATE OR REPLACE FUNCTION public.get_role_by_id(p_id bigint)
 RETURNS TABLE(idrol bigint, nombre character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT * FROM roles WHERE IdRol = p_id;
END;
$function$
;

-- DROP FUNCTION public.get_usuario_by_id(int8);

CREATE OR REPLACE FUNCTION public.get_usuario_by_id(p_id bigint)
 RETURNS TABLE(idusuario bigint, correo character varying, token character varying, idrol bigint)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY SELECT IdUsuario, Correo, Token, IdRol FROM usuarios WHERE IdUsuario = p_id;
END;
$function$
;

-- DROP FUNCTION public.insert_alumno(int8, varchar, varchar, varchar, int8, varchar);

CREATE OR REPLACE FUNCTION public.insert_alumno(p_numero_control bigint, p_nombre character varying, p_apellido_paterno character varying, p_apellido_materno character varying, p_id_usuario bigint, p_clave_carrera character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    INSERT INTO alumnos (NumeroControl, Nombre, ApellidoPaterno, ApellidoMaterno, IdUsuario, ClaveCarrera)
    VALUES (p_numero_control, p_nombre, p_apellido_paterno, p_apellido_materno, p_id_usuario, p_clave_carrera);
    
    RETURN 'Alumno registrado exitosamente';
EXCEPTION 
    WHEN unique_violation THEN
        RETURN 'Error: NumeroControl ya existe';
    WHEN foreign_key_violation THEN
        RETURN 'Error: IdUsuario o ClaveCarrera no existen';
    WHEN others THEN
        RETURN 'Error desconocido al insertar el alumno';
END;
$function$
;

-- DROP FUNCTION public.insert_asignatura_carrera(varchar, varchar, varchar, int4, int4, int4, int2, int2);

CREATE OR REPLACE FUNCTION public.insert_asignatura_carrera(p_clave_asignatura character varying, p_clave_carrera character varying, p_nombre character varying, p_creditos integer, p_horas_teoricas integer, p_horas_practicas integer, p_semestre smallint, p_posicion smallint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Verificar si la asignatura ya existe
    SELECT EXISTS(SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) INTO v_exists;

    -- Si no existe, insertarla
    IF NOT v_exists THEN
        INSERT INTO asignatura (
            "ClaveAsignatura", "NombreAsignatura", "Creditos",
            "Satca_Teoricas", "Satca_Practicas", "Satca_Total"
        ) VALUES (
            p_clave_asignatura, p_nombre, p_creditos,
            p_horas_teoricas, p_horas_practicas, p_horas_teoricas + p_horas_practicas
        );
    END IF;

    -- Insertar en asignatura_carrera
    INSERT INTO asignatura_carrera (
        "idAsig_Carrera", "Clave_Asignatura", "Clave_Carrera", "Semestre", "Posicion"
    ) VALUES (
        DEFAULT, p_clave_asignatura, p_clave_carrera, p_semestre, p_posicion
    );

    RETURN 'Asignatura registrada exitosamente.';
END;
$function$
;

-- DROP FUNCTION public.insert_aula(varchar, varchar, varchar, int4, time, estado_enum);

CREATE OR REPLACE FUNCTION public.insert_aula(p_clave_aula character varying, p_clave_edificio character varying, p_nombre character varying, p_cantidad_computadoras integer, p_hora_disponible time without time zone, p_estado estado_enum DEFAULT 'activo'::estado_enum)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    INSERT INTO aulas (
        claveaula, claveedificio, nombre, cantidadcomputadoras, horadisponible, estado
    ) VALUES (
        p_clave_aula, p_clave_edificio, p_nombre, p_cantidad_computadoras, p_hora_disponible,
        COALESCE(p_estado, 'activo')
    );

    RETURN 'Aula creada exitosamente';

EXCEPTION
    WHEN unique_violation THEN
        RETURN 'Error: ClaveAula ya existe';
    WHEN foreign_key_violation THEN
        RETURN 'Error: ClaveEdificio no existe';
    WHEN check_violation THEN
        RETURN 'Error: Violación de restricción CHECK';
    WHEN others THEN
        RETURN 'Error desconocido al insertar el aula';
END;
$function$
;

-- DROP FUNCTION public.insert_bitacora_alumno(int8, int8);

CREATE OR REPLACE FUNCTION public.insert_bitacora_alumno(p_numero_control bigint, p_numero_inventario bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_alumno_exists BOOLEAN;
    v_computadora_exists BOOLEAN;
BEGIN
    -- Validar que el alumno existe
    SELECT EXISTS(SELECT 1 FROM alumnos WHERE numerocontrol = p_numero_control) 
    INTO v_alumno_exists;
    IF NOT v_alumno_exists THEN
        RETURN 'Error: El número de control no corresponde a un alumno existente.';
    END IF;

    -- Validar que la computadora existe
    SELECT EXISTS(SELECT 1 FROM computadora WHERE numeroinventario = p_numero_inventario) 
    INTO v_computadora_exists;
    IF NOT v_computadora_exists THEN
        RETURN 'Error: El número de inventario no corresponde a una computadora existente.';
    END IF;

    -- Insertar registro en la bitácora
    INSERT INTO bitacora_alumnos (
        fechahora, numerocontrol, numeroinventario, hora_entrada
    ) VALUES (
        now(), p_numero_control, p_numero_inventario, now()
    );

    RETURN 'Registro creado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_bitacora_maestro(int8, varchar);

CREATE OR REPLACE FUNCTION public.insert_bitacora_maestro(p_tarjeta bigint, p_clave_aula character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_maestro_exists BOOLEAN;
    v_aula_exists BOOLEAN;
BEGIN
    -- Validate that the teacher exists
    SELECT EXISTS(SELECT 1 FROM maestros WHERE Tarjeta = p_tarjeta) INTO v_maestro_exists;
    IF NOT v_maestro_exists THEN
        RETURN 'Error: La tarjeta no corresponde a un maestro existente.';
    END IF;

    -- Validate that the classroom exists
    SELECT EXISTS(SELECT 1 FROM aulas WHERE ClaveAula = p_clave_aula) INTO v_aula_exists;
    IF NOT v_aula_exists THEN
        RETURN 'Error: La clave del aula no corresponde a un aula existente.';
    END IF;

    -- Insert new record into bitacora_maestros
    INSERT INTO bitacora_maestros (Id_Bitacora_Maestro, FechaHora, Tarjeta, ClaveAula)
    VALUES (DEFAULT, NOW(), p_tarjeta, p_clave_aula);
    
    RETURN 'Registro creado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_carga_academica_detalle(int8, int8);

CREATE OR REPLACE FUNCTION public.insert_carga_academica_detalle(p_clave_horario bigint, p_id_carga_general bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_horario_exists BOOLEAN;
    v_carga_general_exists BOOLEAN;
BEGIN
    -- Validate that the schedule exists
    SELECT EXISTS(SELECT 1 FROM horarioasignatura_maestro WHERE ClaveHorario = p_clave_horario) INTO v_horario_exists;
    IF NOT v_horario_exists THEN
        RETURN 'Error: La clave del horario no corresponde a un horario existente.';
    END IF;

    -- Validate that the academic load exists
    SELECT EXISTS(SELECT 1 FROM carga_academica_general WHERE IdCargaGeneral = p_id_carga_general) INTO v_carga_general_exists;
    IF NOT v_carga_general_exists THEN
        RETURN 'Error: La carga académica general no corresponde a un registro existente.';
    END IF;

    -- Insert new record into carga_academica_detalles
    INSERT INTO carga_academica_detalles (IdCargaDetalle, ClaveHorario, IdCargaGeneral)
    VALUES (DEFAULT, p_clave_horario, p_id_carga_general);

    RETURN 'Detalle de carga académica creado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_carga_academica_general(int8);

CREATE OR REPLACE FUNCTION public.insert_carga_academica_general(p_numero_control bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_alumno_exists BOOLEAN;
BEGIN
    -- Validate that the student exists
    SELECT EXISTS(SELECT 1 FROM alumnos WHERE NumeroControl = p_numero_control) INTO v_alumno_exists;
    IF NOT v_alumno_exists THEN
        RETURN 'Error: El número de control no corresponde a un alumno existente.';
    END IF;

    -- Insert new record into carga_academica_general
    INSERT INTO carga_academica_general (IdCargaGeneral, NumeroControl)
    VALUES (DEFAULT, p_numero_control);

    RETURN 'Carga académica general creada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_carrera(varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.insert_carrera(p_clave_carrera character varying, p_nombre character varying, p_descripcion character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the carrera already exists by ClaveCarrera
    SELECT EXISTS(SELECT 1 FROM carreras WHERE ClaveCarrera = p_clave_carrera) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: La carrera ya existe.';
    END IF;

    -- Insert the new carrera
    INSERT INTO carreras (ClaveCarrera, Nombre, Descripcion, Generacion)
    VALUES (p_clave_carrera, p_nombre, p_descripcion, 0);

    RETURN 'Carrera registrada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_competencia(varchar, varchar, text);

CREATE OR REPLACE FUNCTION public.insert_competencia(p_clave_asignatura character varying, p_tipo_competencia character varying, p_descripcion text)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    INSERT INTO competencia("Clave_Asignatura", "Tipo_Competencia", "Descripcion")
    VALUES (p_clave_asignatura, p_tipo_competencia, p_descripcion);

    RETURN 'Competencia guardada con éxito';
END;
$function$
;

-- DROP FUNCTION public.insert_computadora(int8, varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.insert_computadora(p_numero_inventario bigint, p_clave_aula character varying, p_marca character varying, p_estado character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the computadora already exists by NumeroInventario
    SELECT EXISTS(SELECT 1 FROM computadora WHERE NumeroInventario = p_numero_inventario) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: La computadora ya existe.';
    END IF;

    -- Insert the new computadora
    INSERT INTO computadora (NumeroInventario, ClaveAula, Marca, Estado)
    VALUES (p_numero_inventario, p_clave_aula, p_marca, p_estado);

    RETURN 'Computadora registrada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_edificio(varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.insert_edificio(p_clave_edificio character varying, p_nombre character varying, p_descripcion character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the edificio already exists by ClaveEdificio
    SELECT EXISTS(SELECT 1 FROM edificios WHERE ClaveEdificio = p_clave_edificio) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: El edificio ya existe.';
    END IF;

    -- Insert the new edificio
    INSERT INTO edificios (ClaveEdificio, Nombre, Descripcion)
    VALUES (p_clave_edificio, p_nombre, p_descripcion);

    RETURN 'Edificio registrado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_grupo(varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.insert_grupo(p_clave_grupo character varying, p_nombre character varying, p_descripcion character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the grupo already exists by ClaveGrupo
    SELECT EXISTS(SELECT 1 FROM grupos WHERE ClaveGrupo = p_clave_grupo) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: El grupo ya existe.';
    END IF;

    -- Insert the new grupo
    INSERT INTO grupos (ClaveGrupo, Nombre, Descripcion)
    VALUES (p_clave_grupo, p_nombre, p_descripcion);

    RETURN 'Grupo registrado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_horario(int8, int8, varchar, varchar, varchar, time, time, int8, time, time, time, time, time, time, time, time, time, time);

CREATE OR REPLACE FUNCTION public.insert_horario(p_clave_horario bigint, p_tarjeta bigint, p_clave_aula character varying, p_clave_grupo character varying, p_clave_asignatura character varying, p_lunes_hi time without time zone, p_lunes_hf time without time zone, p_id_periodo_escolar bigint, p_martes_hi time without time zone, p_martes_hf time without time zone, p_miercoles_hi time without time zone, p_miercoles_hf time without time zone, p_jueves_hi time without time zone, p_jueves_hf time without time zone, p_viernes_hi time without time zone, p_viernes_hf time without time zone, p_sabado_hi time without time zone, p_sabado_hf time without time zone)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the horario already exists by ClaveHorario
    SELECT EXISTS(SELECT 1 FROM horarioasignatura_maestro WHERE ClaveHorario = p_clave_horario) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: El horario ya existe.';
    END IF;

    -- Insert the new horario
    INSERT INTO horarioasignatura_maestro (
        ClaveHorario, Tarjeta, ClaveAula, ClaveGrupo, ClaveAsignatura,
        Lunes_HI, Lunes_HF, IdPeriodoEscolar,
        Martes_HI, Martes_HF, Miercoles_HI, Miercoles_HF,
        Jueves_HI, Jueves_HF, Viernes_HI, Viernes_HF,
        Sabado_HI, Sabado_HF
    ) 
    VALUES (
        p_clave_horario, p_tarjeta, p_clave_aula, p_clave_grupo, p_clave_asignatura,
        p_lunes_hi, p_lunes_hf, p_id_periodo_escolar,
        p_martes_hi, p_martes_hf, p_miercoles_hi, p_miercoles_hf,
        p_jueves_hi, p_jueves_hf, p_viernes_hi, p_viernes_hf,
        p_sabado_hi, p_sabado_hf
    );

    RETURN 'Horario registrado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_maestro(int8, varchar, varchar, varchar, int8, varchar, varchar, varchar, varchar, varchar, varchar, varchar, varchar, varchar, int2);

CREATE OR REPLACE FUNCTION public.insert_maestro(p_tarjeta bigint, p_nombre character varying, p_apellido_paterno character varying, p_apellido_materno character varying, p_id_usuario bigint, p_rfc character varying, p_escolaridad_licenciatura character varying, p_estado_licenciatura character varying, p_escolaridad_especializacion character varying, p_estado_especializacion character varying, p_escolaridad_maestria character varying, p_estado_maestria character varying, p_escolaridad_doctorado character varying, p_estado_doctorado character varying, p_id_departamento smallint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the maestro already exists by Tarjeta
    SELECT EXISTS(SELECT 1 FROM maestros WHERE Tarjeta = p_tarjeta) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: El maestro ya existe.';
    END IF;

    -- Insert the new maestro
    INSERT INTO maestros (
        Tarjeta, Nombre, ApellidoPaterno, ApellidoMaterno, IdUsuario,
        RFC, Escolaridad_Licenciatura, Estado_Licenciatura, Escolaridad_Especializacion,
        Estado_Especializacion, Escolaridad_Maestria, Estado_Maestria, Escolaridad_Doctorado,
        Estado_Doctorado, Id_Departamento
    )
    VALUES (
        p_tarjeta, p_nombre, p_apellido_paterno, p_apellido_materno, p_id_usuario,
        p_rfc, p_escolaridad_licenciatura, p_estado_licenciatura, p_escolaridad_especializacion,
        p_estado_especializacion, p_escolaridad_maestria, p_estado_maestria, p_escolaridad_doctorado,
        p_estado_doctorado, p_id_departamento
    );

    RETURN 'Maestro registrado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_periodo_escolar(varchar, varchar, date, date, bool);

CREATE OR REPLACE FUNCTION public.insert_periodo_escolar(p_codigoperiodo character varying, p_nombre_periodo character varying, p_fecha_inicio date, p_fecha_fin date, p_estado boolean DEFAULT true)
 RETURNS TABLE(id_periodo_escolar bigint, mensaje text)
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists boolean;
BEGIN
    -- Validar duplicado de código
    SELECT EXISTS(SELECT 1 FROM periodo_escolar WHERE codigoperiodo = p_codigoperiodo)
    INTO v_exists;
    IF v_exists THEN
        RETURN QUERY SELECT NULL::bigint, 'Error: El período escolar ya existe con ese código.'::text;
        RETURN;
    END IF;

    -- Validar fechas
    IF p_fecha_inicio > p_fecha_fin THEN
        RETURN QUERY SELECT NULL::bigint, 'Error: La fecha de inicio no puede ser mayor que la fecha de fin.'::text;
        RETURN;
    END IF;

    -- Insertar y devolver el id generado
    INSERT INTO periodo_escolar (
        codigoperiodo,
        nombre_periodo,
        fecha_inicio,
        fecha_fin,
        estado
    ) VALUES (
        p_codigoperiodo,
        p_nombre_periodo,
        p_fecha_inicio,
        p_fecha_fin,
        COALESCE(p_estado, true)
    )
    RETURNING id_periodo_escolar INTO id_periodo_escolar;

    RETURN QUERY SELECT id_periodo_escolar, 'Período escolar creado exitosamente'::text;
END;
$function$
;

-- DROP FUNCTION public.insert_reservacion_alumno(int8, int8, date, time, time);

CREATE OR REPLACE FUNCTION public.insert_reservacion_alumno(p_numero_control bigint, p_numero_inventario bigint, p_fecha_reservacion date, p_hora_inicio time without time zone, p_hora_fin time without time zone)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the reservation already exists
    SELECT EXISTS(SELECT 1 FROM reservacionalumnos 
                  WHERE NumeroControl = p_numero_control 
                  AND NumeroInventario = p_numero_inventario
                  AND FechaReservacion = p_fecha_reservacion 
                  AND HoraInicio = p_hora_inicio
                  AND HoraFin = p_hora_fin) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: La reservación ya existe.';
    END IF;

    -- Insert the new reservation
    INSERT INTO reservacionalumnos (
        NumeroControl, NumeroInventario, FechaReservacion, 
        HoraInicio, HoraFin
    )
    VALUES (
        p_numero_control, p_numero_inventario, p_fecha_reservacion, 
        p_hora_inicio, p_hora_fin
    );

    RETURN 'Reservación registrada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_reservacion_maestro(int8, varchar, date, time, time);

CREATE OR REPLACE FUNCTION public.insert_reservacion_maestro(p_tarjeta bigint, p_clave_aula character varying, p_fecha_reservacion date, p_hora_inicio time without time zone, p_hora_fin time without time zone)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the reservation already exists
    SELECT EXISTS(SELECT 1 FROM reservacionmaestros 
                  WHERE Tarjeta = p_tarjeta 
                  AND ClaveAula = p_clave_aula
                  AND FechaReservacion = p_fecha_reservacion 
                  AND HoraInicio = p_hora_inicio
                  AND HoraFin = p_hora_fin) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: La reservación ya existe.';
    END IF;

    -- Insert the new reservation
    INSERT INTO reservacionmaestros (
        Tarjeta, ClaveAula, FechaReservacion, 
        HoraInicio, HoraFin
    )
    VALUES (
        p_tarjeta, p_clave_aula, p_fecha_reservacion, 
        p_hora_inicio, p_hora_fin
    );

    RETURN 'Reservación registrada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_role(int8, varchar);

CREATE OR REPLACE FUNCTION public.insert_role(p_id_rol bigint, p_nombre character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the role already exists
    SELECT EXISTS(SELECT 1 FROM roles WHERE IdRol = p_id_rol) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: El rol ya existe.';
    END IF;

    -- Insert the new role
    INSERT INTO roles (IdRol, Nombre)
    VALUES (p_id_rol, p_nombre);

    RETURN 'Rol creado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insert_subtema(int4, text);

CREATE OR REPLACE FUNCTION public.insert_subtema(p_tema_id integer, p_nombre text)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    INSERT INTO subtema("Tema_id", "Nombre_Subtema")
    VALUES (p_tema_id, p_nombre);

    RETURN 'Subtema guardado';
END;
$function$
;

-- DROP FUNCTION public.insert_tema(varchar, int4, text);

CREATE OR REPLACE FUNCTION public.insert_tema(p_clave_asignatura character varying, p_numero integer, p_nombre text)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
    new_id INTEGER;
BEGIN
    INSERT INTO tema("Clave_Asignatura", "Numero", "Nombre_Tema")
    VALUES (p_clave_asignatura, p_numero, p_nombre)
    RETURNING "id_Tema" INTO new_id;

    RETURN new_id;
END;
$function$
;

-- DROP FUNCTION public.insert_usuario(varchar, varchar, int8);

CREATE OR REPLACE FUNCTION public.insert_usuario(p_correo character varying, p_password character varying, p_id_rol bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the email already exists
    SELECT EXISTS(SELECT 1 FROM usuarios WHERE Correo = p_correo) INTO v_exists;
    IF v_exists THEN
        RETURN 'Error: El correo ya está registrado.';
    END IF;

    -- Insert the new user
    INSERT INTO usuarios (Correo, Password, Token, IdRol)
    VALUES (p_correo, crypt(p_password, gen_salt('bf')), NULL, p_id_rol);

    RETURN 'Usuario registrado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.insertar_departamento(int2, varchar, varchar);

CREATE OR REPLACE FUNCTION public.insertar_departamento(p_id_departamento smallint, p_nombre character varying, p_abreviacion character varying)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
BEGIN
    INSERT INTO departamentos (id_departamento, nombre, abreviacion)
    VALUES (p_id_departamento, p_nombre, p_abreviacion, p_maestro_id);
END;
$function$
;

-- DROP FUNCTION public.insertar_horario_maestro(int4, varchar, time, time);

CREATE OR REPLACE FUNCTION public.insertar_horario_maestro(p_maestro_id integer, p_dia_semana character varying, p_hora_inicio time without time zone, p_hora_fin time without time zone)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    traslape_count INT;
BEGIN
    IF p_hora_fin <= p_hora_inicio THEN
        RETURN 'Error: La hora de fin debe ser mayor que la hora de inicio.';
    END IF;

    IF p_dia_semana NOT IN ('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo') THEN
        RETURN 'Error: Día de la semana no válido.';
    END IF;

    SELECT COUNT(*) INTO traslape_count
    FROM horarios_maestros
    WHERE maestro_id = p_maestro_id
      AND dia_semana = p_dia_semana
      AND (p_hora_inicio < hora_fin AND p_hora_fin > hora_inicio);

    IF traslape_count > 0 THEN
        RETURN 'Error: El nuevo horario se traslapa con un horario existente.';
    END IF;

    INSERT INTO horarios_maestros (maestro_id, dia_semana, hora_inicio, hora_fin)
    VALUES (p_maestro_id, p_dia_semana, p_hora_inicio, p_hora_fin);

    RETURN 'Horario insertado correctamente.';
END;
$function$
;

-- DROP FUNCTION public.login_usuario(varchar, varchar);

CREATE OR REPLACE FUNCTION public.login_usuario(p_correo character varying, p_password character varying)
 RETURNS TABLE(idusuario bigint, correo character varying, token character varying, idrol bigint)
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_token varchar(80);
BEGIN
    -- Verificar credenciales (usando nombre completo para el parámetro)
    IF NOT EXISTS(SELECT 1 FROM usuarios WHERE usuarios.Correo = login_usuario.p_correo AND usuarios.Password = crypt(p_password, Password)) THEN
        RETURN QUERY SELECT NULL::bigint, NULL::varchar, NULL::varchar, NULL::bigint;
        RETURN;
    END IF;

    -- Generar token
    SELECT uuid_generate_v4() INTO v_token;
    
    -- Actualizar token (usando nombre completo para el parámetro)
    UPDATE usuarios SET Token = v_token WHERE usuarios.Correo = login_usuario.p_correo;
    
    -- Retornar datos
    RETURN QUERY
    SELECT usuarios.IdUsuario, usuarios.Correo, v_token, usuarios.IdRol 
    FROM usuarios 
    WHERE usuarios.Correo = login_usuario.p_correo;
END;
$function$
;

-- DROP FUNCTION public.obtener_asignaturas_por_tarjeta(int8);

CREATE OR REPLACE FUNCTION public.obtener_asignaturas_por_tarjeta(p_tarjeta bigint)
 RETURNS TABLE(claveasignatura character varying, nombreasignatura character varying, creditos integer, satca_practicas integer, satca_teoricas integer, satca_total integer)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        a."ClaveAsignatura",
        a."NombreAsignatura",
        a."Creditos",
        a."Satca_Practicas",
        a."Satca_Teoricas",
        a."Satca_Total"
    FROM maestros m
    JOIN departamentos_carreras dc ON m.Id_Departamento = dc.Id_Departamento
    JOIN asignatura_carrera ac ON dc.ClaveCarrera = ac."Clave_Carrera"
    JOIN asignatura a ON ac."Clave_Asignatura" = a."ClaveAsignatura"
    WHERE m.Tarjeta = p_tarjeta;
END;
$function$
;

-- DROP FUNCTION public.obtener_avances_completos(int8);

CREATE OR REPLACE FUNCTION public.obtener_avances_completos(p_filtro_tarjeta_profesor bigint DEFAULT NULL::bigint)
 RETURNS TABLE(avance_id integer, clave_asignatura character varying, nombre_asignatura text, tarjeta_profesor bigint, nombre_profesor text, periodo character varying, fecha_creacion date, firma_profesor character varying, firma_jefe_carrera character varying, estado character varying, clave_horario bigint, clave_aula character varying, clave_grupo character varying, fecha_ultima_actualizacion timestamp without time zone, version integer, creditos integer, satca_practicas integer, satca_teoricas integer, satca_total integer, presentacion json, detalles json, cantidad_temas integer, temas_completados integer, porcentaje_total numeric, porcentaje_completado numeric, fecha_inicio_programada date, fecha_fin_programada date, dias_restantes integer)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    WITH avance_detalle_agg AS (
        SELECT 
            ad.id_avance,
            json_agg(json_build_object(
                'id_avance_detalle', ad.id_avance_detalle,
                'tema', ad.tema,
                'subtema', ad.subtema,
                'fecha_programada_inicio', ad.fecha_programada_inicio,
                'fecha_programada_fin', ad.fecha_programada_fin,
                'fecha_real_inicio', ad.fecha_real_inicio,
                'fecha_real_fin', ad.fecha_real_fin,
                'evaluacion_programada_inicio', ad.evaluacion_programada_inicio,
                'evaluacion_programada_fin', ad.evaluacion_programada_fin,
                'evaluacion_realizada_inicio', ad.evaluacion_realizada_inicio,
                'evaluacion_realizada_fin', ad.evaluacion_realizada_fin,
                'porcentaje', ad.porcentaje,
                'observaciones', ad.observaciones,
                'completado', ad.completado,
                'fecha_registro', ad.fecha_registro
            )) AS detalles_json,
            COUNT(*) AS cantidad_temas,
            SUM(CASE WHEN ad.completado THEN 1 ELSE 0 END) AS temas_completados,
            SUM(ad.porcentaje) AS porcentaje_total,
            SUM(CASE WHEN ad.completado THEN ad.porcentaje ELSE 0 END) AS porcentaje_completado,
            MIN(ad.fecha_programada_inicio) AS fecha_inicio_programada,
            MAX(ad.fecha_programada_fin) AS fecha_fin_programada
        FROM 
            public.avance_detalle ad
        GROUP BY 
            ad.id_avance
    )
    SELECT 
        a.id_avance::INTEGER AS avance_id,
        a.clave_asignatura::VARCHAR(20),
        asi."NombreAsignatura"::TEXT AS nombre_asignatura,
        a.tarjeta_profesor::BIGINT,
        (m.nombre || ' ' || m.apellidopaterno || ' ' || COALESCE(m.apellidomaterno, ''))::TEXT AS nombre_profesor,
        a.periodo::VARCHAR(20),
        a.fecha_creacion::DATE,
        a.firma_profesor::VARCHAR(100),
        a.firma_jefe_carrera::VARCHAR(100),
        a.estado::VARCHAR(20),
        a.clave_horario::BIGINT,
        ham.claveaula::VARCHAR(10) AS clave_aula,
        ham.clavegrupo::VARCHAR(10) AS clave_grupo,
        a.fecha_ultima_actualizacion::TIMESTAMP,
        a.version::INTEGER,

        asi."Creditos"::INTEGER AS creditos,
        asi."Satca_Practicas"::INTEGER AS satca_practicas,
        asi."Satca_Teoricas"::INTEGER AS satca_teoricas,
        asi."Satca_Total"::INTEGER AS satca_total,
        (
            SELECT json_build_object(
                'caracterizacion', p."Caracterizacion"
            )
            FROM presentacion p
            WHERE p."Clave_Asignatura" = asi."ClaveAsignatura"
            LIMIT 1
        )::JSON AS presentacion,

        COALESCE(ada.detalles_json, '[]'::json)::JSON,
        
        COALESCE(ada.cantidad_temas, 0)::INTEGER,
        COALESCE(ada.temas_completados, 0)::INTEGER,
        COALESCE(ada.porcentaje_total, 0)::NUMERIC(5,2),
        COALESCE(ada.porcentaje_completado, 0)::NUMERIC(5,2),
        ada.fecha_inicio_programada::DATE,
        ada.fecha_fin_programada::DATE,
        CASE 
            WHEN ada.fecha_fin_programada IS NULL THEN NULL
            ELSE (ada.fecha_fin_programada - CURRENT_DATE)::INTEGER
        END AS dias_restantes
    FROM 
        public.avance a
    JOIN 
        public.asignatura asi ON a.clave_asignatura = asi."ClaveAsignatura"
    JOIN 
        public.maestros m ON a.tarjeta_profesor = m.tarjeta
    LEFT JOIN 
        avance_detalle_agg ada ON a.id_avance = ada.id_avance
    LEFT JOIN 
        public.horarioasignatura_maestro ham ON a.clave_horario = ham.clavehorario
    WHERE 
        (p_filtro_tarjeta_profesor IS NULL OR a.tarjeta_profesor = p_filtro_tarjeta_profesor)
    ORDER BY 
        a.fecha_creacion DESC, a.id_avance;
END;
$function$
;

-- DROP FUNCTION public.obtener_comisiones_con_maestros();

CREATE OR REPLACE FUNCTION public.obtener_comisiones_con_maestros()
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    resultado json;
BEGIN
    SELECT COALESCE(json_agg(com_data), '[]'::json) INTO resultado
    FROM (
        SELECT
            c.id_comision,
            c.folio,
            c.nombre_evento,
            c.tipo_evento,          -- (varchar en la tabla)
            c.estado,               -- (estado_enum)
            c.remitente_nombre,
            c.remitente_puesto,
            c.lugar,
            c.motivo,
            c.tipo_comision,        -- (origen_enum)
            c.permiso_constancia,
            c.id_tipo_evento,
            c.id_periodo_escolar,
            c.created_at,
            c.updated_at,
            (
                SELECT COALESCE(json_agg(
                    json_build_object(
                        'tarjeta', m.tarjeta,
                        'nombre_completo',
                            TRIM(
                                COALESCE(m.nombre, '') || ' ' ||
                                COALESCE(m.apellidopaterno, '') || ' ' ||
                                COALESCE(m.apellidomaterno, '')
                            )
                    )
                ), '[]'::json)
                FROM comision_maestro cm
                JOIN maestros m
                  ON cm.tarjeta_maestro = m.tarjeta
               WHERE cm.id_comision = c.id_comision
            ) AS maestros
        FROM public.comision c
        ORDER BY c.id_comision DESC
    ) AS com_data;

    RETURN resultado;
END;
$function$
;

-- DROP FUNCTION public.obtener_competencia(int8);

CREATE OR REPLACE FUNCTION public.obtener_competencia(p_id_competencia bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    competencia_record RECORD;
    resultado JSONB := '{}';
BEGIN
    SELECT * INTO competencia_record
    FROM competencia
    WHERE "id_Competencia" = p_id_competencia;

    IF competencia_record IS NULL THEN
        RETURN jsonb_build_object(
            'success', false,
            'message', 'Competencia no encontrada'
        );
    END IF;

    resultado := jsonb_build_object(
        'success', true,
        'competencia', jsonb_build_object(
            'id_Competencia', competencia_record."id_Competencia",
            'Clave_Asignatura', competencia_record."Clave_Asignatura",
            'Descripcion', competencia_record."Descripcion",
            'Tipo_Competencia', competencia_record."Tipo_Competencia"
        )
    );

    RETURN resultado;
EXCEPTION WHEN OTHERS THEN
    RETURN jsonb_build_object(
        'success', false,
        'message', 'Error al obtener competencia: ' || SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.obtener_departamento(int2);

CREATE OR REPLACE FUNCTION public.obtener_departamento(p_id_departamento smallint)
 RETURNS TABLE(id_departamento smallint, nombre character varying, abreviacion character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        d.id_departamento,
        d.nombre,
        d.abreviacion
    FROM departamentos
    WHERE id_departamento = p_id_departamento;
END;
$function$
;

-- DROP FUNCTION public.obtener_diseno_curricular_por_clave(varchar);

CREATE OR REPLACE FUNCTION public.obtener_diseno_curricular_por_clave(clave_asig character varying)
 RETURNS TABLE(id_diseno bigint, clave_asignatura character varying, fecha date, evento character varying, id_participante bigint, nombre_participante character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        dc."id_Dis_Curricular",
        dc."Clave_Asignatura",
        dc."Fecha",
        dc."Evento",
        p."id_Participante",
        p."Nombre_Participante"
    FROM diseno_curricular dc
    LEFT JOIN diseno_participante dp ON dp."Diseno_id" = dc."id_Dis_Curricular"
    LEFT JOIN participante p ON p."id_Participante" = dp."Participante_id"
    WHERE dc."Clave_Asignatura" = clave_asig;
END;
$function$
;

-- DROP FUNCTION public.obtener_horario_maestro(int4);

CREATE OR REPLACE FUNCTION public.obtener_horario_maestro(p_maestro_id integer)
 RETURNS TABLE(id_horario_maestro integer, dia_semana text, hora_inicio time without time zone, hora_fin time without time zone)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        id_Horario_Maestro,
        dia_semana,
        hora_inicio,
        hora_fin
    FROM horarios_maestros
    WHERE maestro_id = p_maestro_id
    ORDER BY 
        CASE dia_semana
            WHEN 'Lunes' THEN 1
            WHEN 'Martes' THEN 2
            WHEN 'Miércoles' THEN 3
            WHEN 'Jueves' THEN 4
            WHEN 'Viernes' THEN 5
            WHEN 'Sábado' THEN 6
            WHEN 'Domingo' THEN 7
        END,
        hora_inicio;
END;
$function$
;

-- DROP FUNCTION public.obtener_instrumentacion_por_tarjeta(int8);

CREATE OR REPLACE FUNCTION public.obtener_instrumentacion_por_tarjeta(p_tarjeta bigint)
 RETURNS json
 LANGUAGE plpgsql
AS $function$
DECLARE
    resultado JSON;
BEGIN
    SELECT json_agg(instrumentacion_data)
    INTO resultado
    FROM (
        SELECT 
            i."id_Instrumentacion",
            i."Clave_Asignatura",
            a."NombreAsignatura",
            a."Creditos",
            a."Satca_Practicas",
            a."Satca_Teoricas",
            a."Satca_Total",
            i."Periodo",
            i."Fecha_Creacion",
            i."Firma_Profesor",
            i."Firma_Jefe",
            i."Estado",
            
            -- Obtener grupo y aula desde horarioasignatura_maestro
            (
                SELECT json_agg(ha_data)
                FROM (
                    SELECT 
                        g."clavegrupo",
                        g."nombre" AS nombre_grupo,
                        g."descripcion" AS descripcion_grupo,
                        au."claveaula",
                        au."nombre" AS nombre_aula
                    FROM horarioasignatura_maestro ha
                    JOIN grupos g ON g."clavegrupo" = ha."clavegrupo"
                    JOIN aulas au ON au."claveaula" = ha."claveaula"
                    WHERE ha."tarjeta" = i."Tarjeta"
                      AND ha."claveasignatura" = i."Clave_Asignatura"
                      AND ha."idperiodoescolar"::text = i."Periodo" -- suponiendo que "Periodo" coincide con idperiodoescolar
                ) AS ha_data
            ) AS horarios,
            
            -- Competencias de la asignatura
            (
                SELECT json_agg(c_data)
                FROM (
                    SELECT 
                        c."id_Competencia",
                        c."Descripcion",
                        c."Tipo_Competencia"
                    FROM competencia c
                    WHERE c."Clave_Asignatura" = i."Clave_Asignatura"
                ) AS c_data
            ) AS competencias,

            -- Carreras asociadas a la asignatura
            (
                SELECT json_agg(carrera_data)
                FROM (
                    SELECT 
                        ca."clavecarrera",
                        ca."nombre",
                        ca."descripcion",
                        ca."generacion",
                        ac."Semestre",
                        ac."Posicion"
                    FROM asignatura_carrera ac
                    JOIN carreras ca ON ca."clavecarrera" = ac."Clave_Carrera"
                    WHERE ac."Clave_Asignatura" = i."Clave_Asignatura"
                ) AS carrera_data
            ) AS carreras,

            -- Detalles de la instrumentación
            (
                SELECT json_agg(detalle_data)
                FROM (
                    SELECT 
                        d."id_Instrumentacion_Detalle",
                        d."id_Tema",
                        d."Fecha_Programada_Inicio",
                        d."Fecha_Programada_Fin",
                        d."Fecha_Real_Inicio",
                        d."Fecha_Real_Fin",
                        d."Evaluacion_Programada_Inicio",
                        d."Evaluacion_Programada_Fin",
                        d."Evaluacion_Realizada_Inicio",
                        d."Evaluacion_Realizada_Fin",
                        d."Porcentaje",
                        d."Observaciones"
                    FROM instrumentacion_detalle d
                    WHERE d."id_Instrumentacion" = i."id_Instrumentacion"
                ) AS detalle_data
            ) AS detalles

        FROM instrumentacion i
        JOIN asignatura a ON a."ClaveAsignatura" = i."Clave_Asignatura"
        WHERE i."Tarjeta" = p_tarjeta
    ) AS instrumentacion_data;

    IF resultado IS NULL THEN
        RETURN json_build_object(
            'status', 'success',
            'message', 'No se encontró instrumentación para esta tarjeta',
            'data', '[]'
        );
    END IF;

    RETURN json_build_object(
        'status', 'success',
        'data', resultado
    );

EXCEPTION WHEN OTHERS THEN
    RETURN json_build_object(
        'status', 'error',
        'message', SQLERRM
    );
END;
$function$
;

-- DROP FUNCTION public.obtener_presentaciones_por_asignatura(varchar);

CREATE OR REPLACE FUNCTION public.obtener_presentaciones_por_asignatura(p_clave_asignatura character varying)
 RETURNS TABLE(id_presentacion bigint, clave_asignatura character varying, caracterizacion character varying, intencion_didactica character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        p."id_Presentacion",
        p."Clave_Asignatura",
        p."Caracterizacion",
        p."Intencion_didactica"
    FROM 
        presentacion p
    WHERE 
        p."Clave_Asignatura" = p_clave_asignatura;
END;
$function$
;

-- DROP FUNCTION public.obtener_temas_y_subtemas_por_asignatura(varchar);

CREATE OR REPLACE FUNCTION public.obtener_temas_y_subtemas_por_asignatura(p_clave_asignatura character varying)
 RETURNS TABLE(id_tema bigint, numero integer, nombre_tema character varying, id_subtema bigint, nombre_subtema character varying)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT 
        t."id_Tema",
        t."Numero",
        t."Nombre_Tema",
        s."id_Subtema",
        s."Nombre_Subtema"
    FROM tema t
    LEFT JOIN subtema s ON s."Tema_id" = t."id_Tema"
    WHERE t."Clave_Asignatura" = p_clave_asignatura
    ORDER BY t."Numero", s."id_Subtema";
END;
$function$
;

-- DROP FUNCTION public.update_alumno(int8, varchar, varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.update_alumno(p_numero_control bigint, p_nombre character varying, p_apellido_paterno character varying, p_apellido_materno character varying, p_clave_carrera character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    UPDATE alumnos
    SET Nombre = p_nombre,
        ApellidoPaterno = p_apellido_paterno,
        ApellidoMaterno = p_apellido_materno,
        ClaveCarrera = p_clave_carrera
    WHERE NumeroControl = p_numero_control;
    
    IF FOUND THEN
        RETURN 'Alumno actualizado exitosamente';
    ELSE
        RETURN 'Error: Alumno no encontrado';
    END IF;
END;
$function$
;

-- DROP FUNCTION public.update_asignatura_carrera(varchar, varchar, varchar, int4, int4, int4, int2, int2);

CREATE OR REPLACE FUNCTION public.update_asignatura_carrera(p_clave_asignatura character varying, p_clave_carrera character varying, p_nombre character varying, p_creditos integer, p_horas_teoricas integer, p_horas_practicas integer, p_semestre smallint, p_posicion smallint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Verificar existencia
    SELECT EXISTS(SELECT 1 FROM asignatura WHERE "ClaveAsignatura" = p_clave_asignatura) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: La asignatura no existe.';
    END IF;

    -- Actualizar asignatura
    UPDATE asignatura
    SET "NombreAsignatura" = p_nombre,
        "Creditos" = p_creditos,
        "Satca_Teoricas" = p_horas_teoricas,
        "Satca_Practicas" = p_horas_practicas,
        "Satca_Total" = p_horas_teoricas + p_horas_practicas
    WHERE "ClaveAsignatura" = p_clave_asignatura;

    -- Actualizar relación con carrera
    UPDATE asignatura_carrera
    SET "Semestre" = p_semestre,
        "Posicion" = p_posicion
    WHERE "Clave_Asignatura" = p_clave_asignatura AND "Clave_Carrera" = p_clave_carrera;

    RETURN 'Asignatura actualizada exitosamente.';
END;
$function$
;

-- DROP FUNCTION public.update_aula(varchar, varchar, varchar, int4, time, estado_enum);

CREATE OR REPLACE FUNCTION public.update_aula(p_clave_aula character varying, p_clave_edificio character varying DEFAULT NULL::character varying, p_nombre character varying DEFAULT NULL::character varying, p_cantidad_computadoras integer DEFAULT NULL::integer, p_hora_disponible time without time zone DEFAULT NULL::time without time zone, p_estado estado_enum DEFAULT NULL::estado_enum)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
    UPDATE aulas a
       SET claveedificio       = COALESCE(p_clave_edificio, a.claveedificio),
           nombre              = COALESCE(p_nombre, a.nombre),
           cantidadcomputadoras= COALESCE(p_cantidad_computadoras, a.cantidadcomputadoras),
           horadisponible      = COALESCE(p_hora_disponible, a.horadisponible),
           estado              = COALESCE(p_estado, a.estado)
     WHERE a.claveaula = p_clave_aula;

    IF FOUND THEN
        RETURN 'Aula actualizada exitosamente';
    ELSE
        RETURN 'Error: Aula no encontrada';
    END IF;

EXCEPTION
    WHEN foreign_key_violation THEN
        RETURN 'Error: ClaveEdificio no existe (violación de FK)';
    WHEN check_violation THEN
        RETURN 'Error: Violación de restricción CHECK';
    WHEN others THEN
        RETURN 'Error desconocido al actualizar el aula';
END;
$function$
;

-- DROP FUNCTION public.update_carga_academica_detalle(int8, int8, int8);

CREATE OR REPLACE FUNCTION public.update_carga_academica_detalle(p_id_carga_detalle bigint, p_clave_horario bigint, p_id_carga_general bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_horario_exists BOOLEAN;
    v_carga_general_exists BOOLEAN;
BEGIN
    -- Validate that the schedule exists
    SELECT EXISTS(SELECT 1 FROM horarioasignatura_maestro WHERE ClaveHorario = p_clave_horario) INTO v_horario_exists;
    IF NOT v_horario_exists THEN
        RETURN 'Error: La clave del horario no corresponde a un horario existente.';
    END IF;

    -- Validate that the academic load exists
    SELECT EXISTS(SELECT 1 FROM carga_academica_general WHERE IdCargaGeneral = p_id_carga_general) INTO v_carga_general_exists;
    IF NOT v_carga_general_exists THEN
        RETURN 'Error: La carga académica general no corresponde a un registro existente.';
    END IF;

    -- Update the record in carga_academica_detalles
    UPDATE carga_academica_detalles
    SET ClaveHorario = p_clave_horario, IdCargaGeneral = p_id_carga_general
    WHERE IdCargaDetalle = p_id_carga_detalle;

    RETURN 'Detalle de carga académica actualizado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_carga_academica_general(int8, int8);

CREATE OR REPLACE FUNCTION public.update_carga_academica_general(p_id_carga_general bigint, p_numero_control bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_alumno_exists BOOLEAN;
BEGIN
    -- Validate that the student exists
    SELECT EXISTS(SELECT 1 FROM alumnos WHERE NumeroControl = p_numero_control) INTO v_alumno_exists;
    IF NOT v_alumno_exists THEN
        RETURN 'Error: El número de control no corresponde a un alumno existente.';
    END IF;

    -- Update the record in carga_academica_general
    UPDATE carga_academica_general
    SET NumeroControl = p_numero_control
    WHERE IdCargaGeneral = p_id_carga_general;

    RETURN 'Carga académica general actualizada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_carrera(varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.update_carrera(p_clave_carrera character varying, p_nombre character varying, p_descripcion character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the carrera exists
    SELECT EXISTS(SELECT 1 FROM carreras WHERE ClaveCarrera = p_clave_carrera) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: La carrera no existe.';
    END IF;

    -- Update the carrera
    UPDATE carreras
    SET Nombre = p_nombre, Descripcion = p_descripcion
    WHERE ClaveCarrera = p_clave_carrera;

    RETURN 'Carrera actualizada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_computadora(int8, varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.update_computadora(p_numero_inventario bigint, p_clave_aula character varying, p_marca character varying, p_estado character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the computadora exists
    SELECT EXISTS(SELECT 1 FROM computadora WHERE NumeroInventario = p_numero_inventario) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: La computadora no existe.';
    END IF;

    -- Update the computadora
    UPDATE computadora
    SET ClaveAula = p_clave_aula, Marca = p_marca, Estado = p_estado
    WHERE NumeroInventario = p_numero_inventario;

    RETURN 'Computadora actualizada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_edificio(varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.update_edificio(p_clave_edificio character varying, p_nombre character varying, p_descripcion character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the edificio exists
    SELECT EXISTS(SELECT 1 FROM edificios WHERE ClaveEdificio = p_clave_edificio) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: El edificio no existe.';
    END IF;

    -- Update the edificio
    UPDATE edificios
    SET Nombre = p_nombre, Descripcion = p_descripcion
    WHERE ClaveEdificio = p_clave_edificio;

    RETURN 'Edificio actualizado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_grupo(varchar, varchar, varchar);

CREATE OR REPLACE FUNCTION public.update_grupo(p_clave_grupo character varying, p_nombre character varying, p_descripcion character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the grupo exists
    SELECT EXISTS(SELECT 1 FROM grupos WHERE ClaveGrupo = p_clave_grupo) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: El grupo no existe.';
    END IF;

    -- Update the grupo
    UPDATE grupos
    SET Nombre = p_nombre, Descripcion = p_descripcion
    WHERE ClaveGrupo = p_clave_grupo;

    RETURN 'Grupo actualizado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_horario(int8, int8, varchar, varchar, varchar, time, time, int8, time, time, time, time, time, time, time, time, time, time);

CREATE OR REPLACE FUNCTION public.update_horario(p_clave_horario bigint, p_tarjeta bigint, p_clave_aula character varying, p_clave_grupo character varying, p_clave_asignatura character varying, p_lunes_hi time without time zone, p_lunes_hf time without time zone, p_id_periodo_escolar bigint, p_martes_hi time without time zone, p_martes_hf time without time zone, p_miercoles_hi time without time zone, p_miercoles_hf time without time zone, p_jueves_hi time without time zone, p_jueves_hf time without time zone, p_viernes_hi time without time zone, p_viernes_hf time without time zone, p_sabado_hi time without time zone, p_sabado_hf time without time zone)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the horario exists
    SELECT EXISTS(SELECT 1 FROM horarioasignatura_maestro WHERE ClaveHorario = p_clave_horario) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: El horario no existe.';
    END IF;

    -- Update the horario
    UPDATE horarioasignatura_maestro
    SET 
        Tarjeta = p_tarjeta,
        ClaveAula = p_clave_aula,
        ClaveGrupo = p_clave_grupo,
        ClaveAsignatura = p_clave_asignatura,
        Lunes_HI = p_lunes_hi,
        Lunes_HF = p_lunes_hf,
        IdPeriodoEscolar = p_id_periodo_escolar,
        Martes_HI = p_martes_hi,
        Martes_HF = p_martes_hf,
        Miercoles_HI = p_miercoles_hi,
        Miercoles_HF = p_miercoles_hf,
        Jueves_HI = p_jueves_hi,
        Jueves_HF = p_jueves_hf,
        Viernes_HI = p_viernes_hi,
        Viernes_HF = p_viernes_hf,
        Sabado_HI = p_sabado_hi,
        Sabado_HF = p_sabado_hf
    WHERE ClaveHorario = p_clave_horario;

    RETURN 'Horario actualizado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_maestro(int8, varchar, varchar, varchar, int8, varchar, varchar, varchar, varchar, varchar, varchar, varchar, varchar, varchar, int2);

CREATE OR REPLACE FUNCTION public.update_maestro(p_tarjeta bigint, p_nombre character varying, p_apellido_paterno character varying, p_apellido_materno character varying, p_id_usuario bigint, p_rfc character varying, p_escolaridad_licenciatura character varying, p_estado_licenciatura character varying, p_escolaridad_especializacion character varying, p_estado_especializacion character varying, p_escolaridad_maestria character varying, p_estado_maestria character varying, p_escolaridad_doctorado character varying, p_estado_doctorado character varying, p_id_departamento smallint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the maestro exists
    SELECT EXISTS(SELECT 1 FROM maestros WHERE Tarjeta = p_tarjeta) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: El maestro no existe.';
    END IF;

    -- Update the maestro
    UPDATE maestros
    SET 
        Nombre = p_nombre,
        ApellidoPaterno = p_apellido_paterno,
        ApellidoMaterno = p_apellido_materno,
        IdUsuario = p_id_usuario,
        RFC = p_rfc,
        Escolaridad_Licenciatura = p_escolaridad_licenciatura,
        Estado_Licenciatura = p_estado_licenciatura,
        Escolaridad_Especializacion = p_escolaridad_especializacion,
        Estado_Especializacion = p_estado_especializacion,
        Escolaridad_Maestria = p_escolaridad_maestria,
        Estado_Maestria = p_estado_maestria,
        Escolaridad_Doctorado = p_escolaridad_doctorado,
        Estado_Doctorado = p_estado_doctorado,
        Id_Departamento = p_id_departamento
    WHERE Tarjeta = p_tarjeta;

    RETURN 'Maestro actualizado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_periodo_escolar(int8, varchar, varchar, date, date, bool);

CREATE OR REPLACE FUNCTION public.update_periodo_escolar(p_id bigint, p_codigoperiodo character varying DEFAULT NULL::character varying, p_nombre_periodo character varying DEFAULT NULL::character varying, p_fecha_inicio date DEFAULT NULL::date, p_fecha_fin date DEFAULT NULL::date, p_estado boolean DEFAULT NULL::boolean)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists boolean;
BEGIN
    -- Verificar existencia
    SELECT EXISTS(SELECT 1 FROM periodo_escolar WHERE id_periodo_escolar = p_id)
    INTO v_exists;

    IF NOT v_exists THEN
        RETURN 'Error: El período escolar no existe.';
    END IF;

    -- Actualización parcial; se conserva lo actual con COALESCE
    UPDATE periodo_escolar
    SET
        codigoperiodo  = COALESCE(p_codigoperiodo,  codigoperiodo),
        nombre_periodo = COALESCE(p_nombre_periodo, nombre_periodo),
        fecha_inicio   = COALESCE(p_fecha_inicio,   fecha_inicio),
        fecha_fin      = COALESCE(p_fecha_fin,      fecha_fin),
        estado         = COALESCE(p_estado,         estado),
        updated_at     = now()
    WHERE id_periodo_escolar = p_id;

    -- La restricción CHECK (fecha_inicio <= fecha_fin) y UNIQUE(codigoperiodo)
    -- garantizan consistencia; si violan, se captura la excepción:
    RETURN 'Período escolar actualizado exitosamente';
EXCEPTION
    WHEN unique_violation THEN
        RETURN 'Error: El codigoperiodo ya existe.';
    WHEN check_violation THEN
        RETURN 'Error: fecha_inicio no puede ser mayor que fecha_fin.';
END;
$function$
;

-- DROP FUNCTION public.update_reservacion(int8, int8, int8, date, time, time);

CREATE OR REPLACE FUNCTION public.update_reservacion(p_id bigint, p_numero_control bigint, p_numero_inventario bigint, p_fecha_reservacion date, p_hora_inicio time without time zone, p_hora_fin time without time zone)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the reservation exists
    SELECT EXISTS(SELECT 1 FROM reservacionalumnos WHERE IdReservacionAlumno = p_id) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: La reservación no existe.';
    END IF;

    -- Update the reservation
    UPDATE reservacionalumnos
    SET 
        NumeroControl = p_numero_control,
        NumeroInventario = p_numero_inventario,
        FechaReservacion = p_fecha_reservacion,
        HoraInicio = p_hora_inicio,
        HoraFin = p_hora_fin
    WHERE IdReservacionAlumno = p_id;

    RETURN 'Reservación actualizada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_reservacion_maestro(int8, int8, varchar, date, time, time);

CREATE OR REPLACE FUNCTION public.update_reservacion_maestro(p_id bigint, p_tarjeta bigint, p_clave_aula character varying, p_fecha_reservacion date, p_hora_inicio time without time zone, p_hora_fin time without time zone)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the reservation exists
    SELECT EXISTS(SELECT 1 FROM reservacionmaestros WHERE IdReservacionMaestro = p_id) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: La reservación no existe.';
    END IF;

    -- Update the reservation
    UPDATE reservacionmaestros
    SET 
        Tarjeta = p_tarjeta,
        ClaveAula = p_clave_aula,
        FechaReservacion = p_fecha_reservacion,
        HoraInicio = p_hora_inicio,
        HoraFin = p_hora_fin
    WHERE IdReservacionMaestro = p_id;

    RETURN 'Reservación actualizada exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_role(int8, varchar);

CREATE OR REPLACE FUNCTION public.update_role(p_id bigint, p_nombre character varying)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the role exists
    SELECT EXISTS(SELECT 1 FROM roles WHERE IdRol = p_id) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: El rol no existe.';
    END IF;

    -- Update the role
    UPDATE roles
    SET Nombre = p_nombre
    WHERE IdRol = p_id;

    RETURN 'Rol actualizado exitosamente';
END;
$function$
;

-- DROP FUNCTION public.update_usuario(int8, varchar, varchar, int8);

CREATE OR REPLACE FUNCTION public.update_usuario(p_id_usuario bigint, p_correo character varying, p_password character varying, p_id_rol bigint)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_exists BOOLEAN;
BEGIN
    -- Check if the user exists
    SELECT EXISTS(SELECT 1 FROM usuarios WHERE IdUsuario = p_id_usuario) INTO v_exists;
    IF NOT v_exists THEN
        RETURN 'Error: El usuario no existe.';
    END IF;

    -- Update the user
    UPDATE usuarios
    SET Correo = p_correo, Password = crypt(p_password, gen_salt('bf')), IdRol = p_id_rol
    WHERE IdUsuario = p_id_usuario;

    RETURN 'Usuario actualizado exitosamente';
END;
$function$
;
