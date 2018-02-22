import HttpUtil from './httpUtils.js';
class UserService {
    login(username, userpass) {
        let url = '/api/index.php/login';
        return HttpUtil.post(url, {
            username: username,
            password: userpass,
        });
    }
}
const userService = new UserService();
export default userService;
