class EventConfig {

    /**
     * 服务器各种状态事件
     *
     * @return {type}  description
     */
    get STATUS_UPDATE() {
        return "STATUS_UPDATE";
    }

    get ALARM_COUNT() {
        return "ALARM_COUNT";
    }
}

const Service = new EventConfig()

export default Service;
