import ApiService from "./ApiService";

export default class SignUpService {

    static bySpotify(code) {
        return ApiService.post("Signup/bySpotify", code)
    }

    static signUp(st_login, st_senha, st_nome) {
        return ApiService.post("Signup",
            {
                st_login,
                st_senha,
                st_nome
            })
    }

}