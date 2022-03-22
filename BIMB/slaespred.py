import json
import requests
from flask import Flask, flash, redirect, render_template, request, url_for,jsonify
from flask_cors import CORS
import mysql.connector as connection
import pandas as pd
import matplotlib.pyplot as plt
import numpy as np
from sklearn.linear_model import LinearRegression
from datetime import datetime,timedelta

def DataSelectFunction():
    try:
        mydb = connection.connect(host="localhost", database = 'billmanager',user="root", passwd="",use_pure=True)
        query = "SELECT YEAR(`sale_date`) as yr,WEEKDAY(sale_date) as DW,Month(`sale_date`) as mnt,Day(`sale_date`) as day, sum(`total`) as total FROM `sales` GROUP by sale_date;"
        dF = pd.read_sql(query,mydb)
        print(query)
        mydb.close()#close the connection
        return dF
    except Exception as e:
        mydb.close()
        print(str(e))

#print(dF)
def RunLinearRegression(dF,DW,yr,mnt,day):
    ### LINEAR REGRESSION ###
    pred=np.array([[int(DW),int(yr),int(mnt),int(day)]])
    x=dF[['DW','yr','mnt','day']]
    y=dF['total']
    #print(x)
    #print(y)
    if dF.empty:
        return np.array(["empty"])
    else:
        reg = LinearRegression().fit(x, y)
        print(reg.score(x, y))  ## should be 1 , 0 shows a constant value for any input given 

        #print(reg.coef_)

        #print(reg.intercept_)
        print("prediction==>",reg.predict(pred))
        return reg.predict(pred)

def RunXGBoost(dF,pred):
    ### XG BOOST ###
    from sklearn.ensemble import GradientBoostingClassifier
    x=dF[['DW','yr','mnt','day']]
    y=dF['total']
    
    X_train, X_test = x[:30], x[30:]
    y_train, y_test = y[:30], y[30:]

    clf = GradientBoostingClassifier(n_estimators=100, learning_rate=1.0,
        max_depth=1, random_state=0).fit(X_train, y_train)
    print(clf.score(X_test, y_test)) ## should be maximum


    print(clf.predict(pred))


app = Flask(__name__)
CORS(app)
@app.route('/', methods=['GET','POST'])
def main() :
    return json.dumps(["GOTO http://127.0.0.1:5000/getpred?yr=2021"])
@app.route('/getpred', methods=['GET','POST'])
def getpred():
    if request.method=='GET':
        ret={}
        date=request.args.get('date')
        nod=int(request.args.get('NOD'))
        date = datetime.strptime(str(date), '%Y-%m-%d')
        for i in range(0,nod):
            #print(dt)
            #print(date.isoweekday())
            date=date+ timedelta(days=i)
            df=DataSelectFunction()
            ret[date.strftime("%m/%d/%Y")]=RunLinearRegression(df,date.weekday(),date.year,date.month,date.day)[0]
        return json.dumps(ret)

if __name__=='__main__':
    app.run(debug=True,port=5000,use_reloader=False)
