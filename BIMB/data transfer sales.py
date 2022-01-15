##quit()

import mysql.connector
qq=0
while qq==0:
    inpn=input("")
    inpn=inpn.split("\t")
    DB = mysql.connector.connect(host="localhost", user="root",password="", database="billmanager")

    DBcur = DB.cursor()
    #[cus_name,sale_id(3006),cus_id(1=cash), bill no ='SEHC-21-22/565', date='2021-12-31', total'190',
    # discount='0', pay type ='cash=2, bpay=3'),[(prd_id='272',qnt='1',sp='160'),(prd_id='272',qnt='1',sp='160')]]
    i=['Cash',3064,1,'SEHC-21-22/556','2021-12-26',400,0,2,[]]

    i[0]=input("enter customer name or 0 for cash ")
    temp1=int(input("enter no of items"))
    for j in range(temp1):
        a=int(input("enter prd_id"))
        c=int(input("enter sp"))
        b=int(input("enter qnt"))
        temp=[a,b,c]
        i[8].append(temp)
    i[4]='2021-'+input("enter date 12-26 ")
    temp=input("enter sale id (eg 556) ")
    i[3]='SEHC-21-22/'+temp
    i[5]=int(input("enter total "))
    if i[0]=='0':
        i[7]=2
    else:
        i[7]=int(input("enter pay type (2=cash,3=bpay) "))

    i[1]=int(input("enter sale id (eg 3064) "))
    temp=0
    for j in i[8]:
        temp=temp+(j[2]*j[1])

    if temp!=i[5]:
        print("total invalid")
        quit()
    try:
        if i[0]!='0':
            qry="INSERT INTO `customers` (`cus_name`) VALUE ('"+i[0]+"');"
            #print
            values=(i[0])
            #print('here1')
            #print(qry,values)
            DBcur.execute(qry)
            #print('here2')
            print(qry,values)
            i[2]=DBcur.lastrowid

    except Exception as e:
        print(e)
        print('here')
        pass
    print(i)
    qry="INSERT INTO `sales` (`sale_id`, `cus_id`, `bill_no`, `sale_date`, `total`, `discount`, `pay_type`, `pay_date`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"
    values=(i[1],i[2],i[3],i[4],i[5],i[6],i[7],i[4])
    DBcur.execute(qry,values)
    print(qry,values)

    for s in i[8]:
        qry="INSERT INTO `sales_product` (`sale_id`, `prd_id`, `qnt`, `sell_price`) VALUES (%s,%s,%s,%s)"
        values=(i[1],s[0],s[1],s[2])
        DBcur.execute(qry,values)
        print(qry,values)

    DB.commit()

    qq=int(input("continue=0 else 1"))
