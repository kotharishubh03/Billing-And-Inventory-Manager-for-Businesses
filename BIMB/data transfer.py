import mysql.connector

DB1 = mysql.connector.connect(host="localhost", user="root",password="", database="sarvodaya")

DB2 = mysql.connector.connect(host="localhost", user="root",password="", database="billmanager")

DB1cur = DB1.cursor()
DB2cur = DB2.cursor()

DB1cur.execute("SELECT `p_id`,`tax5`, `gst5`, `tax12`, `gst12`, `tax18`, `gst18`, `tax28`, `gst28` FROM `purchase` ORDER BY `purchase`.`p_id` ASC")

table = DB1cur.fetchall()

for i in table:
    qry="UPDATE `purchase` SET `tax5`=%s,`gst5`=%s,`tax12`=%s,`gst12`=%s,`tax18`=%s,`gst18`=%s,`tax28`=%s,`gst28`=%s WHERE `pur_id`=%s"
    values=(i[1],i[2],i[3],i[4],i[5],i[6],i[7],i[8],i[0])
    DB2cur.execute(qry,values)
    print(qry,values)
DB1.commit()
DB2.commit()

