import HttpUtil from './httpUtils.js';
class TreeService {
    getTreeNodes() {
        let url = '/api/index.php/trees/getnav';
        return HttpUtil.get(url);
    }
}
const treeService = new TreeService();
export default treeService;
