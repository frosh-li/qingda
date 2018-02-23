import HttpUtil from './httpUtils.js';
class StatusService {
    getConnections() {
        let url = '/api/index.php/stat';
        return HttpUtil.get(url);
    }
}
const Service = new StatusService();
export default Service;
