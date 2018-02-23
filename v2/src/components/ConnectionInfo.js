import React from 'react';
import Events from '../services/event-service.js';
import EventConfig from '../event-config.js';
import StatusService from '../services/status-service.js';
import AlarmService from '../services/alarm-service.js';

export default class ConnectionInfo extends React.Component {
    constructor(props) {
        super(props);
        this.interval = null;
        this.ints = 5000;
        this.state = {
            online: "-",
            refresh: "-",
            offline: "-",
            interval: true
        }
    }
    componentWillMount() {
        this.getData();
    }
    componentDidMount() {
        this.interval = setInterval(()=>{
            this.getData();
        }, this.ints);
    }
    componentDidUnMount() {
        if(this.interval){
            clearInterval(this.interval);
        }
    }

    getData() {
        StatusService.getConnections()
            .then(data => {
                console.log('ConnectionInfo', data);
                this.setState({
                    online: data.online,
                    offline: data.offline,
                    refresh: data.refresh,
                });
                console.log('Emit event',EventConfig.STATUS_UPDATE, data)
                Events.emit(EventConfig.STATUS_UPDATE, data);
            })
            .catch(e=>{
                console.log(e);
            })

        AlarmService.getAllAlarm()
            .then(data => {
                console.log('Emit event',EventConfig.ALARM_COUNT, data.totals)
                Events.emit(EventConfig.ALARM_COUNT, data.totals);
            })
            .catch(e=>{
                console.log(e);
            })
    }
    showRefreshBtn() {
        if(this.state.interval){
            return (
                <dd><span className="tzcj undis" style={{display:'block'}} onClick={this.stopRefresh.bind(this)}>停止刷新</span></dd>
            )
        }else{
            return (
                <dd><span className="kscj" style={{display:'block'}} onClick={this.startRefresh.bind(this)}>开始刷新</span></dd>
            )

        }
    }

    startRefresh() {
        console.log("重新开启刷新");
        this.setState({
            interval: true
        });
        this.interval = setInterval(()=>{
            this.getData();
        }, this.ints);
    }

    stopRefresh() {
        console.log('停止刷新');
        clearInterval(this.interval);
        this.interval = null;
        this.setState({
            interval: false
        });
    }

    handleChange(event) {
        this.setState({
            refresh:event.target.value
        })
    }
    render() {
        return (
            <div className="set-wrap">
                <dl className="set-list">
                    <dd>刷新频率 <input type="text" id="collectDuration" value={this.state.refresh} onChange={this.handleChange.bind(this)} />秒</dd>
                    <dd>已连接<span id="linkingNum">{this.state.online}</span>个</dd>
                    <dd>未连接<span id="unlinkNum">{this.state.offline}</span>个</dd>
                </dl>
                <dl className="set-btn">
                    <dd><span className="ggsj">更改时间</span></dd>
                    {this.showRefreshBtn()}
                </dl>
            </div>
        )
    }
}
