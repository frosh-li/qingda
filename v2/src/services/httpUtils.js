import axios from 'axios';
class HttpUtil {
    constructor() {
        this.host = 'http://47.94.238.179';
    }
    buildUrl(url, params){
        if(!params){
            return url;
        }
        return url +"?"+ params;
    }
    get(url, params = "") {
        url = this.buildUrl(url, params);
        return new Promise((resolve, reject) => {
            axios.get(`${this.host}${url}`, {
                headers: {
                    "Content-Type": 'application/json',
                    "X-Requested-With":'XMLHttpRequest',

                },
                "withCredentials":true
            }).then((data) => {
                if(data.status !== 200 && data.status !== 304) {
                    return reject(new Error('服务器异常'));
                }
                let res = data.data;
                if(res && res.response && res.response.code === 0){
                    return resolve(res.data);
                }else{
                    return reject(new Error(res.response.message || res.response.msg));
                }
            });
        })
    }
    buildParam(obj){
        let ret = [];
        for(let key in obj){
            ret.push(`${key}=${obj[key]}`);
        }
        return ret.join('&');
    }
    post(url, params) {
        let http = axios.create();
        let cparam = this.buildParam(params);
        return new Promise((resolve, reject) => {
            http.post(`${this.host}${url}`,
                cparam,
                {
                    headers: {
                        "Content-Type": 'application/x-www-form-urlencoded',
                    },
                    withCredentials:true
                }).then((data) => {
                if(data.status !== 200 && data.status !== 304) {
                    return reject(new Error('服务器异常'));
                }
                let res = data.data;
                console.log(res);
                if(res && res.response && res.response.code === 0){
                    return resolve(res.data);
                }else{
                    return reject(new Error(res.response.message || res.response.msg));
                }
            });
        })

    }
}

const httpUtil = new HttpUtil();

export default httpUtil;