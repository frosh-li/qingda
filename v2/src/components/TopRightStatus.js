import React from 'react';
import Events from '../services/event-service.js';
import EventConfig from '../event-config.js';

export default class TopRightStatus extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            collecting: 'grelight',
            light: 'grelight',
            sound: 'grelight',
            sms: 'grelight',
            mail: 'grelight',
            totalAlarm: 0,
        }
    }
    componentWillMount() {
        Events.on(EventConfig.STATUS_UPDATE, this.handleStatusUpdate);
        Events.on(EventConfig.ALARM_COUNT, this.handleAlarmUpdate);
    }
    componentDidUnMount(){
        Events.removeListener(EventConfig.STATUS_UPDATE, this.handleStatusUpdate);
        Events.removeListener(EventConfig.ALARM_COUNT, this.handleAlarmUpdate);
    }
    handleStatusUpdate = (event) => {
        console.log('handleStatusUpdate', event);
        this.setState({
            collecting: event.upiao == 1 ? 'grelight':'graylight',
            light: event.light_on_off == 1 ? 'grelight':'graylight',
            sound: event.voice_on_off == 1 ? 'grelight':'graylight',
            sms: event.sms_on_off == 1 ? 'grelight':'graylight',
            mail: event.email_on_off == 1 ? 'grelight':'graylight',
        })
    }
    handleAlarmUpdate = (event) => {
        console.log('handleAlarmUpdate', event, this.state.totalAlarm);
        if(event == this.state.totalAlarm){
            return;
        }
        else{
            this.setState({
                "totalAlarm": event
            })
        }
    }
    renderAlarm() {
        console.log('renderAlarm');
        if(this.state.totalAlarm > 0){
            return (
                <dd className="baojing" style={{display: "block"}}><img src={require("../img/alarm.gif?v1")} /><em>数据报警</em><span className="bg">{this.state.totalAlarm}</span><em>条</em></dd>
            )
        }else{
            return (
                <dd className="baojing" style={{display: "none"}}><img src={require("../img/alarm.gif?v1")} /><em>数据报警</em><span className="bg">0</span><em>条</em></dd>
            )
        }
    }
    render() {
        return (
            <div className="show-list">
                <dl>
                    <dd><i className={'icon ' + this.state.collecting} id="alarm_collecting"></i><a>数据采集</a></dd>
                    <dd><i className={"icon " + this.state.light} id="alarm_light"></i><a>灯光报警</a></dd>
                    <dd><i className={"icon " + this.state.sound} id="alarm_sound"></i><a>声音报警</a></dd>
                    <dd><i className={"icon " + this.state.sms} id="alarm_sms"></i><a>短信</a></dd>
                    <dd><i className={"icon " + this.state.mail} id="alarm_mail"></i><a>邮件</a></dd>
                    {this.renderAlarm()}
                    <dd><a className="btn1" href="#/manage/caution">查看警情</a></dd>
                    <dd className="mleft"><a className="btn2 stationPop">站信息</a></dd>
                    <dd ><a className="map-switch" href="#/map" id="map-switch" style={{display:"none"}}><img src={require("../images/switch-map.png?v1")} title="切换为地图模式" style={{width:"30px",height:"30px",}} /></a></dd>
                </dl>
            </div>
        )
    }
}
