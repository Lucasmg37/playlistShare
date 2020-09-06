import ApiService from "./ApiService";

export default class LoginService {
    static login(st_email, st_senha) {
        return ApiService.post("Login", {
            st_email,
            st_senha
        })
    }

    static loginBySpotify(code) {
        return ApiService.post("Login/bySpotify", code)
    }

}