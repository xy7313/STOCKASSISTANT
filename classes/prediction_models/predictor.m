%clear all variables
clear all;

%------------ DECLARE A FEW CONSTANTS ------------

%database name
db = 'StockForecasting'; 
%database url
dburl = ['jdbc:mysql://localhost:8889/' db]; 
%mysql driver
driver = 'com.mysql.jdbc.Driver';
%uername and password
username = 'root';
password = 'root';
%number of days ahead to predict
N = 5;

%establish connection to the database
conn = database(db,username,password,driver,dburl);

tic %start timer

%get current year, month, and dat
dateVector = clock;
year = dateVector(1);
month = dateVector(2);
day = dateVector(3);

%format current date
currDate = [num2str(year) '-' num2str(month) '-' num2str(day)];
%format the date of one year prior
yearBefore = [num2str(year - 1) '-' num2str(month) '-' num2str(day)];

%if a connection exists
if isconnection(conn) 
    
    %get all stocks IDs from the database
    curs = exec(conn,'SELECT StockID FROM Stocks');
    curs = fetch(curs);
    [ID] = curs.data;
    
    
   %for each ID
   for currID=2
        %get open, high, low, close, and volume of stocks for the previous
        %year
        curs = exec(conn,['SELECT Open, High, Low, Close, Volume FROM HistoricalPrices WHERE StockID = ' num2str(ID{currID}) ' AND Date > STR_TO_DATE("' yearBefore '", "%Y-%m-%d") ORDER BY Date asc']);
        curs = fetch(curs);  
        data = curs.data;
        
        [fiveDayPrice1, confidence, actual, estimate] = ANN(data, 5, 7);
        fiveDayPrice2 = Prediction_EMA(cell2mat(data(:,4)));
       

 %get current price
 curs = exec(conn,['SELECT Price FROM Stocks WHERE StockID = ' num2str(ID{currID})]);
 curs = fetch(curs);  
 currPrice = curs.data{1};
 
 fiveDayprice = zeros(1,2);
 for i=1:2
    fiveDayprice(i) = mean([fiveDayPrice1(i), fiveDayPrice2(i)]);
 end
 nextDayPrice = fiveDayprice(1)
 avgPredictedprice = mean(fiveDayprice)
 %calculate the gain
 gain = avgPredictedprice - currPrice;
 
 %add in current price into 5 day forecast
 yc = [currPrice fiveDayprice];
 
 %if gain is within 10 cents declare hold decision
 if abs(gain) < 0.1
    decision = 'hold';
    days = 0;
 %otherwise of gain is positive declare buy decision
 elseif gain > 0
    decision = 'buy';
    index = find(yc > avgPredictedprice,1);
    [price, days] = min([currPrice yc(1:index)]);
    days=days-1;
 %otherwise gain is negative, declare hold decision
 else
    decision = 'sell';
    index = find(yc < avgPredictedprice,1);
    [price, days] = min([currPrice yc(1:index)]);
    days=days-1;
 end

%insert all calculated data into the database
%datainsert(conn,'Predictions',{'StockID', 'Date', 'NextDayPrice', 'AvgPrice', 'ConfidenceValue', 'PredictedDecision', 'Gain' ,'WaitTime'},{ID{currID}, datestr(currDate, 'yyyy-mm-dd'), nextDayPrice, avgPredictedprice, confidence, decision, gain, days});
   end

%time take for the prediction session
elapsedTime = toc

%log time
%datainsert(conn,'PredictionTimes',{'Date', 'Time'},{datestr(currDate, 'yyyy-mm-dd'), elapsedTime});

end

%close database connection
close(conn);

