--TRIGGERS

--trigger to generate customer bill when repair job status is updated to DONE. if there is a contract id then cost is set to 0.
--assumes repairperson is paid 22 dollars an hour when calculating total
create or replace trigger make_bill
after update of status on RepairJob
for each row
DECLARE
	

	n_codes ProblemReport.problemcodes%TYPE;
	n_repairer repairs.empno%TYPE;
	n_hours	number(2);
	n_costs number(6,2);
	n_total number(6,2);

BEGIN
	select problemcodes into n_codes from ProblemReport where :new.repairjobid=repairjobid;
	select empno into n_repairer from repairs where :new.repairjobid=repairjobid;
	n_hours:=dbms_random.value(1,99);
	n_costs:= trunc(dbms_random.value(1,700),2);
	IF :new.contractid is null then 
		n_total:=n_costs + (22*n_hours);
	else 
		n_total:=0.00;
	end if;
	insert into CustomerBill values (:new.repairjobid,:new.machineid,:new.contractid,:new.model,:new.customername,:new.phone,:new.timeofarrival,:new.timeout,n_codes,n_repairer,n_hours,n_costs,n_total);
END;
/
Show Errors;



--trigger to populate repairlog table when repairjob status is updated

create or replace trigger update_repairlog
after update of status on RepairJob
for each row
DECLARE
BEGIN
	insert into repairlog values (:new.repairjobid,:new.machineid,:new.status,SYSDATE); 
END;
/
Show Errors;

--TRIGGER TO UPDATE REPAIRJOB TIMEOUT before REPAIRJOB STATUS UPDATED TO DONE
create or replace trigger update_timeout
before update of status on RepairJob
for each row
DECLARE
BEGIN
	:new.timeout:=SYSDATE;
END;
/
Show Errors;


--trigger to make sure repairitems inserted have a valid contractid
create or replace trigger check_contractid
before insert on repairitems
for each row
DECLARE
	temp varchar2(9);
BEGIN
	select contractid into temp from servicecontract where (contractid=:new.serviceContract and machineid=:new.itemid);
	EXCEPTION
	when NO_DATA_FOUND then
	:new.serviceContract:=NULL;
END;
/
Show Errors;
--trigger to make sure repairjob entry has proper contractid
create or replace trigger beforerepairjobinsert
before insert on repairjob
for each row
DECLARE
	temp varchar2(9);
BEGIN

	select contractid into temp from servicecontract where (contractid=:new.contractid and machineid=:new.machineid);
	EXCEPTION
	when NO_DATA_FOUND then
	:new.contractid:=NULL;

	
END;
/
Show Errors;

create or replace trigger afterrepairjobinsert
after insert on repairjob
for each row
DECLARE
	r_emp varchar2(9);
BEGIN
	--make entry into repairlog
	insert into repairlog values (:new.repairjobid,:new.machineid,:new.status, SYSDATE);
	--make entry into problem report, the codes can be entered through the interface
	insert into ProblemReport values (:new.repairjobid,:new.machineid, NULL);
	--select a random employee to be assigned to repair the item
	select empno into r_emp from (select empno from repairperson order by dbms_random.value) where rownum=1;
	insert into repairs values (:new.repairjobid,r_emp,:new.machineid);


END;
/
Show Errors;



--trigger to update device serviceContract if a new ServiceContract is made and repairjob, and update customerbill
create or replace trigger updateitemcontract
after insert on servicecontract
for each row
DECLARE
BEGIN
	update repairitems set serviceContract=:new.contractid where itemid=:new.machineid;
	update repairjob set contractid=:new.contractid where machineid=:new.machineid;
	update customerbill set contractid=:new.contractid, total=0 where machineid=:new.machineid;
END;
/
Show Errors;


--trigger to update a customer bill if problem codes are also updated after customer bill is already made
create or replace trigger updateproblemcodes
after update on problemreport
for each row
DECLARE
BEGIN
	update customerbill set problemcodes=:new.problemcodes where repairjobid=:new.repairjobid;
END;
/
Show Errors;






