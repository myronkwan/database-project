insert into customers values ('myronk',4084208007);

insert into repairperson values ('emp1','jim',1948573948);
insert into repairperson values ('emp2','bob',1948993948);

insert into repairitems values ('itemid1','samsung',500.49,1995,1,0,'id1');
insert into repairitems values ('itemid2','samsung2',500.49,1995,0,1,'id1');
insert into repairitems values ('itemid3','samsung2',500.49,1995,0,1,'id1');
insert into servicecontract values ('id1','itemid1',SYSDATE,SYSDATE+7,'myronk',4084208007);
insert into servicecontract values ('id2','ZFXjqxVWh',SYSDATE,SYSDATE+7,'hhher',4084204337);


commit;




