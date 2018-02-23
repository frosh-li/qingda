import React from 'react';
import ReactDOM from 'react-dom';
import ConnectionInfo from '../components/ConnectionInfo.js';
import TopRightStatus from '../components/TopRightStatus.js';
import TreeNav from '../components/TreeNav.js';

class ManageController {
    realStation(request) {
        ReactDOM.render(
            <span id="wrap">
                <div className="top ">
                    <img src={require("../images/logo.png")} width="305" height="38" className="logo" alt="logo" />
                    <div className="top-nav">
                        <ul className="nav-list">
                            <li className="active switch-btn manage"><a href="#/manage/station"><img src={require("../images/nav-1.png")} /><span>实时数据</span></a></li>
                            <li className="switch-btn qurey"><a href="#/qurey/qureyStation"><img src={require("../images/nav-2.png")} /><span>查询</span></a></li>
                            <li className="switch-btn report"><a href="#/report/reportCaution"><img src={require("../images/nav-3.png")} /><span>报表</span></a></li>
                            <li className="switch-btn settings"><a href="#/settings/stationInfo/tree"><img src={require("../images/nav-4.png")} /><span>设置</span></a></li>
                            <li className="switch-btn"><a id="about" href="javascript:void(0)"><img src={require("../images/nav-5.png")} /><span>关于</span></a></li>
                            <li className="switch-btn"><a target="_blank" href="/help.html"><img src={require("../images/nav-6.png")} /><span>帮助</span></a></li>
                        </ul>
                        <div className="user" id="switchUser"><img src={require("../images/user-img.png")} /><span>切换用户</span> </div>
                        <span className="exit" id="logout"><span>退出系统</span></span>
                    </div>
                    <div className="clear"></div>
                </div>
                <div className="wrap" id="main">
                    <div className="left">
                        <div className="set">
                            <span className="left-hide" title="隐藏树形图"></span>
                            <div className="clear"></div>
                            <ConnectionInfo />
                            <div className="clear"></div>
                            <div className="choose-nav" ><div className="nav-tree ztree" id="nav">
                                <TreeNav />
                            </div></div>
                            <div className="search-bar">
                                <span className="search" id="navSearchBtn" ></span>
                                <input type="text" id="navSearchKeyword" placeholder="搜索" autoComplete="false" name="navSearchKeyword" value="" />
                            </div>
                        </div>
                    </div>
                    <div className="right">
                        <div className="up">
                            <div className="right-header">
                                <div className="show-bar">
                                    <b className="yx-show" title="显示树形图"><i className="icon yunxing"></i>树形图</b>
                                    <strong>实时数据</strong>
                                    <TopRightStatus />
                                </div>
                                <div className="title-list">
                                    <span className="zoom" title="窗口最大化"></span>
                                    <ul className="item-list">
                                        <li className="active undis switch-btn manage station"><span><a href="#/manage/station">站数据</a></span><i htmlFor="station"></i></li>
                                        <li className="switch-btn undis manage group"><span><a href="#/manage/group">组数据</a></span><i htmlFor="group"></i></li>
                                        <li className="switch-btn undis manage battery"><span><a href="#/manage/battery">电池数据</a></span><i htmlFor="battery"></i></li>
                                        <li className="switch-btn undis manage caution"><span><a href="#/manage/caution">数据报警</a></span><i htmlFor="caution"></i></li>
                                        <li className="switch-btn undis manage systemAlarm"><span><a href="#/manage/systemAlarm">系统报警</a></span><i htmlFor="systemAlarm"></i></li>
                                        <li className="switch-btn undis manage newstations"><span><a href="#/manage/newstations">待添加站</a></span><i htmlFor="newstations"></i></li>

                                        {/* <!-- 设置 --> */}
                                        <li className="active switch-btn undis settings stationInfo"><span><a href="#/settings/stationInfo/stationSituation">基本信息</a></span></li>
                                        <li className="switch-btn undis settings manager"><span><a href="#/settings/manager/personal">管理人员</a></span></li>
                                        <li className="switch-btn undis settings message"><span><a href="#/settings/message">短信/邮箱</a></span></li>
                                        <li className="switch-btn undis settings optionSetting"><span><a href="#/settings/optionSetting/stationOption">参数</a></span></li>
                                        <li className="switch-btn undis settings limitationSetting"><span><a href="#/settings/limitationSetting">报警级别</a></span></li>
                                        <li className="switch-btn undis settings cautionEquipmentSetting"><span><a href="#/settings/cautionEquipmentSetting/upperMonitor">报警设备</a></span></li>
                                        <li className="switch-btn undis settings update"><span><a href="#/settings/update">远程升级</a></span></li>
                                        <li className="switch-btn undis settings equipmentSetting"><span><a href="#/settings/equipmentSetting">外控设备</a></span></li>
                                        {/* <!-- 报表 --> */}
                                        <li className="active switch-btn undis report reportCaution"><span><a href="#/report/reportCaution">警情历史表</a></span></li>
                                        <li className="switch-btn undis report deviationTrend"><span><a href="#/report/deviationTrend">偏离趋势报表</a></span></li>
                                        <li className="switch-btn undis report chargeOrDischarge"><span><a href="#/report/chargeOrDischarge">充放电统计表</a></span></li>
                                        <li className="switch-btn undis report batteryLife"><span><a href="#/report/batteryLife">电池使用年限统计表</a></span></li>
                                        <li className="switch-btn undis report reportUilog"><span><a href="#/report/reportUilog/options">UI日志表</a></span></li>
                                        {/* <!-- 查询 --> */}
                                        <li className="active switch-btn undis qurey qureyStation"><span><a href="#/qurey/qureyStation">站数据</a><i htmlFor="station"></i></span></li>
                                        <li className="active switch-btn undis qurey qureyGroup"><span><a href="#/qurey/qureyGroup">组数据</a><i htmlFor="group"></i></span></li>
                                        <li className="active switch-btn undis qurey qureyBattery"><span><a href="#/qurey/qureyBattery">电池数据</a><i htmlFor="battery"></i></span></li>
                                        <li className="active switch-btn undis qurey qureyCaution"><span><a href="#/qurey/qureyCaution">数据报警</a></span></li>
                                        <li className="active switch-btn undis qurey baseinfo"><span><a href="#/qurey/baseinfo/queryStationSituation">基本信息</a></span></li>
                                        {/* <!--li className="active switch-btn undis qurey stationOption"><span><a href="#/qurey/stationOption/queryStationOption">参数</a></span></li--> */}
                                        <li className="active switch-btn undis qurey uilog"><span><a href="#/qurey/uilog/options">UI日志</a></span></li>
                                        {/* <!--li className="active switch-btn undis qurey limitation"><span><a href="#/qurey/limitation">门限</a></span></li--> */}
                                        <li className="active switch-btn undis qurey runlog"><span><a href="#/qurey/runlog">运行日志</a></span></li>
                                        {/* <!--li className="active switch-btn undis qurey option"><span><a href="#/qurey/option/stationOption">参数</a></span></li--> */}
                                        <li className="active switch-btn undis qurey equipment"><span><a href="#/qurey/equipment">外控设备</a></span></li>
                                        <li className="active switch-btn undis qurey adminConfig"><span><a href="#/qurey/adminConfig">管理人员</a></span></li>
                                        <li className="active switch-btn undis qurey IRCollect"><span><a href="#/qurey/IRCollect">强制内阻采集</a></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div className="data-wrap">
                                <div className="list-header">
                                    <div className="kscj blocks" style={{display:"none",height:"50px","lineHeight": "50px",}} id="collectIRWrap">

                                        <span>每次只能采集一个站点</span>

                                        <input type="password" id="cj_password" value="" />

                                        <a href="javascript:void(0)" id="startCollectR">开始采集</a>

                                    </div>
                                    {/* <!--kscj--> */}
                                    {/* <!--搜索条--> */}
                                    <htmlForm className="search-jqtranshtmlForm undis">
                                        <div className="rowElem">
                                            <label className="item-title reportCaution" htmlFor="name">警情分类</label>
                                            <select name="ff" className="report-caution-selector" id="cationCategory">
                                                <option value="ALL">全部</option>
                                                <option value="R">红</option>
                                                <option value="O">橙</option>
                                                <option value="Y">黄</option>

                                            </select>
                                            <label className="item-title" htmlFor="name">开始时间</label>
                                            <input type="text" id="beginTime"/>
                                            <label className="item-title" htmlFor="name">结束时间</label>
                                            <input type="text" id="endTime"/>
                                            <input type="button" value="查询" id="searchBtn"/>
                                        </div>
                                    </htmlForm>
                                    {/* <!--列表二级导航--> */}
                                    <ul className="sub-list-tab" id="subListTab">
                                        <li className="active undis switch-btn stationInfo tree"><span><a href="#/settings/stationInfo/tree">树形结构图</a></span><i></i></li>
                                        <li className="active undis switch-btn stationInfo stationSituation"><span><a href="#/settings/stationInfo/stationSituation">站点情况</a></span><i></i></li>
                                        <li className="active undis switch-btn stationInfo batterys"><span><a href="#/settings/stationInfo/batterys">电池信息表</a></span><i></i></li>
                                        <li className="active undis switch-btn stationInfo institutions"><span><a href="#/settings/stationInfo/institutions">用户单位信息表</a></span><i></i></li>
                                        <li className="active undis switch-btn stationInfo upsInfo"><span><a href="#/settings/stationInfo/upsInfo">UPS信息表</a></span><i></i></li>
                                        <li className="active undis switch-btn stationInfo monitorSeller"><span><a href="#/settings/stationInfo/monitorSeller">BMS厂家信息</a></span><i></i></li>

                                        <li className="active undis switch-btn baseinfo queryStationSituation"><span><a href="#/qurey/baseinfo/queryStationSituation">站点信息</a></span><i></i></li>
                                        <li className="active undis switch-btn baseinfo queryBatterys"><span><a href="#/qurey/baseinfo/queryBatterys">电池信息表</a></span><i></i></li>
                                        <li className="active undis switch-btn baseinfo queryInstitutions"><span><a href="#/qurey/baseinfo/queryInstitutions">用户单位信息表</a></span><i></i></li>

                                        <li className="active undis switch-btn baseinfo queryUpsInfo"><span><a href="#/qurey/baseinfo/queryUpsInfo">UPS信息表</a></span><i></i></li>
                                        <li className="active undis switch-btn baseinfo queryMonitorSeller"><span><a href="#/qurey/baseinfo/queryMonitorSeller">BMS厂家信息</a></span><i></i></li>

                                        <li className="active undis switch-btn manager personal"><span><a href="#/settings/manager/personal">人员</a></span><i></i></li>
                                        <li className="active undis switch-btn manager role"><span><a href="#/settings/manager/role">角色权限</a></span><i></i></li>

                                        <li className="switch-btn undis optionSetting stationOption"><span><a href="#/settings/optionSetting/stationOption">站参数</a></span><i></i></li>
                                        <li className="switch-btn undis optionSetting groupOption"><span><a href="#/settings/optionSetting/groupOption">组参数</a></span><i></i></li>
                                        <li className="switch-btn undis optionSetting batteryOption"><span><a href="#/settings/optionSetting/batteryOption">电池参数</a></span><i></i></li>
                                        <li className="switch-btn undis optionSetting systemOption"><span><a href="#/settings/optionSetting/systemOption">系统报警参数</a></span><i></i></li>
                                        <li className="switch-btn optionSetting otherOption"><span><a href="#/settings/optionSetting/otherOption">其他参数</a></span><i></i></li>

                                        <li className="switch-btn undis option stationOption"><span><a href="#/qurey/option/stationOption">站参数</a></span><i></i></li>
                                        <li className="switch-btn undis option groupOption"><span><a href="#/qurey/option/groupOption">组参数</a></span><i></i></li>
                                        <li className="switch-btn undis option batteryOption"><span><a href="#/qurey/option/batteryOption">电池参数</a></span><i></i></li>

                                        <li className="active switch-btn undis cautionEquipmentSetting upperMonitor"><span><a href="#/settings/cautionEquipmentSetting/upperMonitor">上位机设备</a></span><i></i></li>
                                        <li className="switch-btn undis cautionEquipmentSetting stationMonitor"><span><a href="#/settings/cautionEquipmentSetting/stationMonitor">站设备</a></span><i></i></li>

                                        <li className="switch-btn undis reportUilog options"><span><a href="#/report/reportUilog/options">设置</a></span><i></i></li>
                                        <li className="switch-btn undis reportUilog user"><span><a href="#/report/reportUilog/user">用户登录登出</a></span><i></i></li>
                                        <li className="switch-btn undis reportUilog other"><span><a href="#/report/reportUilog/other">其他</a></span><i></i></li>

                                        <li className="switch-btn undis uilog options"><span><a href="#/qurey/uilog/options">设置</a></span><i></i></li>
                                        <li className="switch-btn undis uilog user"><span><a href="#/qurey/uilog/user">用户登录登出</a></span><i></i></li>
                                        <li className="switch-btn undis uilog other"><span><a href="#/qurey/uilog/other">其他</a></span><i></i></li>
                                    </ul>
                                    {/* <!--按钮组--> */}
                                    <ul className="list-btns" id="listBtns">
                                        <li className="stationInfo-stationSituation undis" id="addStation">添加站点</li>
                                        {/* <!--<li className="optionSetting-stationOption undis" id="addStationOption">添加站参数</li>--> */}
                                        <li className="stationInfo-monitorSeller undis" id="addBMS">添加BMS</li>
                                        <li className="stationInfo-institutions undis" id="addCompany">添加单位</li>
                                        <li className="stationInfo-batterys undis" id="addBattery">添加电池</li>
                                        <li className="stationInfo-upsInfo undis" id="addUps">添加UPS</li>
                                        <li className="manager-personal undis" id="addPersonal">添加人员</li>
                                        <li className="manager-role undis" id="addRole">添加角色</li>
                                        <li className="message undis" id="addMessage">设置短信/邮箱</li>
                                        <li className="cautionEquipmentSetting-upperMonitor undis" id="optionPort">设置端口</li>
                                        <li className="equipmentSetting undis" id="addDevice">添加外控设备</li>
                                    </ul>
                                </div>
                                <div className="data-item" id="dataItem"></div>
                                {/* <!--data-item--> */}
                            </div>
                            {/* <!--data-wrap--> */}
                        </div>
                        {/* <!--up--> */}
                        <div className="down chart-wrap" id="down">
                                <span className="bottom-hide" title="隐藏趋势图"></span>
                                <div className="clear"></div>
                                <h4>环境温度</h4>
                            <div className="show-data" id="chart" style={{"minHeight":"300px",}}></div>
                            <div className="btn-wrap clearfix">
                                <div className="btn-bg undis btns station qureyStation">
                                    <a className="switch-btn l-radius active" field="Temperature">
                                        环境温度
                                        <span className="hide">(°C)</span>
                                    </a>
                                    <a className="switch-btn" field="Humidity">
                                        环境湿度
                                        <span className="hide">(%)</span>
                                    </a>
                                    <a className="switch-btn" field="Lifetime">
                                        预估寿命
                                        <span className="hide">(%)</span>
                                    </a>
                                    <a className="r-radius switch-btn" field="Capacity">
                                        预估容量
                                        <span className="hide">(%)</span>
                                    </a>
                                </div>


                                <div className="btn-bg undis btns group qureyGroup">
                                    <a className="l-radius switch-btn active" field="Current">
                                        组电流
                                        <span className="hide">(A)</span>
                                    </a>
                                    <a className="switch-btn disabled" field="Capacity">
                                        氢气浓度
                                    </a>
                                    <a className="switch-btn disabled" field="Capacity">
                                        氧气浓度
                                    </a>
                                    <a className="switch-btn" field="Temperature">
                                        组温度
                                        <span className="hide">(°C)</span>
                                    </a>
                                    <a className="r-radius switch-btn" field="Humidity">
                                        组湿度
                                        <span className="hide">(%)</span>
                                    </a>
                                </div>


                                <div className="btn-bg undis btns battery qureyBattery">
                                    <a className="l-radius switch-btn active" field="Voltage">
                                        电压状态
                                        <span className="hide">(V)</span>
                                    </a>
                                    <a className="switch-btn" field="Temperature">
                                        温度状态
                                        <span className="hide">(°C)</span>
                                    </a>
                                    <a className="switch-btn" field="Resistor">
                                        内阻状态
                                        <span className="hide">(mΩ)</span>
                                    </a>
                                    <a className=" switch-btn" field="Capacity">
                                        预估容量
                                        <span className="hide">(%)</span>
                                    </a>
                                    <a className="r-radius switch-btn" field="Lifetime">
                                        电池寿命
                                        <span className="hide">(%)</span>
                                    </a>
                                </div>

                                <div className="btn-bg undis btns caution qureyCation"><a className="l-radius switch-btn active hide" field="U">警情统计</a></div>
                                <span className="shift-btn">柱线切换</span>
                                <p>
                                    <em className="color red"></em>超限
                                    <em className="color orange"></em>警告
                                    <em className="color yellow"></em>关注
                                    <em className="color green"></em>正常
                                </p>
                            </div>
                            {/* <!--btn-list--> */}
                        </div>
                        {/* <!--down--> */}
                    </div>
                    {/* <!--right--> */}
                    <div className="clear"></div>
                </div>
                <div className="bottom">
                    <ul className="bottom-list">
                        <li>
                            <b className="yx-show" title="显示树形图"><i className="icon yunxing"></i>树形图</b>
                            <b className="bottom-show" title="显示趋势图"><i className="icon yunxing2"></i>趋势图</b>
                            <span>系统启动时间：<i id="stat_sys_uptime">08：08：08</i></span></li>
                        <li><span>当前管理员：<i id="stat_manager">XXXX</i></span></li>
                        <li><span>登陆时间：<i id="stat_login_time">11：22：52</i></span></li>
                        <li><span id="realtime">2015年11月30日&nbsp;&nbsp;16:00&nbsp;&nbsp;星期六</span></li>
                    </ul>
                </div>
            </span>,
            document.querySelector('#root')
        )
    }
}
const Manage = new ManageController();
export default Manage;
