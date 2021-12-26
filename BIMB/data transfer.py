import mysql.connector

DB1 = mysql.connector.connect(host="localhost", user="root",password="", database="sarvodaya")

DB2 = mysql.connector.connect(host="localhost", user="root",password="", database="billmanager")

DB1cur = DB1.cursor()
DB2cur = DB2.cursor()

DB1cur.execute("SELECT `p_id`, `prd_id`, `qnt` FROM `purchase_products` ORDER BY `purchase_products`.`p_id`  ASC")

table = DB1cur.fetchall()

for i in table:
    qry="INSERT INTO `purchase_product`(`pur_id`, `prd_id`, `qnt`) VALUES(%s,%s,%s)"
    values=(i[0],i[1],i[2])
    DB2cur.execute(qry,values)
    print(qry,values)
DB1.commit()
DB2.commit()
