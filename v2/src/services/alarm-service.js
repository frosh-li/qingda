import HttpUtil from './httpUtils.js';
class AlarmService {
    getAllAlarm(username, userpass) {
        let url = '/api/index.php/realtime/galarm';
        return HttpUtil.post(url);
    }
}
const userService = new AlarmService();
export default userService;
