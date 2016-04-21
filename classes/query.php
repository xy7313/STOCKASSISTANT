<?php
	// written by: Manoj Velagaleti
	// tested by: Manoj Velagaleti
	// debugged by: Manoj Velagaleti
	
	class query {
		
		//insert into the historical prices table
		public function insert_historical() {
			return "INSERT INTO HistoricalPrices (StockID, Date, Open, High, Low, Close, Volume, AdjClose) VALUES (?, STR_TO_DATE(?,'%Y-%m-%d'), ?, ?, ?, ?, ?, ?)";
		}
		
		//get historical prices
		public function get_historical() {
			return "SELECT Date, Close FROM HistoricalPrices WHERE stockID = ? AND Date > STR_TO_DATE(?, '%Y-%m-%d') ORDER BY Date asc";
		}
		
		//get all stocks
		public function get_all_stocks() {
			return "SELECT Ticker, Company, Exchange FROM Stocks ORDER BY Ticker";
		}
		
		//get ticker and company names of all stocks
		public function get_all_tickers_and_companies() {
			return "SELECT Ticker, Company FROM Stocks";
		}
		
		//get stock by ticker
		public function get_stock() {
			return "SELECT * FROM Stocks WHERE StockID = ? LIMIT 1";
		}
		
		//get all stockIDs and tickers
		public function get_stockID_and_ticker() {
			return "SELECT StockID,Ticker FROM Stocks";
		}
		
		//get stockID from ticker
		public function get_stockID() {
		return "SELECT StockID FROM Stocks WHERE Ticker = ? LIMIT 1";	
		}
		
		//delete stock by stockID
		public function delete_stock_Stocks() {
		return "DELETE FROM HistoricalPrices WHERE StockID = ?";
		}
		
		public function delete_stock_HistoricalPrices() {
		return "DELETE FROM Stocks WHERE StockID = ?";	
		}
		
		public function update_price() {
			return "UPDATE Stocks SET Price = ?, Time = ? WHERE StockID = ?";
		}
		
		//insert new stock into stocks database
		public function insert_newStock() {
			return "INSERT INTO Stocks (Ticker, Company, Price, Exchange, Description, Time) VALUES (?,?,?,?,?,?)";
		}
		
		//get last insert date for a given stockID
		public function get_last_date() {
			return "SELECT StockID, MAX(Date) AS recentDate from HistoricalPrices GROUP BY StockID";
		}
		
		//get ticker by id()
		public function get_ticker() {
			return "SELECT Ticker FROM Stocks WHERE StockID = ? LIMIT 1";
		}
		
		//get search results count
		public function getSearchCount() {
			return "SELECT COUNT(*) FROM Stocks WHERE (Ticker LIKE ? OR Company LIKE ?)";
		}
		
		//get limited stock ids by keyword
		public function search() {
			return "SELECT StockID FROM Stocks WHERE (Ticker LIKE ? OR Company LIKE ?) LIMIT ?, ?";
		}
		
		//get keyword count 
		public function get_keyword_count() {
			return "SELECT Count FROM Keywords WHERE Keyword = ?";
		}
		
		
		//get top 50 keywords
		public function get_keywords() {
			return "SELECT * FROM Keywords ORDER BY Count desc LIMIT 50";
		}
		
		//insert new keyword
		public function insert_keyword() {
			return "INSERT INTO Keywords (Keyword) Values(?)"; 
		}
		
		//update keyword count
		public function update_keyword() {
			return "UPDATE Keywords SET Count = ? WHERE Keyword = ?";
		}
		
		//get prediction data
		public function get_predictionData() {
			return "SELECT Date, NextDayPrice, AvgPrice, ConfidenceValue, PredictedDecision, WaitTime FROM Predictions WHERE StockID = ? ORDER BY Date desc LIMIT 1";
		}
		
		//get prediction session timings
		public function get_predictionTimes() {
			return "SELECT * FROM PredictionTimes ORDER BY Date desc LIMIT 250";
		}
		
		//get stockIDs of top 5 stocks() 
		public function getSuggestedStocks() {
			return "SELECT StockID FROM Predictions ORDER BY Date desc, Gain desc LIMIT ?, ?";
		}
		
		//get predicted prices for a given stock
		public function get_predicted_prices() {
			return "SELECT NextDayPrice FROM Predictions WHERE StockID=? AND Date > STR_TO_DATE(?, '%Y-%m-%d') ORDER BY Date asc";
		}
		
		//get userID from email
		public function getUserID() {
			return "SELECT UserID FROM Users WHERE Email = ? LIMIT 1";
		}
		
		//get password from email
		public function getPassword() {
			return "SELECT Password FROM Users WHERE Email = ? LIMIT 1";
		}
		
		//get userID and Name from email
		public function getUserInfo() {
			return "SELECT UserID, FirstName, Level FROM Users WHERE Email = ? LIMIT 1";
		}
		
		//insert new user
		public function insertUser() {
			return "INSERT INTO Users (FirstName, LastName, Email, Password, Phone, Carrier) Values(?,?,?,?,?,?)";
		}
		
		//get tracked stock
		public function getTrackedStock() {
			return "SELECT 1 FROM TrackedStocks WHERE UserID = ? AND StockID = ? LIMIT 1";
		}
		
		//get ALL tracked stock
		public function getAllTrackedStocks() {
			return "SELECT StockID FROM TrackedStocks WHERE UserID = ?";
		}
		
		//add tracked stock
		public function addTrackedStock() {
			return "INSERT INTO TrackedStocks (UserID, StockID) VALUES(?,?)";
		}
		
		//remove tracked stock
		public function removeTrackedStock() {
			return "DELETE FROM TrackedStocks WHERE UserID = ? AND StockID = ?";
		}
	}
?>