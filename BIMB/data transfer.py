##quit()

import mysql.connector

DB1 = mysql.connector.connect(host="localhost", user="root",password="", database="sarvodaya")

DB2 = mysql.connector.connect(host="localhost", user="root",password="", database="billmanager")

DB1cur = DB1.cursor()
DB2cur = DB2.cursor()

DB1cur.execute("SELECT `s_id`, `FY_id`, `amt` FROM `previous_bal`")

table = DB1cur.fetchall()
    
for i in table:
    t=[0,10,11,12,13,14]
    qry="INSERT INTO `supp_pre_bal`(`supp_id`, `fy_id`, `amt`) VALUES (%s,%s,%s)"
    values=(i[0],t[i[1]],i[2])
    DB2cur.execute(qry,values)
    print(qry,values)
DB1.commit()
DB2.commit()
