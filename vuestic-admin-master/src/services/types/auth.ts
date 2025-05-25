export interface Maestro {
  Nombre: string;
  ApellidoPaterno: string;
  ApellidoMaterno: string;
  Tarjeta: string;
  RFC: string;
  Escolaridad_Licenciatura: string;
  Estado_Licenciatura: string;
  Escolaridad_Especializacion: string;
  Estado_Especializacion: string;
  Escolaridad_Maestria: string;
  Estado_Maestria: string;
  Escolaridad_Doctorado: string;
  Estado_Doctorado: string;
  Id_Departamento: string;
  Nombre_Departamento: string;
  Abreviacion_Departamento: string;
}

export interface Usuario {
  id: string;
  nombre: string;
  email: string;
  rol: string; // nombre del rol
  idrol: string; // nuevo campo
}


export interface AuthData {
  token: string
  user: Usuario
  maestro: Maestro
}