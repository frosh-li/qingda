CREATE TRIGGER `be` AFTER INSERT ON `tb_battery_module_history` FOR EACH ROW begin
set @sn_key = (select sn_key from tb_battery_module where sn_key = new.sn_key);
if @sn_key > 0 then
	delete from tb_battery_module where sn_key=@sn_key;
end if;
insert into tb_battery_module(record_time,sn_key,bid,gid,mid,sid,Humidity,HumCol,DrvCurrent,DrvCol,Voltage,VolCol,Resistor,ResCol,Temperature,TemCol,Capacity,LifeTime,Dev_R,Dev_U,Dev_T,DevRCol,DevUCol,DevTCol) VALUES(new.record_time,new.sn_key,new.bid,new.gid,new.mid,new.sid,new.Humidity,new.HumCol,new.DrvCurrent,new.DrvCol,new.Voltage,new.VolCol,new.Resistor,new.ResCol,new.Temperature,new.TemCol,new.Capacity,new.LifeTime,new.Dev_R,new.Dev_U,new.Dev_T,new.DevRCol,new.DevUCol,new.DevTCol);
end;

CREATE TRIGGER `aa` AFTER INSERT ON `tb_group_module_history` FOR EACH ROW begin
set @sn_key = (select sn_key from tb_group_module where sn_key = new.sn_key);
if @sn_key > 0 then
	delete from tb_group_module where sn_key=@sn_key;
end if;
insert 
into 
tb_group_module(record_time,sn_key,gid,sid,Humidity,HumCol,Voltage,VolCol,Current,CurCol,Temperature,TemCol,ChaState,Avg_U,Avg_T,Avg_R) 
VALUES(new.record_time,new.sn_key,new.gid,new.sid,new.Humidity,new.HumCol,new.Voltage,new.VolCol,new.Current,new.CurCol,new.Temperature,new.TemCol,new.ChaState,new.Avg_U,new.Avg_T,new.Avg_R);
end;

CREATE TRIGGER `his` AFTER INSERT ON `tb_station_module_history` FOR EACH ROW begin
set @sn_key = (select sn_key from tb_station_module where sn_key = new.sn_key);
if @sn_key > 0 then
	delete from tb_station_module where sn_key=@sn_key;
end if;
insert 
into 
tb_station_module(record_time,sn_key,GroBats,Groups,sid,Temperature,TemCol,Humidity,HumCol,Voltage,VolCol,Current,CurCol,ChaState,Capacity,Lifetime) 
VALUES(new.record_time,new.sn_key,new.GroBats,new.Groups,new.sid,new.Temperature,new.TemCol,new.Humidity,new.HumCol,new.Voltage,new.VolCol,new.Current,new.CurCol,new.ChaState,new.Capacity,new.Lifetime);
end;