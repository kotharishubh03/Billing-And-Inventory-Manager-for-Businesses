##quit()
##
##import mysql.connector
##
##DB1 = mysql.connector.connect(host="localhost", user="root",password="", database="sarvodaya")
##
##DB2 = mysql.connector.connect(host="localhost", user="root",password="", database="billmanager")
##
##DB1cur = DB1.cursor()
##DB2cur = DB2.cursor()
##
##DB1cur.execute("SELECT `p_and_s_id`, `prd_id`, `s_id` FROM `product_and_suppliers`")
##
##table = DB1cur.fetchall()
##    
##for i in table:
##    qry="INSERT INTO `products_supplier`(`id`, `prd_id`, `supp_id`) VALUES (%s,%s,%s)"
##    values=(i[0],i[1],i[2])
##    DB2cur.execute(qry,i)
##    print(qry,values)
##DB1.commit()
##DB2.commit()
