
create table RepairItems(
	itemid varchar2(9) primary key,
	model varchar2(9),
	price number(6,2),
	year number(4),
	computer number(1),
	printer number(1),
	constraint check_device check ((computer=1 and printer=0) or (computer=0 and printer=1)),
	serviceContract varchar2(9)
);


create table customers(
	name varchar2(9),
	phone number(10) primary key
);

create table ServiceContract(
	contractid varchar2(9) primary key,
	machineid varchar2(9),
	startdate date,
	enddate date,
	name varchar2(9),
	phone number(10)
);	

create table RepairJob(
	repairjobid varchar2(9) primary key,
	machineid varchar2(9) references repairitems(itemid) on delete cascade,
	contractid varchar2(9),
	status varchar2(12),
	constraint check_status check (status in ('UNDER_REPAIR','READY','DONE')),
	timeofarrival date,
	model varchar2(9),
	customername varchar2(9),
	phone number(10),
	timeout date
);


create table repairperson(
	empno varchar2(9) primary key,
	name varchar2(9),
	phone number(10) unique
);

create table ProblemReport(
	repairjobid varchar2(9) references RepairJob(repairjobid),
	machineid varchar2(9) references RepairItems(itemid) on delete cascade,
	problemcodes varchar2(20),
	constraint pk_pr primary key (repairjobid)
);

create table CustomerBill(
	repairjobid varchar2(9) references RepairJob(repairjobid),
	machineid varchar2(9),
	constraint pk_cb primary key (repairjobid),
	contractid varchar2(9),
	model varchar2(9),
	customername varchar2(9),
	phone number(10),
	timein date,
	timeout date,
	problemcodes varchar(20),
	repairperson varchar2(9),
	laborhours number(2),
	costs number(6,2),
	total number(6,2)
);


create table repairlog(
	repairjobid varchar2(9) references RepairJob(repairjobid),
	machineid varchar2(9) references RepairItems(itemid),
	status varchar2(12),
	constraint repairstatuscheck check (status in ('UNDER_REPAIR','READY','DONE')),
	updated date,
	constraint pk_rl primary key (repairjobid,status)
);

--RELATIONSHIPs
create table repairs(
	repairjobid varchar2(9) references repairjob(repairjobid) on delete cascade,
	empno varchar2(9),
	machineid varchar2(9),
	constraint pk_r primary key (repairjobid)
);






