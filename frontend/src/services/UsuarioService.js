import ApiService from "./ApiService";

export default class UsuarioService {
    static resendEmailActivate(id_usuario) {
        return ApiService.post("Usuario/resendEmailActivate/" + id_usuario)
    }

    static recoveryPassword(st_email) {
        return ApiService.post("Usuario/recoveryPassword/",
            {
                st_email
            })
    }

    static activate(st_code, id_usuario) {
        return ApiService.post("Usuario/activate/",
            {
                id_usuario,
                st_code
            })
    }

    static validateRecovery(st_email, st_code, st_senha) {
        return ApiService.post("Usuario/validateRecovery/",
            {
                st_email,
                st_code,
                st_senha
            })
    }
}