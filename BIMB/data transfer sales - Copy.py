##quit()

import mysql.connector
qq=0
while qq==0:
    inpn=input("")
    inpn=inpn.split("\t")
    print(inpn)
    print()
    DB = mysql.connector.connect(host="localhost", user="root",password="", database="billmanager")

    DBcur = DB.cursor()
    #[cus_name,sale_id(3006),cus_id(1=cash), bill no ='SEHC-21-22/565', date='2021-12-31', total'190',
    # discount='0', pay type ='cash=2, bpay=3'),[(prd_id='272',qnt='1',sp='160'),(prd_id='272',qnt='1',sp='160')]]
    i=['Cash',3064,1,'SEHC-21-22/556','2021-12-26',400,0,2,[]]

    if inpn[0]=="" or inpn[0]=="cash":
        i[0]="0"
    else:
        i[0]=inpn[0]
    temp1=int(input("enter no of items"))
    for j in range(temp1):
        a=int(input("enter prd_id"))
        c=int(input("enter sp"))
        b=int(input("enter qnt"))
        temp=[a,b,c]
        i[8].append(temp)
    i[4]='2021-'+inpn[3]+'-'+inpn[2]
    i[3]='SEHC-21-22/'+inpn[4]

    mf=inpn[5].split()
    mf=mf[0].split('.')
    mf[0]=mf[0].replace(',', '')
    print(mf[0])
    i[5]=int(mf[0])

    if i[0]=='0':
        i[7]=2
    else:
        i[7]=3

    i[1]=int(inpn[11])
    temp=0
    for j in i[8]:
        temp=temp+(j[2]*j[1])

    if temp!=i[5]:
        print("total invalid")
        quit()

    print(i)
    print()
    
    if i[0]!='0':
        try:
            qry="INSERT INTO `customers` (`cus_name`) VALUE ('"+i[0]+"');"
            DBcur.execute(qry)
            #print('here2')
            print(qry)
            i[2]=DBcur.lastrowid
        except Exception as e:
            print(e)
            qry="SELECT `cus_id` FROM `customers` WHERE `cus_name`='"+i[0]+"';"
            DBcur.execute(qry)
            myresult = DBcur.fetchone()
            i[2]=myresult[0]
            
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
